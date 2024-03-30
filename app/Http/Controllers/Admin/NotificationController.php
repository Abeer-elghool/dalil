<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notification\SendNotificationRequest;
use App\Http\Resources\Admin\Notification\NotificationResource;
use App\Models\Admin;
use App\Models\User;
use App\Notifications\GeneralNotification;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $admins = Admin::pluck('id');
        $notifications = DatabaseNotification::whereHasMorph('notifiable', [Admin::class], function ($q) use ($admins) {
            $q->whereIn('notifiable_id', $admins);
        })->latest()->paginate(200);
        return NotificationResource::collection($notifications)->additional(['status' => 200, 'message' => '']);
    }

    public function store(SendNotificationRequest $request)
    {
        $users = User::whereIn('id', $request->users)->get();
        \Notification::send($users, new GeneralNotification($request->safe()->only('title', 'body')));
        return response()->json(['status' => 200, 'data' => null, 'messages' => 'notification sent successfully.']);
    }

    public function destroy($id)
    {
        $admins = Admin::pluck('id');
        $notification = DatabaseNotification::whereHasMorph('notifiable', [Admin::class], function ($q) use ($admins) {
            $q->whereIn('notifiable_id', $admins);
        })->findOrFail($id);
        $notification->delete();
        return response()->json(['status' => 200, 'data' => null, 'messages' => 'notification deleted successfully.']);
    }
}
