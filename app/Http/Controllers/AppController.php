<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\View\View;

class AppController extends Controller
{
    public function index(): View
    {
        $roles = Role::withCount('users') ->with(['permissions' => fn ($q) => $q->orderBy('group')->orderBy('name')])->orderBy('level')->get();

        $stats = [
            'total_roles'       => Role::count(),
            'total_permissions' => Permission::count(),
            'total_users'       => User::count(),
            'total_grupos'      => Permission::distinct('group')->count('group'),
        ];

        $permissoesPorGrupo = Permission::orderBy('group')->orderBy('name')->get()->groupBy('group');
        $ultimosUsuarios = User::with('roles')->latest()->limit(5)->get();

        return view('pages.app.index', compact(
            'roles',
            'stats',
            'permissoesPorGrupo',
            'ultimosUsuarios',
        ));
    }
}
