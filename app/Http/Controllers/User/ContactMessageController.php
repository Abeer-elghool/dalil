<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\ContactMessage\{ContactMessageRequest, SubscribeMailRequest};
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function contact_message(ContactMessageRequest $request)
    {
        $message = new ContactMessage();

        if (auth('api')->check()) {
            $user = auth('api')->user();
            $message->name = $user->name;
            $message->email = $user->email;
            $message->user_id = $user->id;
        } else {
            $message->name = $request->name;
            $message->email = $request->email;
        }

        $message->subject = $request->subject;
        $message->message = $request->message;
        $message->save();

        send_admin_notification([
            'title'             => 'new message from ' . $message->name,
            'body'              => $message->subject,
            'notify_type'       => 'contact_message',
            'contact_message_id' => $message->id
        ]);
        return response()->json(['status' => 200, 'data' => null, 'message' => 'message send successfully.']);
    }

    function mail_subscribe(SubscribeMailRequest $request) {
        return response()->json(['status' => 200, 'data' => null, 'message' => 'Subscription Done successfully.']);
    }
}
