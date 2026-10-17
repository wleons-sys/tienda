<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->buscar) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->buscar}%")
                  ->orWhere('email', 'LIKE', "%{$request->buscar}%");
            });
        }

        if ($request->rol) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('idRol', $request->rol);
            });
        }

        $usuarios = $query->orderBy('created_at', 'desc')->paginate(10);
        $roles = DB::table('roles')->where('activo', 1)->get();

        return view('admin.usuarios.index', compact('usuarios', 'roles'));
    }

    public function create()
    {
        $roles = DB::table('roles')->where('activo', 1)->get();
        return view('admin.usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'rol' => 'required|exists:roles,idRol',
        ], [
            'name.required' => 'El nombre es obligatorio',
            'email.required' => 'El correo es obligatorio',
            'email.unique' => 'Este correo ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'Mínimo 6 caracteres',
            'rol.required' => 'Selecciona un rol',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'activo' => 1,
            ]);

            DB::table('roles_usuarios')->insert([
                'idUsuario' => $user->id,
                'idRol' => $request->rol,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al crear el usuario: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $usuario = User::findOrFail($id);
        $rol = DB::table('roles_usuarios')
            ->join('roles', 'roles_usuarios.idRol', '=', 'roles.idRol')
            ->where('roles_usuarios.idUsuario', $id)
            ->select('roles.*')
            ->first();

        return view('admin.usuarios.show', compact('usuario', 'rol'));
    }

    public function edit($id)
    {
        $usuario = User::findOrFail($id);
        $roles = DB::table('roles')->where('activo', 1)->get();
        $rolActual = DB::table('roles_usuarios')->where('idUsuario', $id)->first();

        return view('admin.usuarios.edit', compact('usuario', 'roles', 'rolActual'));
    }

    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
            'password' => 'nullable|min:6',
            'rol' => 'required|exists:roles,idRol',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'activo' => $request->has('activo') ? 1 : 0,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $usuario->update($data);

            // Actualizar rol
            DB::table('roles_usuarios')->where('idUsuario', $id)->delete();
            DB::table('roles_usuarios')->insert([
                'idUsuario' => $id,
                'idRol' => $request->rol,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();
            return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado correctamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $usuario = User::findOrFail($id);

        // No permitir eliminar el propio usuario
        if ($id == auth()->id()) {
            return back()->withErrors(['error' => 'No puedes eliminar tu propio usuario']);
        }

        DB::table('roles_usuarios')->where('idUsuario', $id)->delete();
        $usuario->delete();

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado correctamente');
    }
}