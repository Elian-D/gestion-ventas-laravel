<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $users = User::when($search, function($query, $search) {
                        return $query->where('name', 'like', "%{$search}%");
                    })
                    ->orderBy('id')
                    ->paginate(10)
                    ->withQueryString();

        return view('users.index', compact('users', 'search'));
    }

    public function create() { 

        return view('users.create');
        
    }

    public function store(Request $request) {
        // Validar ingreso del usuario
        $request->validate([
            'name' => 'required|string|unique:users,name',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        //Crear usuario
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario creado correctamente');
    }

    public function update(Request $request, User $user)
    {
        // Validar los datos
        $request->validate([
            'name' => 'required|string|unique:users,name,' . $user->id,
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'current_password' => ['nullable', 'string', 'current_password'], // valida contraseña actual si se envía
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],       // nueva contraseña opcional
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Si el usuario ingresó nueva contraseña, actualizarla
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // Actualizar el usuario
        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario actualizado correctamente');
    }

    public function destroy(User $user) {
        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Usuario eliminado correctamente');
    }

    public function editRoles(User $user) {
        $roles = Role::all();

        // Obtener rol actuales del usuario
        $userRoles = $user->roles->pluck('id')->toArray();

        return view('users.roles', compact('user', 'roles', 'userRoles'));
    }
    
    public function updateRole(Request $request, User $user){
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->roles()->sync([$request->role_id]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Rol actualizado correctamente.');
    }




}
