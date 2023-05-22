<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserDetailResource;

class UserDetailController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function getCurrentUserDetail()
    {
        $userDetails = auth()->user()->details;
        if ($userDetails) {
            return new UserDetailResource(auth()->user()->details);
        }
        return response()->noContent();
    }
}
