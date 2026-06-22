<?php

namespace App\Http\Controllers\App\Configuracoes;

use App\Http\Controllers\Controller;
use App\Services\WhatsappService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Configuração e monitoramento do WhatsApp / Evolution API.
 *
 * Rotas:
 * - GET  /app/whatsapp            → index()       — tela principal
 * - POST /app/whatsapp/save       → save()        — salva .env / config
 * - GET  /app/whatsapp/status     → status()      — status de conexão (AJAX)
 * - GET  /app/whatsapp/qrcode     → qrcode()      — QR code para conexão (AJAX)
 * - POST /app/whatsapp/connect    → connect()     — inicia instância
 * - POST /app/whatsapp/disconnect → disconnect()  — desconecta instância
 * - POST /app/whatsapp/bot-toggle → botToggle()   — habilita/desabilita bot
 */
class WhatsappConfigController extends Controller
{
    /**
     * Tela de configuração do WhatsApp.
     */
    public function index(): View
    {
        $config = [
            'url'      => config('whatsapp.evolution.url', ''),
            'key'      => config('whatsapp.evolution.key', ''),
            'instance' => config('whatsapp.evolution.instance', 'futuredata'),
            'enabled'  => config('whatsapp.bot_enabled', true),
        ];

        return view('app.configuracoes.whatsapp', compact('config'));
    }

    /**
     * Salva as configurações no arquivo .env do projeto.
     */
    public function save(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'url'      => 'required|url',
            'key'      => 'required|string',
            'instance' => 'required|string|alpha_dash',
            'secret'   => 'nullable|string',
        ]);

        $this->updateEnv([
            'WHATSAPP_EVOLUTION_URL'      => $data['url'],
            'WHATSAPP_EVOLUTION_KEY'      => $data['key'],
            'WHATSAPP_EVOLUTION_INSTANCE' => $data['instance'],
            'WHATSAPP_WEBHOOK_SECRET'     => $data['secret'] ?? '',
        ]);

        return back()->with('success', 'Configurações do WhatsApp salvas com sucesso!');
    }

    /**
     * Retorna o status de conexão da instância Evolution API (AJAX).
     */
    public function status(): JsonResponse
    {
        $cfg = config('whatsapp.evolution');

        if (empty($cfg['url']) || empty($cfg['key'])) {
            return response()->json(['state' => 'not_configured']);
        }

        try {
            $response = Http::timeout(5)
                ->withHeaders(['apikey' => $cfg['key']])
                ->get("{$cfg['url']}/instance/connectionState/{$cfg['instance']}");

            if ($response->successful()) {
                $body  = $response->json();
                $state = $body['instance']['state']
                      ?? $body['state']
                      ?? 'unknown';

                return response()->json(['state' => $state]);
            }

            return response()->json(['state' => 'error', 'code' => $response->status()]);
        } catch (\Throwable $e) {
            Log::warning('WhatsApp status check failed', ['error' => $e->getMessage()]);
            return response()->json(['state' => 'offline']);
        }
    }

    /**
     * Retorna o QR code base64 para conexão da instância (AJAX).
     */
    public function qrcode(): JsonResponse
    {
        $cfg = config('whatsapp.evolution');

        if (empty($cfg['url']) || empty($cfg['key'])) {
            return response()->json(['error' => 'Não configurado'], 422);
        }

        try {
            // Tenta o endpoint v2 primeiro; fallback para v1
            $response = Http::timeout(10)
                ->withHeaders(['apikey' => $cfg['key']])
                ->get("{$cfg['url']}/instance/connect/{$cfg['instance']}");

            if (! $response->successful()) {
                return response()->json(['error' => 'Falha ao obter QR code'], 422);
            }

            $body = $response->json();
            $qr   = $body['base64'] ?? $body['qrcode']['base64'] ?? null;

            if (! $qr) {
                return response()->json(['error' => 'QR code não disponível', 'raw' => $body], 422);
            }

            return response()->json(['qr' => $qr]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Alterna o estado do bot automático (enable/disable) via .env.
     */
    public function botToggle(Request $request): JsonResponse
    {
        $enabled = (bool) $request->input('enabled', true);
        $this->updateEnv(['WHATSAPP_BOT_ENABLED' => $enabled ? 'true' : 'false']);

        return response()->json(['enabled' => $enabled]);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /** Atualiza variáveis no arquivo .env do projeto. */
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
    }
}
