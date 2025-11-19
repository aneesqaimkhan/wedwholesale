@extends('tenant.layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User: ' . $user->name)

@section('content')
<div class="card">
    <form method="POST" action="{{ route_include_subdirectory('users.update', ['subdomain' => request()->route('subdomain'), 'user' => $user->id]) }}" class="compact-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name <span style="color: red;">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email <span style="color: red;">*</span></label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password (leave blank to keep current)</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
            @error('phone')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="company">Company</label>
            <input type="text" class="form-control @error('company') is-invalid @enderror" id="company" name="company" value="{{ old('company', $user->company) }}">
            @error('company')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
            @error('address')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                Active
            </label>
        </div>

        <div class="section-title" style="margin-top: 20px;">Assign Roles</div>
        <div style="border: 1px solid #e1e5e9; padding: 10px; border-radius: 4px;">
            @if($roles->count() > 0)
                @foreach($roles as $role)
                    <label style="display: flex; align-items: center; font-size: 12px; cursor: pointer; padding: 6px; margin-bottom: 4px;">
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                            {{ $user->roles->contains($role->id) ? 'checked' : '' }} style="margin-right: 8px;">
                        <div>
                            <div style="font-weight: 500;">{{ $role->name }}</div>
                            @if($role->description)
                                <div style="font-size: 10px; color: #666; margin-top: 2px;">{{ $role->description }}</div>
                            @endif
                        </div>
                    </label>
                @endforeach
            @else
                <div style="text-align: center; padding: 20px; color: #999; font-size: 12px;">
                    No roles available. <a href="{{ route_include_subdirectory('roles.create', ['subdomain' => request()->route('subdomain')]) }}">Create a role first</a>
                </div>
            @endif
        </div>

        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <button type="submit" class="btn" style="padding: 8px 20px; font-size: 12px;">Update User</button>
            <a href="{{ route_include_subdirectory('users.index', ['subdomain' => request()->route('subdomain')]) }}" class="btn" style="padding: 8px 20px; font-size: 12px; background: #6c757d;">Cancel</a>
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



