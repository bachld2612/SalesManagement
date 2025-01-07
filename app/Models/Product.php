<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $table = 'products';

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    //
}
