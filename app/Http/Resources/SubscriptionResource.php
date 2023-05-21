<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Nette\Utils\DateTime;

class SubscriptionResource extends JsonResource
{
    public static $wrap = false;

    protected $parameter;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->user,
            'subscribed_user' => $this->subscribedUser,
            'plan' => $this->plan,
            'stripe_id' => $this->stripe_id,
            'price_id' => $this->stripe_price,
            'stripe_status' => $this->stripe_status,
            'created_at' => (new DateTime($this->created_at))->format('Y-m-d H:i:s'),
        ];
    }
}
