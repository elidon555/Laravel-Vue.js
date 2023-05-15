<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateContentRequest;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserDetailRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\ContentPreviewResource;
use App\Http\Resources\ContentResource;
use App\Http\Resources\UserResource;
use App\Models\Content;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserDetail;
use FFMpeg\FFMpeg;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserDetailController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param CreateContentRequest $request
     * @return JsonResponse
     */
    public function update(UpdateUserDetailRequest $request,UserDetail $userDetail)
    {
        $inputs = $request->validated();


        return response()->json();
    }

}
