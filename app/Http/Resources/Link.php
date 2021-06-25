<?php

namespace App\Http\Resources;

use App\Http\Resources\ShareCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class Link extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'link' => $this->link,
            'expire_at' => $this->expire_at,
            'shares' => new ShareCollection($this->shares),
        ];
    }
}
