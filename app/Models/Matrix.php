<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matrix extends Model
{
    protected $table = 'matrix';

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
