<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class UserAvatarController extends Controller
{
    public function show(User $user): Response
    {
        $avatar = Cache::remember("avatar:{$user->id}", now()->addDay(), function () use ($user) {
            $fallbackUrl = "https://ui-avatars.com/api/{$user->name}/128/dbeafe/2563eb";

            $response = Http::timeout(5)->get("https://unavatar.io/{$user->email}", [
                'fallback' => $fallbackUrl,
            ]);

            if ($response->successful()) {
                return [
                    'bytes' => $response->body(),
                    'content_type' => $response->header('Content-Type', 'image/png'),
                ];
            }

            $fallbackResponse = Http::timeout(5)->get($fallbackUrl);

            return [
                'bytes' => $fallbackResponse->body(),
                'content_type' => $fallbackResponse->header('Content-Type', 'image/png'),
            ];
        });

        return response($avatar['bytes'])
            ->header('Content-Type', $avatar['content_type'])
            ->header('Cache-Control', 'public, max-age=86400');
    }
}
