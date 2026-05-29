<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ForgotPassword extends Component
{
    #[Rule('required|email')]
    public string $email = '';

    public bool $sent = false;

    public function sendLink()
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->sent = true;
        } else {
            $this->addError('email', 'Tidak dapat mengirim link reset. Pastikan email terdaftar.');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
