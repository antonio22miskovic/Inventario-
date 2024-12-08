<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Hash;

class UserController extends Controller
{
    // app/Http/Controllers/UserController.php

    public function __construct()
    {
        $this->middleware('role:admin');
    }


    public function index()
    {
        if (request()->ajax()) {
            $users = User::with('roles')
            ->whereDoesntHave('roles', function($query) {
                $query->where('name', 'admin'); // Excluir al usuario con el rol 'admin'
            })
            ->get();
            return DataTables::of($users)
                ->addColumn('roles', function($user) {
                    return implode(', ', $user->roles->pluck('name')->toArray());
                })
                ->addColumn('action', function($user) {
                    return '<button class="btn btn-warning btn-sm" onclick="editUser(' . $user->id . ')">Editar</button>
                    <button class="btn btn-danger btn-sm" onclick="deleteUser(' . $user->id . ')">Eliminar</button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('users.index');
    }


    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index');
    }

    public function edit($userId)
    {
        $user = User::with('roles')->findOrFail($userId);
        $roles = Role::all(); // O si usas un paquete de roles como Spatie, puedes obtener los roles de esa forma
        return response()->json([
            'user' => $user,
            'roles' => $roles
        ]);
    }

    public function update(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,user',
        ]);
    
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'password' => $request->filled('password') ? bcrypt($request->input('password')) : $user->password,
        ]);
    
        return response()->json(['message' => 'Usuario actualizado con éxito']);
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['success' => 'eliminado correctamente']);
    }

    // Mostrar el formulario de cambio de contraseña
public function showChangePasswordForm()
{
    return view('change-password');
}

// Procesar el cambio de contraseña
public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|string|min:6|confirmed',
    ]);

    // Verificar si la contraseña actual es correcta
    if (!Hash::check($request->current_password, auth()->user()->password)) {
        return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta']);
    }

    // Actualizar la contraseña
    auth()->user()->update([
        'password' => bcrypt($request->new_password),
    ]);

    return redirect()->route('change-password')->with('success', 'Contraseña cambiada exitosamente.');
}

}
