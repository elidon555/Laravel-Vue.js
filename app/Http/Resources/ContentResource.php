<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\DateTime;

class ContentResource extends JsonResource
{
    public static $wrap = false;
    private static bool $isSubbed;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (self::$isSubbed===true){
            $url = $this->getFullUrl();
            $description = $this->getCustomProperty('description');
        } else {
            $url = $this->getCustomProperty('isPublic') ? $this->getFullUrl() : false;
            $description = $this->getCustomProperty('isPublic') ? $this->getCustomProperty('description') : substr($this->getCustomProperty('description'),0,100);
        }
        return [
            'id' => $this->id,
            'title' => $this->getCustomProperty('title'),
            'description' => $description,
            'url' => $url,
            'type' => str_contains($this->mime_type,'image') ? 'photo' : 'video',
            'created_at' => Carbon::parse($this->created_at)->format('M d, Y \a\t h:i A'),
        ];
    }

    public static function customCollection($resource, $isSubbed): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        self::$isSubbed = $isSubbed;
        return parent::collection($resource);
    }
}
