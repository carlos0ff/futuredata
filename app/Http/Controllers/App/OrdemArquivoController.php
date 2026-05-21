<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Ordem;
use App\Models\OrdemArquivo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrdemArquivoController extends Controller
{
    public function store(Request $request, Ordem $ordemServico): RedirectResponse
    {
        $request->validate([
            'arquivo'    => 'required|file|max:20480', // 20 MB
            'tipo'       => 'required|in:' . implode(',', array_keys(OrdemArquivo::TIPOS)),
            'descricao'  => 'nullable|string|max:255',
        ]);

        $file   = $request->file('arquivo');
        $path   = $file->store("ordens/{$ordemServico->id}", 'local');

        $ordemServico->arquivos()->create([
            'user_id'       => auth()->id(),
            'nome_original' => $file->getClientOriginalName(),
            'caminho'       => $path,
            'mime_type'     => $file->getMimeType(),
            'tamanho'       => $file->getSize(),
            'tipo'          => $request->tipo,
            'descricao'     => $request->descricao,
        ]);

        return back()
            ->with('success', 'Arquivo enviado com sucesso.')
            ->withFragment('tab-arquivos');
    }

    public function download(Ordem $ordemServico, OrdemArquivo $arquivo): StreamedResponse|RedirectResponse
    {
        abort_if($arquivo->ordem_id !== $ordemServico->id, 403);

        if (! Storage::disk('local')->exists($arquivo->caminho)) {
            return back()->with('error', 'Ficheiro não encontrado no armazenamento.');
        }

        return Storage::disk('local')->download($arquivo->caminho, $arquivo->nome_original);
    }

    public function destroy(Ordem $ordemServico, OrdemArquivo $arquivo): RedirectResponse
    {
        abort_if($arquivo->ordem_id !== $ordemServico->id, 403);

        $arquivo->delete();

        return back()
            ->with('success', 'Arquivo removido.')
            ->withFragment('tab-arquivos');
    }
}
