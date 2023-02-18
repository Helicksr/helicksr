<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationSettingsController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'on_lick_shared' => ['nullable', 'boolean'],
            'on_added_to_group' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();

        $user->notification_settings = [
            'on_lick_shared' => $request->input(
                'on_lick_shared',
                $user->notification_settings['on_lick_shared'] ?? true,
            ),
            'on_added_to_group' => $request->input(
                'on_added_to_group',
                $user->notification_settings['on_added_to_group'] ?? true,
            ),
        ];

        $user->save();

        return;
    }
}
