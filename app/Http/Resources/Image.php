<?php

namespace App\Http\Resources;

use App\Http\Resources\Link as LinkResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Image extends JsonResource
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
            'filename' => $this->filename,
            'filesize' => $this->filesize,
            'mimetype' => $this->mimetype,
            'filepath' => asset($this->filepath),
            'link' => new LinkResource($this->link),
        ];

    }
}
