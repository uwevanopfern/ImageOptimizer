<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function link()
    {
        return $this->belongsTo(Link::class);
    }
}
