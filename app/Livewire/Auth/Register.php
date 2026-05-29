<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Register extends Component
{
    #[Rule('required|string|max:100')]
    public string $name = '';

    #[Rule('required|email|unique:users,email|max:150')]
    public string $email = '';

    #[Rule('required|string|min:8|confirmed')]
    public string $password = '';

    #[Rule('required')]
    public string $password_confirmation = '';

    public bool $showPassword = false;
    public bool $agreeTerms = false;

    public function register()
    {
        $this->validate();

        if (!$this->agreeTerms) {
            $this->addError('agreeTerms', 'Anda harus menyetujui Syarat & Ketentuan.');
            return;
        }

        $user = User::create([
            'name'     => trim($this->name),
            'email'    => strtolower(trim($this->email)),
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        session()->flash('success', 'Akun berhasil dibuat! Silakan verifikasi email Anda.');

        return redirect()->route('verification.notice');
    }

    public function render()
    {
        return view('livewire.auth.register');
    }
}
