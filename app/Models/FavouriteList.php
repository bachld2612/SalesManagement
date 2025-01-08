<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavouriteList extends Model
{
    protected $fillable = ['user_id', 'product_id'];
    public $timestamps = false;

    protected $table = 'favouritelists';
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lấy sản phẩm liên kết với favourite.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
