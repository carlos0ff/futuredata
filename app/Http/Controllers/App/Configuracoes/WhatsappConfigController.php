<?php

namespace App\Http\Controllers\App\Configuracoes;

use App\Http\Controllers\Controller;
use App\Models\Mensagem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class WhatsappConfigController extends Controller
{
    public function index(): View
    {
        $config = [
            'url'      => config('whatsapp.evolution.url', ''),
            'key'      => config('whatsapp.evolution.key', ''),
            'instance' => config('whatsapp.evolution.instance', 'futuredata'),
            'enabled'  => config('whatsapp.bot_enabled', true),
            'n8n_url'  => config('services.n8n.webhook_url', ''),
        ];

        $stats = [
            'hoje'      => Mensagem::whereDate('created_at', today())->count(),
            'total'     => Mensagem::count(),
            'recebidas' => Mensagem::where('tipo', 'cliente')->whereDate('created_at', today())->count(),
            'enviadas'  => Mensagem::where('tipo', 'tecnico')->whereDate('created_at', today())->count(),
        ];

        $recentes = Mensagem::with('ordem.cliente')
            ->whereDate('created_at', today())
            ->latest()
            ->take(5)
            ->get();

        return view('app.configuracoes.whatsapp', compact('config', 'stats', 'recentes'));
    }

    public function save(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'url'      => 'required|url',
            'key'      => 'required|string',
            'instance' => 'required|string',
            'secret'   => 'nullable|string',
            'n8n_url'  => 'nullable|url',
        ]);

        $env = [
            'WHATSAPP_EVOLUTION_URL'      => $data['url'],
            'WHATSAPP_EVOLUTION_KEY'      => $data['key'],
            'WHATSAPP_EVOLUTION_INSTANCE' => $data['instance'],
            'WHATSAPP_WEBHOOK_SECRET'     => $data['secret'] ?? '',
        ];

        if (! empty($data['n8n_url'])) {
            $env['N8N_WEBHOOK_URL'] = $data['n8n_url'];
        }

        $this->updateEnv($env);

        return back()->with('success', 'Configurações salvas com sucesso!');
    }

    public function status(): JsonResponse
    {
        $cfg = config('whatsapp.evolution');

        if (empty($cfg['url']) || empty($cfg['key'])) {
            return response()->json(['state' => 'not_configured']);
        }

        try {
            $res = Http::timeout(5)
                ->withHeaders(['apikey' => $cfg['key']])
                ->get("{$cfg['url']}/instance/connectionState/{$cfg['instance']}");

            if ($res->successful()) {
                $body  = $res->json();
                $state = $body['instance']['state'] ?? $body['state'] ?? 'unknown';
                $phone = $body['instance']['profileName'] ?? $body['instance']['wuid'] ?? null;

                return response()->json(['state' => $state, 'phone' => $phone]);
            }

            return response()->json(['state' => 'error', 'code' => $res->status()]);
        } catch (\Throwable $e) {
            return response()->json(['state' => 'offline']);
        }
    }

    public function qrcode(): JsonResponse
    {
        $cfg = config('whatsapp.evolution');

        if (empty($cfg['url']) || empty($cfg['key'])) {
            return response()->json(['error' => 'Não configurado'], 422);
        }

        try {
            $res = Http::timeout(10)
                ->withHeaders(['apikey' => $cfg['key']])
                ->get("{$cfg['url']}/instance/connect/{$cfg['instance']}");

            if (! $res->successful()) {
                return response()->json(['error' => 'Falha ao obter QR code (HTTP ' . $res->status() . ')'], 422);
            }

            $body  = $res->json();
            $state = $body['instance']['state'] ?? $body['state'] ?? null;

            // Instância já conectada — não há QR a mostrar
            if ($state === 'open') {
                return response()->json(['connected' => true]);
            }

            $qr = $body['base64'] ?? $body['qrcode']['base64'] ?? null;

            if (! $qr) {
                return response()->json(['error' => 'QR Code indisponível. Tente desconectar e reconectar a instância no Evolution Manager.'], 422);
            }

            return response()->json(['qr' => $qr]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /** Registra o webhook na Evolution API automaticamente. */
    public function registerWebhook(Request $request): JsonResponse
    {
        $cfg        = config('whatsapp.evolution');
        $webhookUrl = url('/webhook/whatsapp');

        if (empty($cfg['url']) || empty($cfg['key'])) {
            return response()->json(['error' => 'Evolution API não configurada.'], 422);
        }

        try {
            $res = Http::timeout(10)
                ->withHeaders(['apikey' => $cfg['key'], 'Content-Type' => 'application/json'])
                ->post("{$cfg['url']}/webhook/set/{$cfg['instance']}", [
                    'webhook' => [
                        'url'            => $webhookUrl,
                        'enabled'        => true,
                        'webhookByEvents' => false,
                        'webhookBase64'  => false,
                        'events'         => ['MESSAGES_UPSERT', 'CONNECTION_UPDATE'],
                    ],
                ]);

            if ($res->successful()) {
                return response()->json(['ok' => true, 'url' => $webhookUrl]);
            }

            return response()->json(['error' => 'Evolution retornou: ' . $res->status()], 422);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /** Testa o webhook n8n disparando um evento de teste. */
    public function testN8n(Request $request): JsonResponse
    {
        $url = $request->input('url') ?: config('services.n8n.webhook_url');

        if (! $url) {
            return response()->json(['error' => 'URL do n8n não configurada.'], 422);
        }

        try {
            $res = Http::timeout(5)->post($url, [
                'event'     => 'test',
                'source'    => 'FutureData',
                'message'   => 'Teste de conexão do FutureData com n8n.',
                'timestamp' => now()->toIso8601String(),
            ]);

            return response()->json(['ok' => true, 'status' => $res->status()]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function botToggle(Request $request): JsonResponse
    {
        $enabled = (bool) $request->input('enabled', true);
        $this->updateEnv(['WHATSAPP_BOT_ENABLED' => $enabled ? 'true' : 'false']);

        return response()->json(['enabled' => $enabled]);
    }

    private function updateEnv(array $values): void
    {
        $envPath = base_path('.env');
        $content = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            $escaped = addslashes($value);
            $line    = "{$key}={$escaped}";

            if (preg_match("/^{$key}=.*/m", $content)) {
                $content = preg_replace("/^{$key}=.*/m", $line, $content);
            } else {
                $content .= "\n{$line}";
            }
        }

        file_put_contents($envPath, $content);
        Artisan::call('config:clear');
    }
}
