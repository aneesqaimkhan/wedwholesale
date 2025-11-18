@extends('tenant.layouts.admin')

@section('title', 'View User')
@section('page-title', 'View User: ' . $user->name)

@section('content')
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; font-size: 16px; font-weight: 600;">User Details</h3>
        <div>
            @if(auth()->user()->hasPermission('users.edit') || auth()->user()->role === 'admin')
            <a href="{{ route_include_subdirectory('users.edit', ['subdomain' => request()->route('subdomain'), 'user' => $user->id]) }}" class="btn btn-warning" style="padding: 6px 16px; font-size: 12px;">Edit User</a>
            @endif
            <a href="{{ route_include_subdirectory('users.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="padding: 6px 16px; font-size: 12px; background: #6c757d;">Back to List</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div>
            <label style="font-size: 11px; color: #666; font-weight: 500;">Name</label>
            <div style="font-size: 14px; font-weight: 600; margin-top: 4px;">{{ $user->name }}</div>
        </div>
        <div>
            <label style="font-size: 11px; color: #666; font-weight: 500;">Email</label>
            <div style="font-size: 12px; margin-top: 4px;">{{ $user->email }}</div>
        </div>
        <div>
            <label style="font-size: 11px; color: #666; font-weight: 500;">Phone</label>
            <div style="font-size: 12px; margin-top: 4px;">{{ $user->phone ?: '-' }}</div>
        </div>
        <div>
            <label style="font-size: 11px; color: #666; font-weight: 500;">Company</label>
            <div style="font-size: 12px; margin-top: 4px;">{{ $user->company ?: '-' }}</div>
        </div>
        <div>
            <label style="font-size: 11px; color: #666; font-weight: 500;">Status</label>
            <div style="margin-top: 4px;">
                @if($user->is_active)
                    <span style="color: #28a745; font-size: 12px;">● Active</span>
                @else
                    <span style="color: #dc3545; font-size: 12px;">● Inactive</span>
                @endif
            </div>
        </div>
        <div>
            <label style="font-size: 11px; color: #666; font-weight: 500;">Created At</label>
            <div style="font-size: 12px; margin-top: 4px;">{{ $user->created_at->format('M d, Y') }}</div>
        </div>
    </div>

    @if($user->address)
    <div style="margin-bottom: 20px;">
        <label style="font-size: 11px; color: #666; font-weight: 500;">Address</label>
        <div style="font-size: 12px; margin-top: 4px; padding: 10px; background: #f8f9fa; border-radius: 4px;">{{ $user->address }}</div>
    </div>
    @endif

    <div style="margin-bottom: 20px;">
        <label style="font-size: 11px; color: #666; font-weight: 500; margin-bottom: 10px; display: block;">Assigned Roles ({{ $user->roles->count() }})</label>
        <div style="border: 1px solid #e1e5e9; padding: 10px; border-radius: 4px;">
            @if($user->roles->count() > 0)
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    @foreach($user->roles as $role)
                        <div style="background: #667eea; color: white; padding: 8px 12px; border-radius: 4px; font-size: 11px;">
                            <div style="font-weight: 500;">{{ $role->name }}</div>
                            @if($role->description)
                                <div style="font-size: 9px; opacity: 0.9; margin-top: 4px;">{{ $role->description }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align: center; padding: 20px; color: #999; font-size: 12px;">No roles assigned</div>
            @endif
        </div>
    </div>
</div>
@endsection

