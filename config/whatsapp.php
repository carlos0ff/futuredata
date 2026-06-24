<?php

return [

    /*
     | Provider: 'evolution' (padrão, open-source, gratuito)
     | Outras opções futuras: 'zapi', 'twilio'
     */
    'provider' => env('WHATSAPP_PROVIDER', 'evolution'),

    'evolution' => [
        'url'          => env('WHATSAPP_EVOLUTION_URL'),
        'key'          => env('WHATSAPP_EVOLUTION_KEY'),
        'instance'     => env('WHATSAPP_EVOLUTION_INSTANCE', 'futuredata'),
        'instance_jid' => env('WHATSAPP_INSTANCE_JID', '558194821792'),
    ],

    /*
     | Token secreto para validar webhooks recebidos.
     | Configure o mesmo valor no painel do Evolution API.
     */
    'webhook_secret' => env('WHATSAPP_WEBHOOK_SECRET'),

    /*
     | Ativa ou desativa respostas automáticas do bot.
     | Quando false, mensagens são recebidas/salvas mas o bot não responde.
     */
    'bot_enabled' => env('WHATSAPP_BOT_ENABLED', true),

];
