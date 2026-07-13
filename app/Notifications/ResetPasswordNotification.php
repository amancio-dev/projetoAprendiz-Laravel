<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends BaseResetPassword
{
    /**
     * @param string $token
     * @param string $tipo 'admin' | 'empresa' | 'aluno' — usado para montar a URL certa
     */
    public function __construct(string $token, protected string $tipo)
    {
        parent::__construct($token);
    }

    public function toMail($notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'tipo'  => $this->tipo,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Redefinição de Senha - Projeto Aprendiz')
            ->line('Você solicitou a redefinição de senha da sua conta.')
            ->action('Redefinir Senha', $url)
            ->line('Este link expira em 60 minutos.')
            ->line('Se você não solicitou isso, nenhuma ação é necessária.');
    }
}
