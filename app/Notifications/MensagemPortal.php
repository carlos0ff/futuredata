<?php

namespace App\Notifications;

use App\Models\Mensagem;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MensagemPortal extends Notification
{
    use Queueable;

    public function __construct(public Mensagem $mensagem) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $ordem = $this->mensagem->ordem;

        return [
            'tipo'      => 'mensagem_portal',
            'titulo'    => 'Nova mensagem no portal',
            'mensagem'  => "Cliente enviou mensagem na OS {$ordem?->numero}.",
            'url'       => route('app.os.show', $ordem),
            'ordem_id'  => $ordem?->id,
            'numero'    => $ordem?->numero,
        ];
    }
}
