<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Integração com Google Gemini para respostas inteligentes no WhatsApp.
 *
 * Modelo padrão: gemini-2.5-flash-lite (leve e rápido para chatbot).
 * Configuração: GEMINI_API_KEY no .env
 */
class GeminiService
{
    private const MODEL   = 'gemini-2.5-flash-lite';
    private const API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s';

    /**
     * Envia uma mensagem para o Gemini e retorna a resposta.
     *
     * @param string $message      Mensagem do usuário
     * @param string $systemPrompt Instruções do sistema (contexto da conversa)
     * @param array  $history      Histórico [{role: user|model, text: string}]
     */
    public function chat(string $message, string $systemPrompt = '', array $history = []): ?string
    {
        $apiKey = config('services.gemini.key');

        if (! $apiKey) {
            return null;
        }

        $contents = [];

        foreach ($history as $entry) {
            $contents[] = [
                'role'  => $entry['role'],
                'parts' => [['text' => $entry['text']]],
            ];
        }

        $contents[] = [
            'role'  => 'user',
            'parts' => [['text' => $message]],
        ];

        $payload = ['contents' => $contents];

        if ($systemPrompt) {
            $payload['system_instruction'] = [
                'parts' => [['text' => $systemPrompt]],
            ];
        }

        $url = sprintf(self::API_URL, self::MODEL, $apiKey);

        // Tenta até 2 vezes para absorver 503 temporários de alta demanda
        for ($attempt = 1; $attempt <= 2; $attempt++) {
            try {
                $response = Http::timeout(15)->post($url, $payload);

                if ($response->status() === 503 && $attempt < 2) {
                    sleep(2);
                    continue;
                }

                if (! $response->successful()) {
                    Log::warning('GeminiService: erro na API', [
                        'status' => $response->status(),
                        'body'   => $response->body(),
                    ]);
                    return null;
                }

                return $response->json('candidates.0.content.parts.0.text');
            } catch (\Throwable $e) {
                Log::error('GeminiService: exceção', ['error' => $e->getMessage()]);
                return null;
            }
        }

        return null;
    }

    public function isConfigured(): bool
    {
        return ! empty(config('services.gemini.key'));
    }
}
