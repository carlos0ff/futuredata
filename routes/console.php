<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Ativa o bot às 8h de seg–sex
Schedule::call(function () {
    $envPath = base_path('.env');
    $content = file_get_contents($envPath);
    $content = preg_match('/^WHATSAPP_BOT_ENABLED=/m', $content)
        ? preg_replace('/^WHATSAPP_BOT_ENABLED=.*/m', 'WHATSAPP_BOT_ENABLED=true', $content)
        : $content . "\nWHATSAPP_BOT_ENABLED=true";
    file_put_contents($envPath, $content);
    Artisan::call('config:clear');
})->weekdays()->at('08:00')->timezone('America/Sao_Paulo');

// Desativa o bot às 18h de seg–sex
Schedule::call(function () {
    $envPath = base_path('.env');
    $content = file_get_contents($envPath);
    $content = preg_match('/^WHATSAPP_BOT_ENABLED=/m', $content)
        ? preg_replace('/^WHATSAPP_BOT_ENABLED=.*/m', 'WHATSAPP_BOT_ENABLED=false', $content)
        : $content . "\nWHATSAPP_BOT_ENABLED=false";
    file_put_contents($envPath, $content);
    Artisan::call('config:clear');
})->weekdays()->at('18:00')->timezone('America/Sao_Paulo');
