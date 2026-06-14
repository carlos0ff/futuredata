<?php

namespace App\Http\Controllers\App\Usuarios;
use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * 
 */
class UsuarioController extends Controller
{
    public function index(Request $request): View
    {
        $usuarios = User::query()
            ->when($request->filled('busca'), fn ($q) =>
                $q->where('name', 'like', "%{$request->busca}%")
                  ->orWhere('email', 'like', "%{$request->busca}%")
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('app.usuarios.index', compact('usuarios'));
    }

    public function create(): View
    {
        return view('app.usuarios.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)],
            'role'     => 'required|in:gerente,tecnico',
        ]);

        User::create([
            'name'     => $dados['name'],
            'email'    => $dados['email'],
            'password' => Hash::make($dados['password']),
            'role'     => $dados['role'],
        ]);

        return redirect()->route('app.usuarios.index')
            ->with('success', 'Usuário criado com sucesso.');
    }

    public function edit(User $usuario): View
    {
        return view('app.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario): RedirectResponse
    {
        $dados = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => "required|email|unique:users,email,{$usuario->id}",
            'password' => ['nullable', Password::min(8)],
            'role'     => 'required|in:gerente,tecnico',
        ]);

        $usuario->update([
            'name'  => $dados['name'],
            'email' => $dados['email'],
            'role'  => $dados['role'],
        ]);

        if (!empty($dados['password'])) {
            $usuario->update(['password' => Hash::make($dados['password'])]);
        }

        return redirect()->route('app.usuarios.index')
            ->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $usuario): RedirectResponse
    {
        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'Você não pode excluir sua própria conta.');
        }

        $usuario->delete();

        return redirect()->route('app.usuarios.index')
            ->with('success', 'Usuário removido com sucesso.');
    }
}
