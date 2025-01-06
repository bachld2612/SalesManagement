<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    protected $table = 'products';
    protected $fillable = ['name','description','price','amount','category','buy_price','image_link','supplier_id'];
    //

    public function favouritedBy()
    {
        return $this->belongsToMany(User::class, 'favourite_lists', 'product_id', 'user_id');
    }

    public function rates()
{
    return $this->hasMany(Rate::class);
}

}
