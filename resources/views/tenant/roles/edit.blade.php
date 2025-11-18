@extends('tenant.layouts.admin')

@section('title', 'Edit Role')
@section('page-title', 'Edit Role')

@section('content')
<div class="card">
    <form method="POST" action="{{ route_include_subdirectory('roles.update', ['subdomain' => request()->route('subdomain'), 'role' => $role->id]) }}" class="compact-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Role Name <span style="color: red;">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $role->name) }}" required>
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $role->description) }}</textarea>
            @error('description')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $role->is_active) ? 'checked' : '' }}>
                Active
            </label>
        </div>

        <div class="section-title" style="margin-top: 20px;">Permissions</div>
        <div style="max-height: 400px; overflow-y: auto; border: 1px solid #e1e5e9; padding: 10px; border-radius: 4px;">
            @if($permissions->count() > 0)
                @foreach($permissions as $module => $modulePermissions)
                    <div style="margin-bottom: 15px;">
                        <div style="font-weight: 600; font-size: 12px; color: #667eea; margin-bottom: 8px; text-transform: uppercase;">
                            {{ $module ?: 'General' }}
                        </div>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 5px;">
                            @foreach($modulePermissions as $permission)
                                <label style="display: flex; align-items: center; font-size: 11px; cursor: pointer; padding: 4px;">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                        {{ $role->permissions->contains($permission->id) ? 'checked' : '' }} style="margin-right: 6px;">
                                    <span>{{ $permission->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div style="text-align: center; padding: 30px; color: #dc3545;">
                    <p style="font-size: 13px; margin-bottom: 10px;"><strong>No permissions found!</strong></p>
                    <p style="font-size: 11px; color: #666;">Please seed the permissions first by running:</p>
                    <code style="display: block; background: #f8f9fa; padding: 8px; border-radius: 4px; margin-top: 8px; font-size: 10px;">
                        php artisan tenant:seed {{ request()->route('subdomain') }} --class=RolePermissionSeeder
                    </code>
                </div>
            @endif
        </div>

        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <button type="submit" class="btn" style="padding: 8px 20px; font-size: 12px;">Update Role</button>
            <a href="{{ route_include_subdirectory('roles.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="padding: 8px 20px; font-size: 12px; background: #6c757d;">Cancel</a>
        </div>
    </form>
</div>

<style>
    .compact-form {
        font-size: 13px;
    }
    .compact-form .form-group {
        margin-bottom: 12px;
    }
    .compact-form .form-group label {
        display: block;
        font-size: 11px;
        font-weight: 500;
        color: #666;
        margin-bottom: 4px;
    }
    .compact-form .form-control {
        padding: 6px 10px;
        font-size: 12px;
        width: 100%;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .compact-form textarea.form-control {
        height: auto;
        min-height: 60px;
    }
    .compact-form .error {
        font-size: 11px;
        color: #dc3545;
        margin-top: 4px;
    }
    .section-title {
        font-size: 13px;
        font-weight: 600;
        color: #667eea;
        margin: 15px 0 10px 0;
        padding-bottom: 5px;
        border-bottom: 2px solid #667eea;
    }
</style>
@endsection

