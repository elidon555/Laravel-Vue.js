<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentPreviewResource extends JsonResource
{
    public static $wrap = false;

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
            'description' => $this->getCustomProperty('isPublic') ? $this->getCustomProperty('description') : substr($this->getCustomProperty('description'),0,100),
            'url' => $this->getCustomProperty('isPublic') ? $this->getFullUrl() : false,
            'type' => str_contains($this->mime_type,'image') ? 'photo' : 'video',
            'created_at' => Carbon::parse($this->created_at)->format('M d, Y \a\t h:i A'),
        ];
    }
}
