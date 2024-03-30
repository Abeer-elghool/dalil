<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Author\{AuthorRequest, UpdateAuthorRequest};
use App\Http\Resources\Admin\Author\{AuthorResource, AuthorShowResource};
use App\Models\Author;
use Illuminate\Support\Facades\DB;
use Throwable;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::latest()->paginate(100);
        return AuthorResource::collection($authors)->additional(['status' => 200, 'message' => '']);
    }

    public function show($uuid)
    {
        $author = Author::where('uuid', $uuid)->firstOrFail();
        return AuthorShowResource::make($author)->additional(['status' => 200, 'message' => '']);
    }

    public function store(AuthorRequest $request)
    {
        DB::beginTransaction();
        try {
            $author = Author::create($request->validated() + ['admin_id' => auth('admin')->id()]);
            DB::commit();
            return AuthorResource::make($author)->additional(['status' => 200, 'message' => 'Author created successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'create failed.'], 500);
        }
    }

    public function update(UpdateAuthorRequest $request, Author $author)
    {
        DB::beginTransaction();
        try {
            $author->update($request->validated());
            DB::commit();
            return AuthorResource::make($author)->additional(['status' => 200, 'message' => 'Author updated successfully.']);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'data' => null, 'message' => 'update failed.'], 500);
        }
    }

    public function destroy(Author $author)
    {
        $author->delete();
        return response()->json(['message' => 'Author deleted successfully']);
    }
}
