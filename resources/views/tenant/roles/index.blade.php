@extends('tenant.layouts.admin')

@section('title', 'Roles')
@section('page-title', 'Roles')

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Role List</h3>
        @permission('users.manage_roles')
        <a href="{{ route_include_subdirectory('roles.create', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="padding: 6px 16px; font-size: 12px;">Add New Role</a>
        @endpermission
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

    @if($roles->count() > 0)
        <table class="table" style="font-size: 12px;">
            <thead>
                <tr>
                    <th style="font-size: 11px; padding: 8px;">ID</th>
                    <th style="font-size: 11px; padding: 8px;">Name</th>
                    <th style="font-size: 11px; padding: 8px;">Slug</th>
                    <th style="font-size: 11px; padding: 8px;">Description</th>
                    <th style="font-size: 11px; padding: 8px;">Permissions</th>
                    <th style="font-size: 11px; padding: 8px;">Status</th>
                    <th style="font-size: 11px; padding: 8px;">Users</th>
                    <th style="font-size: 11px; padding: 8px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td style="padding: 8px;">{{ $role->id }}</td>
                    <td style="padding: 8px; font-weight: 500;">{{ $role->name }}</td>
                    <td style="padding: 8px;"><code style="font-size: 10px; background: #f0f0f0; padding: 2px 4px; border-radius: 3px;">{{ $role->slug }}</code></td>
                    <td style="padding: 8px;">{{ Str::limit($role->description, 50) ?: '-' }}</td>
                    <td style="padding: 8px;">
                        <span style="background: #667eea; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px;">
                            {{ $role->permissions->count() }} permissions
                        </span>
                    </td>
                    <td style="padding: 8px;">
                        @if($role->is_active)
                            <span style="color: #28a745; font-size: 11px;">● Active</span>
                        @else
                            <span style="color: #dc3545; font-size: 11px;">● Inactive</span>
                        @endif
                    </td>
                    <td style="padding: 8px;">
                        <span style="background: #6c757d; color: white; padding: 2px 6px; border-radius: 3px; font-size: 10px;">
                            {{ $role->users->count() }} users
                        </span>
                    </td>
                    <td style="padding: 8px;">
                        <a href="{{ route_include_subdirectory('roles.show', ['subdomain' => request()->route('subdomain'), 'role' => $role->id]) }}" class="btn btn-success" style="padding: 4px 8px; font-size: 11px;">View</a>
                        @permission('users.manage_roles')
                        <a href="{{ route_include_subdirectory('roles.edit', ['subdomain' => request()->route('subdomain'), 'role' => $role->id]) }}" class="btn btn-warning" style="padding: 4px 8px; font-size: 11px;">Edit</a>
                        @if($role->users->count() == 0)
                        <form method="POST" action="{{ route_include_subdirectory('roles.destroy', ['subdomain' => request()->route('subdomain'), 'role' => $role->id]) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this role?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 4px 8px; font-size: 11px;">Delete</button>
                        </form>
                        @endif
                        @endpermission
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 12px; font-size: 12px;">
            {{ $roles->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 30px; color: #666; font-size: 12px;">
            <p>No roles found. <a href="{{ route_include_subdirectory('roles.create', ['subdomain' => request()->route('subdomain')]) }}">Create your first role</a></p>
        </div>
    @endif
</div>
@endsection



