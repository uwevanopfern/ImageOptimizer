<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function shares()
    {
        return $this->hasMany(Share::class);
    }
}
