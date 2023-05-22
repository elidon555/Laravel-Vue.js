<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateContentRequest;
use App\Http\Resources\ContentResource;
use App\Models\Content;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $authUser = auth()->user();

        $perPage = request('per_page', 10);
        $userIdContent = request('id',$authUser->id ?? '');

        $user = User::findOrFail($userIdContent);

        $query = $user->media()
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);
        $additional['subscriptionPlans'] = SubscriptionPlan::query()->where('user_id',$userIdContent)->get()->toArray();
        $additional['user'] = $user->toArray();

        //check if it's owner of content or is subscribed
        if ($authUser && ($authUser->subscribed($userIdContent) || $authUser->id==$userIdContent)){
            $isSubbed = true;
        }

        return ContentResource::customCollection($query,$isSubbed ?? false)->additional($additional);

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
