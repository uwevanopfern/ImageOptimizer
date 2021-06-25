<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Share extends JsonResource
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
            'times' => $this->time,
        ];

    }
}
