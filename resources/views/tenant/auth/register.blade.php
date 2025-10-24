@extends('tenant.layouts.app')

@section('content')
<div class="auth-container">
    <div class="auth-header">
        <h2>Create Account</h2>
        <p>Join us today</p>
    </div>
    
    <div class="auth-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/register') }}">
            @csrf
            
            <div class="form-group">
                <label for="name">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    class="form-control @error('name') is-invalid @enderror" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus
                >
                @error('name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    value="{{ old('email') }}" 
                    required
                >
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control @error('password') is-invalid @enderror" 
                    required
                >
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    class="form-control" 
                    required
                >
            </div>

            <div class="form-group">
                <label for="phone">Phone Number (Optional)</label>
                <input 
                    type="text" 
                    id="phone" 
                    name="phone" 
                    class="form-control @error('phone') is-invalid @enderror" 
                    value="{{ old('phone') }}"
                >
                @error('phone')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="company">Company (Optional)</label>
                <input 
                    type="text" 
                    id="company" 
                    name="company" 
                    class="form-control @error('company') is-invalid @enderror" 
                    value="{{ old('company') }}"
                >
                @error('company')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="address">Address (Optional)</label>
                <textarea 
                    id="address" 
                    name="address" 
                    class="form-control @error('address') is-invalid @enderror" 
                    rows="3"
                >{{ old('address') }}</textarea>
                @error('address')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-block">
                Create Account
            </button>
        </form>

        <div class="text-center mt-3">
            <p class="text-sm text-muted">
                Already have an account? 
                <a href="{{ url('/login') }}" style="color: #667eea; text-decoration: none;">
                    Sign in here
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
