<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;
    public $email;

    /**
     * Create a new notification instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $resetUrl = url(env("NEXT_URL"). "/password/reset?token=". $this->token. "&email=". $notifiable->getEmailForPasswordReset());

        return (new MailMessage)
            ->subject("Reset Password Akun Anda")
            ->greeting("Halo!")
            ->line("Kami menerima permintaan untuk mereset password akun Anda.")
            ->action("Reset Password", $resetUrl)
            ->line("Link reset password ini akan kadaluarsa dalam " . config("auth.passwords." . config("auth.defaults.passwords") . ".expire") . " menit.")
            ->line("Abaikan email ini apabila Anda merasa tidak meminta reset password.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
