<?php

use App\Livewire\Components\DashboardDatatable;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Title;

new #[Title('Users')] class extends DashboardDatatable
{
    public function builder()
    {
        return User::query();
    }

    public function getColumns()
    {
        return [
            column('name')
                ->label(__('Name'))
                ->sortable()
                ->content(function (User $user) {
                    return thumbnail([
                        'title' => $user?->display_name,
                        'image' => $user?->getFirstMediaUrl('avatar'),
                    ]);
                }),
            column('email')
                ->label(__('Email'))
                ->sortable()
                ->searchable()
                ->filterable()
                ->content(function (User $user) {
                    return contents([
                        a([
                            'href' => "mailto:{$user->email}",
                            'label' => $user->email,
                            'target' => '_blank',
                            'class' => 'hover:link-underline',
                        ]),
                        container([
                            'class' => 'mt-1',
                            'content' => contents([
                                $user->hasVerifiedEmail()
                                    ? container([
                                        'tag' => 'span',
                                        'class' => 'badge badge-green pill inline-flex items-center gap-0.5',
                                        'content' => contents([
                                            container([
                                                'tag' => 'i',
                                                'class' => 'icon bi-check2-circle',
                                            ]),
                                            container([
                                                'tag' => 'span',
                                                'content' => __('Verfied'),
                                            ]),
                                        ]),
                                    ])
                                    : '',
                                ! $user->hasVerifiedEmail()
                                    ? container([
                                        'tag' => 'button',
                                        'atts' => [
                                            'type' => 'button',
                                            'wire:click' => "sendEmailVerification({$user->id})",
                                        ],
                                        'class' => 'link',
                                        'content' => contents([
                                            container([
                                                'tag' => 'span',
                                                'atts' => [
                                                    'wire:loading.remove' => '',
                                                    'wire:target' => "sendEmailVerification({$user->id})",
                                                ],
                                                'content' => contents([
                                                    container([
                                                        'tag' => 'i',
                                                        'class' => 'icon bi-send',
                                                    ]),
                                                    container([
                                                        'tag' => 'span',
                                                        'content' => __('Send verification'),
                                                    ]),
                                                ]),
                                            ]),
                                            container([
                                                'tag' => 'i',
                                                'class' => 'icon fg-loader-dots-move',
                                                'atts' => [
                                                    'wire:loading' => '',
                                                    'wire:target' => "sendEmailVerification({$user->id})",
                                                ],
                                            ]),
                                        ]),
                                    ])
                                    : '',
                                ! $user->hasVerifiedEmail()
                                    ? a([
                                        'href' => $user->email_verification_url,
                                        'target' => '_blank',
                                        'label' => __('Verify now'),
                                        'icon' => 'bi-check2-circle',
                                    ])
                                    : '',
                            ]),
                        ]),
                    ]);
                }),
            column('phone')
                ->label(__('Phone'))
                ->content(fn (User $user) => a(['href' => "tel:{$user->getMeta('phone')}", 'label' => $user->getMeta('phone') ?? '', 'target' => '_blank'])),

            column('role')
                ->label(__('Role'))
                ->sortable()
                ->searchable()
                ->filterable()
                ->content(function (User $user) {
                    return $user->getRoleNames()
                        ->map(function ($role) {
                            $label = role_label($role);
                            $icon = role_icon($role);
                            $color = role_color($role);

                            return container([
                                'tag' => 'span',
                                'class' => css_classes([
                                    'badge badge-primary pill inline-flex items-center gap-0.5',
                                    "badge-{$color}" => $color,
                                ]),
                                'content' => contents([
                                    container([
                                        'tag' => 'i',
                                        'class' => css_classes([
                                            'icon',
                                            $icon,
                                        ]),
                                    ]),
                                    container([
                                        'tag' => 'span',
                                        'content' => $label,
                                    ]),
                                ]),
                            ]);
                        })->join('');
                }),
        ];
    }

    public function getActions()
    {
        return [
            ...parent::getActions(),
            ...[
                taction('resetPassword')
                    ->title(__('Reset password'))
                    ->icon('bi-key'),
                taction('login')
                    ->title(__('Login'))
                    ->icon('bi-box-arrow-in-right'),
            ],
        ];
    }

    public function edit($id)
    {
        $this->authorize('update', user($id));
        $this->redirect(route('profile', user($id)), true);
    }

    public function create()
    {
        $this->dispatch('edit', 'user');
    }

    public function sendEmailVerification(User $user)
    {
        $this->authorize('manage_users');
        try {
            $user->sendEmailVerificationNotification();
            $this->toastSuccess(__('Verification link has been sent.'));
        } catch (Exception $e) {
            $message = get_option('app.debug')
                ? __('Send failed: :error!', ['error' => $e->getMessage()])
                : __('Send failed!');
            $this->toastError($message);
        }
    }

    public function resetPassword(User $user)
    {
        $this->authorize('update', $user);
        $token = Password::broker()->createToken($user);
        // dd($token);
        $this->redirect(route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]));
    }

    public function login(User $user)
    {
        Auth::login($user, true);
    }

    public function render()
    {
        return view('dashboard.users.index.index')->layout('layouts.dashboard', [
            'title' => __('Users'),
        ]);
    }
};
