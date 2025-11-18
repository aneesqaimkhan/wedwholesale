<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Check if user has permission to manage users
     */
    private function checkPermission()
    {
        $user = Auth::user();
        
        // Allow if user has permission or is old admin role
        if (!$user->hasPermission('users.view') && $user->role !== 'admin') {
            abort(403, 'You do not have permission to manage users.');
        }
    }

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->checkPermission();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->orderBy('created_at', 'desc')->paginate(10);
        return view('tenant.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::where('is_active', true)->orderBy('name')->get();
        return view('tenant.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'is_active' => 'boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'company' => $request->company,
            'address' => $request->address,
            'role' => 'user', // Keep for backward compatibility
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        // Assign roles
        if ($request->has('roles')) {
            $roleSlugs = Role::whereIn('id', $request->roles)->pluck('slug')->toArray();
            $user->assignRoles($roleSlugs);
        }

        return redirect(route_include_subdirectory('users.index'))
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load('roles');
        return view('tenant.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::where('is_active', true)->orderBy('name')->get();
        $user->load('roles');
        return view('tenant.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
            'is_active' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'address' => $request->address,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Update roles
        if ($request->has('roles')) {
            $roleSlugs = Role::whereIn('id', $request->roles)->pluck('slug')->toArray();
            $user->assignRoles($roleSlugs);
        } else {
            $user->roles()->detach();
        }

        return redirect(route_include_subdirectory('users.index'))
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === Auth::id()) {
            return redirect(route_include_subdirectory('users.index'))
                ->with('error', 'You cannot delete your own account.');
        }

        $user->roles()->detach();
        $user->delete();

        return redirect(route_include_subdirectory('users.index'))
            ->with('success', 'User deleted successfully');
    }
}
