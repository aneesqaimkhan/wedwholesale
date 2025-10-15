<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\MasterUser;
use Illuminate\Support\Str;

class TenantAuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check rate limiting
        $key = 'login.' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        // Find user in master database
        $user = MasterUser::where('email', $request->email)->first();

        if (!$user || !$user->is_active) {
            RateLimiter::hit($key, 300); // 5 minutes
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials or account inactive.',
            ]);
        }

        // Check if account is locked
        if ($user->isLocked()) {
            throw ValidationException::withMessages([
                'email' => 'Account is temporarily locked due to multiple failed login attempts.',
            ]);
        }

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            $user->incrementLoginAttempts();
            RateLimiter::hit($key, 300);
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials.',
            ]);
        }

        // Reset login attempts and log successful login
        $user->resetLoginAttempts();
        RateLimiter::clear($key);

        // Configure tenant database connection
        $user->tenant->configureDatabaseConnection();

        // Login the user
        Auth::guard('web')->login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password request
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:master_users,email',
        ]);

        $user = MasterUser::where('email', $request->email)->first();

        if ($user) {
            // Generate reset token
            $token = Str::random(64);
            
            // Store token in cache (you might want to create a password_resets table)
            cache()->put("password_reset_{$token}", $user->id, now()->addMinutes(60));

            // Send email (implement your email service)
            // Mail::to($user->email)->send(new PasswordResetMail($token));

            return back()->with('status', 'Password reset link sent to your email.');
        }

        return back()->withErrors(['email' => 'Email not found.']);
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm(Request $request, $token)
    {
        $userId = cache()->get("password_reset_{$token}");
        
        if (!$userId) {
            return redirect()->route('login')->withErrors(['token' => 'Invalid or expired reset token.']);
        }

        $user = MasterUser::find($userId);
        if (!$user) {
            return redirect()->route('login')->withErrors(['token' => 'User not found.']);
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $user->email]);
    }

    /**
     * Handle reset password request
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $userId = cache()->get("password_reset_{$request->token}");
        
        if (!$userId) {
            return back()->withErrors(['token' => 'Invalid or expired reset token.']);
        }

        $user = MasterUser::find($userId);
        if (!$user || $user->email !== $request->email) {
            return back()->withErrors(['email' => 'Invalid user or email.']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
            'login_attempts' => 0,
            'locked_until' => null,
        ]);

        // Clear the token
        cache()->forget("password_reset_{$request->token}");

        return redirect()->route('login')->with('status', 'Password updated successfully. You can now login.');
    }
}
