<?php

namespace App\Http\Controllers\Portal\Ordens;

use App\Http\Controllers\Controller;
use App\Models\Ordem;
use Illuminate\Http\RedirectResponse;

class OrcamentoController extends Controller
{
    public function aprovar(Ordem $ordemServico): RedirectResponse
    {
        $ordemServico->update(['status_orcamento' => 'aprovado']);

        return redirect()
            ->route('portal.token', $ordemServico->token)
            ->with('success', 'Orçamento aprovado! Nossa equipe iniciará o serviço em breve.');
    }

    public function rejeitar(Ordem $ordemServico): RedirectResponse
    {
        $ordemServico->update(['status_orcamento' => 'recusado']);

        return redirect()
            ->route('portal.token', $ordemServico->token)
            ->with('info', 'Orçamento recusado. Nossa equipe entrará em contacto para discutir outras opções.');
    }
}
