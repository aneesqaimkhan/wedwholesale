@extends('tenant.layouts.admin')

@section('title', 'Users')
@section('page-title', 'Users')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 600;">User List</h3>
        @if(auth()->user()->hasPermission('users.create') || auth()->user()->role === 'admin')
        <a href="{{ route_include_subdirectory('users.create', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="padding: 6px 16px; font-size: 12px;">Add New User</a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="padding: 8px 12px; margin-bottom: 12px; font-size: 12px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" style="padding: 8px 12px; margin-bottom: 12px; font-size: 12px;">
            {{ session('error') }}
        </div>
    @endif

    @if($users->count() > 0)
        <table class="table" style="font-size: 12px;">
            <thead>
                <tr>
                    <th style="font-size: 11px; padding: 8px;">ID</th>
                    <th style="font-size: 11px; padding: 8px;">Name</th>
                    <th style="font-size: 11px; padding: 8px;">Email</th>
                    <th style="font-size: 11px; padding: 8px;">Phone</th>
                    <th style="font-size: 11px; padding: 8px;">Roles</th>
                    <th style="font-size: 11px; padding: 8px;">Status</th>
                    <th style="font-size: 11px; padding: 8px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td style="padding: 8px;">{{ $user->id }}</td>
                    <td style="padding: 8px; font-weight: 500;">{{ $user->name }}</td>
                    <td style="padding: 8px;">{{ $user->email }}</td>
                    <td style="padding: 8px;">{{ $user->phone ?: '-' }}</td>
                    <td style="padding: 8px;">
                        @if($user->roles->count() > 0)
                            @foreach($user->roles as $role)
                                <span style="background: #667eea; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px; margin-right: 4px; display: inline-block; margin-bottom: 2px;">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        @else
                            <span style="color: #999; font-size: 10px;">No roles</span>
                        @endif
                    </td>
                    <td style="padding: 8px;">
                        @if($user->is_active)
                            <span style="color: #28a745; font-size: 11px;">● Active</span>
                        @else
                            <span style="color: #dc3545; font-size: 11px;">● Inactive</span>
                        @endif
                    </td>
                    <td style="padding: 8px;">
                        <a href="{{ route_include_subdirectory('users.show', ['subdomain' => request()->route('subdomain'), 'user' => $user->id]) }}" class="btn btn-success" style="padding: 4px 8px; font-size: 11px;">View</a>
                        @if(auth()->user()->hasPermission('users.edit') || auth()->user()->role === 'admin')
                        <a href="{{ route_include_subdirectory('users.edit', ['subdomain' => request()->route('subdomain'), 'user' => $user->id]) }}" class="btn btn-warning" style="padding: 4px 8px; font-size: 11px;">Edit</a>
                        @endif
                        @if((auth()->user()->hasPermission('users.delete') || auth()->user()->role === 'admin') && $user->id !== auth()->id())
                        <form method="POST" action="{{ route_include_subdirectory('users.destroy', ['subdomain' => request()->route('subdomain'), 'user' => $user->id]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 4px 8px; font-size: 11px;">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 12px; font-size: 12px;">
            {{ $users->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 30px; color: #666; font-size: 12px;">
            <p>No users found. <a href="{{ route_include_subdirectory('users.create', ['subdomain' => request()->route('subdomain')]) }}">Add your first user</a></p>
        </div>
    @endif
</div>
@endsection



