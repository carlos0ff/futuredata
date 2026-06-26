<?php

namespace App\Http\Controllers\App\Servicos;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * CRUD de Serviços oferecidos pela assistência técnica.
 *
 * Rotas (prefixo: app/servicos):
 * - GET    /          → index()
 * - POST   /          → store()
 * - PUT    /{service} → update()
 * - DELETE /{service} → destroy()
 */
class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::where('user_id', auth()->id())->latest()->get();

        return view('app.servicos.index', [
            'services'   => $services,
            'categories' => $this->categories(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'category'   => ['required', 'string', 'max:60'],
            'base_price' => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'status'     => ['sometimes', 'string', 'in:ativo,feito,notificado_whatsapp'],
        ]);

        Service::create(array_merge($data, ['user_id' => auth()->id()]));

        return back()->with('success', 'Serviço adicionado com sucesso.');
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        abort_if($service->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'name'       => ['required', 'string', 'max:100'],
            'category'   => ['required', 'string', 'max:60'],
            'base_price' => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'status'     => ['sometimes', 'string', 'in:ativo,feito,notificado_whatsapp'],
        ]);

        $service->update($data);

        return back()->with('success', 'Serviço atualizado com sucesso.');
    }

    public function updateStatus(Request $request, Service $service): JsonResponse
    {
        abort_if($service->user_id !== auth()->id(), 403);

        $data = $request->validate([
            'status' => ['required', 'string', 'in:ativo,feito,notificado_whatsapp'],
        ]);

        $service->update($data);

        return response()->json(['ok' => true]);
    }

    public function destroy(Service $service): RedirectResponse
    {
        abort_if($service->user_id !== auth()->id(), 403);

        $service->delete();

        return back()->with('success', 'Serviço removido.');
    }

    private function categories(): array
    {
        return [
            'Diagnóstico',
            'Reparo de Hardware',
            'Reparo de Software',
            'Limpeza',
            'Instalação',
            'Manutenção Preventiva',
            'Recuperação de Dados',
            'Outros',
        ];
    }
}
