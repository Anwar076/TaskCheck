<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInvitationNotification extends Notification
{
    use Queueable;

    public function __construct(
        #[\SensitiveParameter] public string $token,
        public ?string $invitedByName = null,
        public ?string $companyName = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $expireMinutes = (int) config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        return (new MailMessage)
            ->subject('Welkom bij TaskCheck — stel je wachtwoord in')
            ->view('emails.user-invitation', [
                'user' => $notifiable,
                'resetUrl' => $resetUrl,
                'invitedByName' => $this->invitedByName,
                'companyName' => $this->companyName,
                'expireMinutes' => $expireMinutes,
            ]);
    }
}
