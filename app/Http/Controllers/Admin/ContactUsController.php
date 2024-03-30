<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ContactUs\{ContactUsRequest, UpdateContactUsRequest};
use App\Http\Resources\Admin\ContactUs\{ContactUsResource, ContactUsShowResource};
use App\Models\ContactUs;
use Illuminate\Support\Facades\DB;
use Throwable;

class ContactUsController extends Controller
{
    public function index()
    {
        $contact_us = ContactUs::latest()->paginate(100);
        return ContactUsResource::collection($contact_us)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $contact_us = ContactUs::where('uuid', $uuid)->firstOrFail();
        return ContactUsShowResource::make($contact_us)->additional(['status' => 200, 'message' => '']);
    }

    public function store(ContactUsRequest $request)
    {
        DB::beginTransaction();
        try {
            $contact_us = ContactUs::create($request->validated() + ['admin_id' => auth('admin')->id()]);
            DB::commit();
            return ContactUsResource::make($contact_us)->additional(['status' => 200, 'message' => 'ContactUs created successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'create failed.'], 500);
        }
    }

    public function update(UpdateContactUsRequest $request, $id)
    {
        $contact_us = ContactUs::findOrFail($id);
        DB::beginTransaction();
        try {
            $contact_us->update($request->validated());
            DB::commit();
            return ContactUsResource::make($contact_us)->additional(['status' => 200, 'message' => 'ContactUs updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'update failed.'], 500);
        }
    }

    public function destroy($id)
    {
        $contact_us = ContactUs::findOrFail($id);
        $contact_us->delete();
        return response()->json(['message' => 'ContactUs deleted successfully']);
    }
}
