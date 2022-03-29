<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Auth\Notifications\ResetPassword;

class MailResetPasswordNotification extends Notification
{
    use Queueable;
    protected $pageUrl;
    public $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {

        $this->pageUrl = 'localhost:8000';
        $this->token = $token;
        // also set to env later
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        if(static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)
                    ->subject('Reset App Password')
                    ->line('Your are receiving this email because we recieved a password reset
                    request from your account.')
                    ->action('Reset Password',$this->pageUrl."?token=".$this->token )
                    ->line('The password reset link will expire in a couple of minutes')
                    ->line('If you did not request a password reset, no further action is required!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
