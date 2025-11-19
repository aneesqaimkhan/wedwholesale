@extends('tenant.layouts.admin')

@section('title', 'View Role')
@section('page-title', 'View Role: ' . $role->name)

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 600;">Role Details</h3>
        <div>
            @permission('users.manage_roles')
            <a href="{{ route_include_subdirectory('roles.edit', ['subdomain' => request()->route('subdomain'), 'role' => $role->id]) }}" class="btn btn-warning" style="padding: 6px 16px; font-size: 12px;">Edit Role</a>
            @endpermission
            <a href="{{ route_include_subdirectory('roles.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="padding: 6px 16px; font-size: 12px; background: #6c757d;">Back to List</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div>
            <label style="font-size: 11px; color: #666; font-weight: 500;">Name</label>
            <div style="font-size: 14px; font-weight: 600; margin-top: 4px;">{{ $role->name }}</div>
        </div>
        <div>
            <label style="font-size: 11px; color: #666; font-weight: 500;">Slug</label>
            <div style="font-size: 12px; margin-top: 4px;"><code style="background: #f0f0f0; padding: 4px 8px; border-radius: 3px;">{{ $role->slug }}</code></div>
        </div>
        <div>
            <label style="font-size: 11px; color: #666; font-weight: 500;">Status</label>
            <div style="margin-top: 4px;">
                @if($role->is_active)
                    <span style="color: #28a745; font-size: 12px;">● Active</span>
                @else
                    <span style="color: #dc3545; font-size: 12px;">● Inactive</span>
                @endif
            </div>
        </div>
        <div>
            <label style="font-size: 11px; color: #666; font-weight: 500;">Users Count</label>
            <div style="font-size: 14px; margin-top: 4px;">
                <span style="background: #6c757d; color: white; padding: 4px 8px; border-radius: 3px; font-size: 11px;">
                    {{ $role->users->count() }} users
                </span>
            </div>
        </div>
    </div>

    @if($role->description)
    <div style="margin-bottom: 20px;">
        <label style="font-size: 11px; color: #666; font-weight: 500;">Description</label>
        <div style="font-size: 12px; margin-top: 4px; padding: 10px; background: #f8f9fa; border-radius: 4px;">{{ $role->description }}</div>
    </div>
    @endif

    <div style="margin-bottom: 20px;">
        <label style="font-size: 11px; color: #666; font-weight: 500; margin-bottom: 10px; display: block;">Permissions ({{ $role->permissions->count() }})</label>
        <div style="max-height: 300px; overflow-y: auto; border: 1px solid #e1e5e9; padding: 10px; border-radius: 4px;">
            @if($role->permissions->count() > 0)
                @foreach($role->permissions->groupBy('module') as $module => $modulePermissions)
                    <div style="margin-bottom: 15px;">
                        <div style="font-weight: 600; font-size: 11px; color: #667eea; margin-bottom: 8px; text-transform: uppercase;">
                            {{ $module ?: 'General' }}
                        </div>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @foreach($modulePermissions as $permission)
                                <span style="background: #667eea; color: white; padding: 4px 8px; border-radius: 3px; font-size: 10px;">
                                    {{ $permission->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div style="text-align: center; padding: 20px; color: #999; font-size: 12px;">No permissions assigned</div>
            @endif
        </div>
    </div>

    @if($role->users->count() > 0)
    <div>
        <label style="font-size: 11px; color: #666; font-weight: 500; margin-bottom: 10px; display: block;">Users with this Role ({{ $role->users->count() }})</label>
        <div style="border: 1px solid #e1e5e9; padding: 10px; border-radius: 4px;">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 8px;">
                @foreach($role->users as $user)
                    <div style="padding: 8px; background: #f8f9fa; border-radius: 4px; font-size: 12px;">
                        <div style="font-weight: 500;">{{ $user->name }}</div>
                        <div style="font-size: 10px; color: #666;">{{ $user->email }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection



