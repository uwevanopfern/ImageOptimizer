<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $guarded = [];

    public function link()
    {
        return $this->hasOne(Link::class);
    }
}
