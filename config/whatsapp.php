<?php

return [

    /*
     | Provider: 'evolution' (padrão, open-source, gratuito)
     | Outras opções futuras: 'zapi', 'twilio'
     */
    'provider' => env('WHATSAPP_PROVIDER', 'evolution'),

    'evolution' => [
        'url'      => env('WHATSAPP_EVOLUTION_URL'),        // Ex: https://evolution.seudominio.com.br
        'key'      => env('WHATSAPP_EVOLUTION_KEY'),        // apikey da instância
        'instance' => env('WHATSAPP_EVOLUTION_INSTANCE', 'futuredata'),
    ],

    /*
     | Token secreto para validar webhooks recebidos.
     | Configure o mesmo valor no painel do Evolution API.
     */
    'webhook_secret' => env('WHATSAPP_WEBHOOK_SECRET'),

];
