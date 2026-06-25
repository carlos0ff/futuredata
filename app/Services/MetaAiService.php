<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Integração com a API oficial do Llama da Meta para respostas no WhatsApp.
 *
 * Documentação: https://llama.developer.meta.com/docs/overview
 * Modelo padrão: Llama-4-Scout-17B-16E-Instruct
 * Configuração: META_AI_API_KEY no .env
 */
class MetaAiService
{
    private const API_URL = 'https://api.llama.com/v1/chat/completions';
    private const MODEL   = 'Llama-4-Scout-17B-16E-Instruct';

    public function chat(string $message, string $systemPrompt = '', array $history = []): ?string
    {
        $apiKey = config('services.meta_ai.key');

        if (! $apiKey) {
            return null;
        }

        $messages = [];

        if ($systemPrompt) {
            $messages[] = ['role' => 'system', 'content' => $systemPrompt];
        }

        foreach ($history as $entry) {
            $messages[] = ['role' => $entry['role'], 'content' => $entry['text']];
        }

        $messages[] = ['role' => 'user', 'content' => $message];

        try {
            $response = Http::timeout(15)
                ->withToken($apiKey)
                ->withOptions([
                    // Mantém POST em redirecionamentos (evita 405 por conversão POST→GET)
                    'allow_redirects' => ['strict' => true, 'max' => 3],
                ])
                ->post(self::API_URL, [
                    'model'       => self::MODEL,
                    'messages'    => $messages,
                    'max_tokens'  => 512,
                    'temperature' => 0.7,
                ]);

            if (! $response->successful()) {
                Log::warning('MetaAiService: erro na API', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return null;
            }

            return $response->json('choices.0.message.content');
        } catch (\Throwable $e) {
            Log::error('MetaAiService: exceção', ['error' => $e->getMessage()]);
            return null;
        }
    }

    public function isConfigured(): bool
    {
        return ! empty(config('services.meta_ai.key'));
    }
}
