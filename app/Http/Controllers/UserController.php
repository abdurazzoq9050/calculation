<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'login' => 'required|string|unique:users',
            'password' => 'required|string|min:4',
            'phone' => 'required|string|unique:users',
            'role' => 'required|string|in:Разраб,Работник,Администратор',
        ]);

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'sometimes|string',
            'surname' => 'sometimes|string',
            'login' => 'sometimes|string|unique:users,login,' . $user->id,
            'password' => 'sometimes|string|min:4',
            'phone' => 'sometimes|string|unique:users,phone,' . $user->id,
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('surname')) {
            $user->surname = $request->surname;
        }
        if ($request->has('login')) {
            $user->login = $request->login;
        }
        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }

        $user->save();

        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {

        if ($user->role == "Разраб") {
            return response()->json(['message' => 'Cannot delete user'], 400);
        }
        
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }


    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);
    
        if (!Auth::attempt($request->only('login', 'password'))) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    
        
        $user = Auth::user();

        $user->tokens->each(function ($token) {
            $token->delete();
        });

        $token = $user->createToken('CalculationToken')->plainTextToken;
    
        return response()->json([
            'message' => 'Login successful',
            'access_token' => $token,
            'user' => $user,
            'token_type' => 'Bearer'
        ]);
    }


    public function loginv2(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required'
        ]);
    
        if (!Auth::attempt($request->only('login', 'password'))) {
            return redirect()->back()->withErrors(['login' => 'Неверный логин или пароль']);
        }
    
        $user = Auth::user();
    
        return redirect()->route('products.index');
    }
    
    public function logout(Request $request)
    {
        $user = Auth::user();
        $user->tokens->each(function ($token) {
            $token->delete();
        });
    
        Auth::logout();
    
        return redirect()->route('login');
    }

    public function employees()
    {
        $users = User::whereNotIn('role', ['Разраб','Администратор'])->orderBy('name','ASC')->get();
        $title = 'Сотрудники';

        return view('employees.index', compact('users', 'title'));
    }

    public function create()
    {
        $title = 'Создание сотрудника';
        return view('employees.create', compact('title'));
    }

    public function storev2(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'surname' => 'required|string',
            'login' => 'required|string|unique:users',
            'password' => 'required|string|min:4',
            'phone' => 'required|string|unique:users',
            'role' => 'required|string|in:Разраб,Работник,Администратор',
        ]);

        
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'login' => $request->login,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => $request->role,
        ]);
        
        return redirect()->route('employees.index')->with('success', 'Сотрудник успешно создан');
    }

    public function showv2(User $user)
    {
        // return view('employees.show', compact('user'));
    }

    public function edit(int $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('employees.index')->withErrors(['message' => 'Сотрудник не найден']);
        }

        $title = 'Редактирование сотрудника';
        return view('employees.edit', compact('user', 'title'));
    }

    public function updatev2(Request $request, int $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('employees.index')->withErrors(['message' => 'Сотрудник не найден']);
        }

        
        $validated = $request->validate([
            'name'      => 'sometimes|string',
            'surname'   => 'sometimes|string',
            'login'     => 'sometimes|string|unique:users,login,' . $user->id,
            'password'  => 'sometimes|nullable|string',
            'phone'     => 'sometimes|string',
            'role'      => 'sometimes|string|in:Разраб,Работник,Администратор',
        ]);


        if ($request->name !=null && $request->name != $user->name) {
            $user->name = $request->name;
        }
        if ($request->surname!=null && $request->surname != $user->surname) {
            $user->surname = $request->surname;
        }
        if ($request->login!=null && $request->login != $user->login) {
            $user->login = $request->login;
        }
        if ($request->password!=null && $request->password != $user->password) {
            $user->password = Hash::make($request->password);
        }
        if ($request->phone !=null && $request->phone != $user->phone) {
            $user->phone = $request->phone;
        }
        if ($request->role!=null && $request->role != $user->role) {
            $user->role = $request->role;
        }

        $user->save();

        return redirect()->route('employees.index')->with('success', 'Сотрудник успешно обновлен');
    }
    
    public function destroyv2(int $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('employees.index')->withErrors(['message' => 'Сотрудник не найден']);
        }

        if ($user->role == "Разраб") {
            return redirect()->route('employees.index')->withErrors(['message' => 'Невозможно удалить пользователя']);
        }
        
        $user->delete();
        return redirect()->route('employees.index')->with('success', 'Сотрудник успешно удален');
    }
}
