<?php

namespace App\Models;

use App\Traits\CommonScopes;
use App\Traits\ModelObserver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, CommonScopes, ModelObserver, SoftDeletes;
    
    protected $fillable = [
        'name',
        'price',
        'detail',
    ];


    public static function clearCache($id = null)
    {
        if ($id) {
            Cache::forget('product_' . $id);
        }
        Cache::forget('product');
        Cache::forget('product_active');
        Cache::forget('product_trash');
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
