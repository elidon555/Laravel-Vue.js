<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateContentRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\ContentResource;
use App\Http\Resources\UserResource;
use App\Models\Content;
use App\Models\SubscriptionPlan;
use App\Models\User;
use FFMpeg\FFMpeg;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
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
        $userId = request('id');

        $user = User::findOrFail($userId);
        $query = $user->media()
//            ->where('title', 'like', "%$search%")
            ->where('media.collection_name','=','images')
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);
        $subscriptionPlans['subscriptionPlans'] = SubscriptionPlan::query()->where('user_id',$userId)->get()->toArray();
        return ContentResource::collection($query)->additional($subscriptionPlans);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateContentRequest $request
     * @return JsonResponse
     */
    public function store(CreateContentRequest $request)
    {
        $inputs = $request->validated();
        $file = $request->file('file');
        $user = auth()->user();

        if ($this->isImage($file)){
            $user->addMediaFromRequest('file')
                ->withCustomProperties($inputs['properties'])
                ->toMediaCollection('images');
        } else if ($this->isVideo($file)) {
            $user->addMediaFromRequest('file')
                ->withCustomProperties($inputs['properties'])
                ->toMediaCollection('videos');
        }

        return response()->json();
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

    protected function isImage($file)
    {
        return in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/gif']);
    }

    protected function isVideo($file)
    {
        return in_array($file->getMimeType(), ['video/mp4', 'video/quicktime', 'video/x-msvideo']);
    }


}
