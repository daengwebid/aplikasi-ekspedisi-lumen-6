<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    protected $guarded = [];
    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute()
    {
        return url('fleets/' . $this->photo);
    }
}
