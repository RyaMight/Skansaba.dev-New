<?php

namespace App\Livewire\Auth;

use App\Http\Requests\LoginRequest;
use App\Models\LoginAudit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.guest')]
#[Title('Login - CMS Jurusan RPL')]
class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $showPassword = false;
    public bool $isLoading = false;

    protected $rules = [
        'email' => ['required', 'string'],
        'password' => ['required', 'string', 'min:6'],
    ];

    protected $messages = [
        'email.required' => 'Email atau NISN wajib diisi',
        'password.required' => 'Password wajib diisi',
        'password.min' => 'Password minimal :min karakter',
    ];

    /**
     * Real-time validation on field update
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Toggle password visibility
     */
    public function togglePassword()
    {
        $this->showPassword = !$this->showPassword;
    }

    /**
     * Handle login attempt
     */
    public function login()
    {
        // Check rate limiting
        $rateLimitKey = 'login:' . request()->ip();
        
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            $this->addError('email', "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.");
            $this->auditFailedLogin('Rate limit exceeded');
            return;
        }

        // Trim input so copy-pasted NISN/Email with spaces doesn't fail
        $this->email = trim($this->email);

        // Validate input
        $this->validate();

        $this->isLoading = true;

        // Additional email-based rate limiting
        $failedAttempts = LoginAudit::countRecentFailedAttempts($this->email, 15);
        
        if ($failedAttempts >= 5) {
            $this->isLoading = false;
            $this->addError('email', 'Akun Anda telah diblokir sementara karena terlalu banyak percobaan login yang gagal. Silakan coba lagi nanti.');
            $this->auditFailedLogin('Account temporarily blocked');
            return;
        }

        // Determine login field type: email or nisn
        $loginType = filter_var($this->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'nisn';

        // Check if account exists and is inactive
        $existingUser = User::where($loginType, $this->email)->first();
        if ($existingUser && $existingUser->status !== 'active') {
            $this->isLoading = false;
            $this->addError('email', 'Akun Anda berstatus nonaktif. Silakan hubungi admin.');
            $this->auditFailedLogin('Account inactive');
            return;
        }

        // Attempt authentication
        if (Auth::attempt([$loginType => $this->email, 'password' => $this->password])) {
            RateLimiter::clear($rateLimitKey);
            
            // Regenerate session
            request()->session()->regenerate();

            $user = Auth::user();
            if ($user) {
                $user->updateQuietly(['last_login_at' => now()]);

                if ($user->isSiswa()) {
                    $isDefault = $this->password === User::DEFAULT_SISWA_PASSWORD;
                    session([
                        'using_default_password_' . $user->id => $isDefault,
                        'default_password_modal_dismissed_' . $user->id => false,
                    ]);
                }
            }

            // Audit successful login
            $this->auditSuccessfulLogin();

            // Redirect to dashboard
            return $this->redirect('/dashboard', navigate: true);
        }

        // Login failed
        RateLimiter::hit($rateLimitKey, 300); // 5 minutes decay

        $this->isLoading = false;
        
        // Audit failed login
        $this->auditFailedLogin('Invalid credentials');

        $this->addError('email', 'Email/NISN atau password yang Anda masukkan salah.');
        $this->password = '';
    }

    /**
     * Audit successful login
     */
    protected function auditSuccessfulLogin(): void
    {
        LoginAudit::create([
            'email' => $this->email,
            'user_id' => Auth::id(),
            'status' => 'success',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'attempted_at' => now()
        ]);
    }

    /**
     * Audit failed login
     */
    protected function auditFailedLogin(string $reason): void
    {
        LoginAudit::create([
            'email' => $this->email,
            'user_id' => null,
            'status' => 'failed',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'failure_reason' => $reason,
            'attempted_at' => now()
        ]);
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
