<x-mail::message>
{{ __('You have been invited by :user to :app.', [
    'user' => $invitation->sender->name,
    'app' => config('app.name'),
]) }}

<x-mail::button url="{{ $invitation->url() }}">{{ __('Accept Invitation') }}</x-mail::button>

{{ __('If you did not expect to receive an invitation, you may discard this email.') }}
</x-mail::message>
