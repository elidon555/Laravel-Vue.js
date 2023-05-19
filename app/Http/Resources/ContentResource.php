<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\DateTime;

class ContentResource extends JsonResource
{
    public static $wrap = false;
    private bool $isSubbed;

    public function __construct($resource, $userIdContent)
    {
        parent::__construct($resource);

        $authUser = auth()->user();
        if ($authUser && ( $authUser->subscribed($userIdContent ?? '' ) || $authUser->id===$userIdContent)){
            $isSubbed = true;
        }
        $this->isSubbed = $isSubbed ?? false;
    }

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
            'title' => $this->getCustomProperty('title'),
            'description' => $this->getCustomProperty('isPublic') || $this->isSubbed ? $this->getCustomProperty('description') : substr($this->getCustomProperty('description'),0,100),
            'url' => $this->getCustomProperty('isPublic') || $this->isSubbed ? $this->getFullUrl() : false,
            'type' => str_contains($this->mime_type,'image') ? 'photo' : 'video',
            'created_at' => Carbon::parse($this->created_at)->format('M d, Y \a\t h:i A'),
        ];
    }
}
