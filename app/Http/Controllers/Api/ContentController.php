<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\ContentResource;
use App\Http\Resources\UserResource;
use App\Models\Content;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $fileType = request('file_type', 'photo');

        $query = Content::query()
            ->where('title', 'like', "%$search%")
            ->where('type','=',$fileType)
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);

        return ContentResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'type' => 'required',
            'file' => 'required|file|max:10240', // max 10MB
            'price' => 'required|numeric|min:0',
        ]);

        $file = $request->file('file');
        $filePath = $file->store('public/content_files');

        $content = new Content();
        $content->user_id = Auth::user()->id;
        $content->title = $request->input('title');
        $content->description = $request->input('description');
        $content->type = $request->input('type');
        $content->file_path = $filePath;
        $content->price = $request->input('price');
        $content->save();

        return redirect()->route('contents.index')->with('success', 'Content created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $content = Content::findOrFail($id);
        Storage::disk('public')->delete(str_replace('/storage', '', $content->file_path));
        $content->delete();
    }


}
