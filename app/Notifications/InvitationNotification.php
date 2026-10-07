<?php

namespace App\Notifications;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\Attributes\DeleteWhenMissingModels;

#[DeleteWhenMissingModels]
class InvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Invitation $invitation) {}

    /** @return array<int, string> */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $team = $this->invitation->team;
        $sender = $this->invitation->sender;

        return (new MailMessage)
            ->subject(__("You've been invited to join :team", ['team' => $team->name]))
            ->replyTo($sender->email, $sender->name)
            ->line(__(':sender has invited you to join the :team team.', ['sender' => $sender->name, 'team' => $team->name]))
            ->action(__('Accept invitation'), $this->invitation->url())
            ->line(__('If you did not expect to receive an invitation, you may discard this email.'));
    }
}
