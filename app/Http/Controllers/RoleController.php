<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $roles = Role::when($search, function($query, $search) {
                        return $query->where('name', 'like', "%{$search}%");
                    })
                    ->orderBy('name')
                    ->paginate(10)
                    ->withQueryString();

        return view('roles.index', compact('roles', 'search'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        Role::create(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Rol creado correctamente.');
    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
        ]);

        $role->update(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Rol actualizado correctamente.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Rol eliminado correctamente.');
    }

        // Mostrar formulario de permisos
        public function editPermissions(Role $role)
        {
            $permissions = Permission::all();

            // Agrupar permisos por categoría (puedes usar prefijo o un campo group)
            $groupedPermissions = $permissions->groupBy(function($perm) {
                if(str_starts_with($perm->name, 'role')) return 'Roles';
                if(str_starts_with($perm->name, 'dashboard')) return 'Dashboard';
                if(str_starts_with($perm->name, 'user')) return 'Usuarios';
                return 'Otros';
            });

            $rolePermissions = $role->permissions->pluck('name')->toArray();

            return view('roles.permissions', compact('role', 'groupedPermissions', 'rolePermissions'));
        }


    // Guardar permisos
    public function updatePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Permisos actualizados correctamente.');
    }
}
