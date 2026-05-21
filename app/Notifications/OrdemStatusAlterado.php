<?php

namespace App\Notifications;

use App\Models\Ordem;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrdemStatusAlterado extends Notification
{
    use Queueable;

    public function __construct(
        public Ordem $ordem,
        public string $statusAnterior,
        public string $statusNovo,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $labelAnterior = \App\Models\Ordem::STATUS[$this->statusAnterior]['label'] ?? $this->statusAnterior;
        $labelNovo     = \App\Models\Ordem::STATUS[$this->statusNovo]['label']     ?? $this->statusNovo;

        return [
            'tipo'      => 'os_status',
            'titulo'    => "OS {$this->ordem->numero} atualizada",
            'mensagem'  => "Status alterado de \"{$labelAnterior}\" para \"{$labelNovo}\".",
            'url'       => route('app.os.show', $this->ordem),
            'ordem_id'  => $this->ordem->id,
            'numero'    => $this->ordem->numero,
        ];
    }
}
