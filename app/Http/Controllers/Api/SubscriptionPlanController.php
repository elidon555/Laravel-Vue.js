<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateContentRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Http\Resources\SubscriptionPlanResource;
use App\Models\SubscriptionPlan;

class SubscriptionPlanController extends Controller
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
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');

        $query = SubscriptionPlan::query()
            ->where('name', 'like', "%$search%")
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return SubscriptionPlanResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateContentRequest $request)
    {
        $data = $request->validated();
        $user = SubscriptionPlan::create($data);

        return new SubscriptionPlanResource($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePermissionRequest $request, SubscriptionPlan $SubscriptionPlan)
    {
        $data = $request->validated();

        $SubscriptionPlan->syncPermissions($data['permissions']);
        $SubscriptionPlan->update($data);

        return new SubscriptionPlanResource($SubscriptionPlan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubscriptionPlan $SubscriptionPlan)
    {
        $SubscriptionPlan->delete();

        return response()->noContent();
    }
}
