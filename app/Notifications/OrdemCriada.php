<?php

namespace App\Notifications;

use App\Models\Ordem;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrdemCriada extends Notification
{
    use Queueable;

    public function __construct(public Ordem $ordem) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'tipo'      => 'os_criada',
            'titulo'    => 'Nova OS criada',
            'mensagem'  => "OS {$this->ordem->numero} foi aberta para o cliente {$this->ordem->cliente?->nome}.",
            'url'       => route('app.os.show', $this->ordem),
            'ordem_id'  => $this->ordem->id,
            'numero'    => $this->ordem->numero,
        ];
    }
}
