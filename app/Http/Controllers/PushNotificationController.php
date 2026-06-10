<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class PushNotificationController extends Controller
{
    public function vapidPublicKey(): JsonResponse
    {
        return response()->json([
            'publicKey' => config('push.vapid.public_key'),
        ]);
    }

    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint' => 'required|url',
            'keys.auth' => 'required|string',
            'keys.p256dh' => 'required|string',
        ]);

        PushSubscription::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'endpoint' => $request->input('endpoint'),
            ],
            [
                'public_key' => $request->input('keys.p256dh'),
                'auth_token' => $request->input('keys.auth'),
                'content_encoding' => $request->input('content_encoding', 'aes128gcm'),
                'metadata' => [
                    'user_agent' => $request->userAgent(),
                    'subscribed_at' => now()->toIso8601String(),
                ],
            ]
        );

        return response()->json(['message' => 'Subscription saved.']);
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        $request->validate([
            'endpoint' => 'required|url',
        ]);

        PushSubscription::where('user_id', Auth::id())
            ->where('endpoint', $request->input('endpoint'))
            ->delete();

        return response()->json(['message' => 'Subscription removed.']);
    }

    public function status(): JsonResponse
    {
        $subscribed = PushSubscription::where('user_id', Auth::id())->exists();

        return response()->json(['subscribed' => $subscribed]);
    }

    public function send(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'url' => 'nullable|url',
            'icon' => 'nullable|string',
            'tag' => 'nullable|string',
        ]);

        $subscriptions = PushSubscription::where('user_id', $request->input('user_id'))->get();

        if ($subscriptions->isEmpty()) {
            return response()->json(['message' => 'No subscriptions found for this user.'], 404);
        }

        $webPush = $this->createWebPush();
        $payload = json_encode([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'url' => $request->input('url', url('/')),
            'icon' => $request->input('icon', url('/apple-touch-icon.png')),
            'tag' => $request->input('tag'),
        ]);

        foreach ($subscriptions as $sub) {
            $subscription = Subscription::create([
                'endpoint' => $sub->endpoint,
                'publicKey' => $sub->public_key,
                'authToken' => $sub->auth_token,
                'contentEncoding' => $sub->content_encoding ?? 'aes128gcm',
            ]);

            $webPush->queueNotification($subscription, $payload);
        }

        $sent = 0;
        $failed = 0;
        foreach ($webPush->flush() as $report) {
            if ($report->isSuccess()) {
                $sent++;
            } else {
                $failed++;
                if ($report->isSubscriptionExpired()) {
                    PushSubscription::where('endpoint', $report->getRequest()->getUri()->__toString())->delete();
                }
            }
        }

        return response()->json([
            'message' => "Sent: {$sent}, Failed: {$failed}",
            'sent' => $sent,
            'failed' => $failed,
        ]);
    }

    public function sendToAll(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'url' => 'nullable|url',
            'icon' => 'nullable|string',
            'tag' => 'nullable|string',
        ]);

        $subscriptions = PushSubscription::all();

        if ($subscriptions->isEmpty()) {
            return response()->json(['message' => 'No subscriptions found.'], 404);
        }

        $webPush = $this->createWebPush();
        $payload = json_encode([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'url' => $request->input('url', url('/')),
            'icon' => $request->input('icon', url('/apple-touch-icon.png')),
            'tag' => $request->input('tag'),
        ]);

        foreach ($subscriptions as $sub) {
            $subscription = Subscription::create([
                'endpoint' => $sub->endpoint,
                'publicKey' => $sub->public_key,
                'authToken' => $sub->auth_token,
                'contentEncoding' => $sub->content_encoding ?? 'aes128gcm',
            ]);

            $webPush->queueNotification($subscription, $payload);
        }

        $sent = 0;
        $failed = 0;
        foreach ($webPush->flush() as $report) {
            if ($report->isSuccess()) {
                $sent++;
            } else {
                $failed++;
                if ($report->isSubscriptionExpired()) {
                    PushSubscription::where('endpoint', $report->getRequest()->getUri()->__toString())->delete();
                }
            }
        }

        return response()->json([
            'message' => "Sent: {$sent}, Failed: {$failed}",
            'sent' => $sent,
            'failed' => $failed,
        ]);
    }

    private function createWebPush(): WebPush
    {
        $auth = [
            'VAPID' => [
                'subject' => config('push.vapid.subject'),
                'publicKey' => config('push.vapid.public_key'),
                'privateKey' => config('push.vapid.private_key'),
            ],
        ];

        return new WebPush($auth);
    }
}
