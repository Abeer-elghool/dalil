<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\ContactMessage\ContactMessageResource;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index()
    {
        $contact_messages = ContactMessage::latest()->paginate(10);
        return ContactMessageResource::collection($contact_messages)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $contact_message = ContactMessage::where('uuid', $uuid)->firstOrFail();
        $contact_message->update(['read_at' => Now()]);
        return response()->json(['status' => 200, 'data' => ContactMessageResource::make($contact_message), 'message' => '']);
    }
}
