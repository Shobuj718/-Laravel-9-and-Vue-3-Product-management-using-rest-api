<?php

namespace App\Repository\Eloquent;

use App\Models\Product;
use App\Models\ProductImage;
use App\Repository\BaseRepository;
use App\Http\Resources\ProductResource;
use App\Repository\ProductRepositoryInterface;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;


class ProductRepository extends BaseRepository  implements ProductRepositoryInterface
{
    private $cacheKey = 'product';

    /**
     * > Get all product  from cache if not available get from database
     */
    public function getAll()
    {
        // get all product  form cache if not available get form database
        return Cache::remember($this->cacheKey, config('cache.lifetime'), function () {
            return ProductResource::collection(Product::orderBy('id', 'DESC')->get());
        });
    }

    /**
     * > Get product  by id form cache if not available get form database
     * 
     * @param id The id of the model you want to retrieve.
     */
    public function getById($id)
    {
        // get product  by id form cache if not available get form database
        return Cache::remember($this->cacheKey . '_' . $id, config('cache.lifetime'), function () use ($id) {
            return new ProductResource(Product::findOrFail($id));
        });
    }

    /**
     * It creates a product  and flushes the cache
     * 
     * @param array The data to be inserted into the database.
     * 
     * @return The product  that was created.
     */
    public function create($data)
    {
        $product = new Product;
        $product->name = $data['name'] ?? null;
        $product->price = $data['price'] ?? null;
        $product->detail = $data['detail'] ?? null;
        $product->slug = Str::slug($data['name']);
        $product->save();

        if (isset($data['category'])) {
            $product->categories()->sync($data['category']);
        }

        //need to create file helper function
        if ($data['product_image']) {
            $file = $data['product_image'];
            $name = '/product/' . uniqid() . '.' . $file->extension();
            $file->storePubliclyAs('public', $name);
            $product_image = new ProductImage;
            $product_image->name = $name;
            $product_image->product_id = $product->id;
            $product_image->save();
        }

        return new ProductResource($product);    
        
    }

    /**
     * It updates a product  by id, flushes the cache, and returns the updated product 
     * 
     * @param id The id of the product  you want to update.
     * @param array The data to be updated.
     * 
     * @return The updated product .
     */
    public function update($id, $data)
    {
        
        $product = Product::findOrFail($id);
        $product->name = $data['name'] ?? null;
        $product->price = $data['price'] ?? null;
        $product->detail = $data['detail'] ?? null;
        $product->slug = Str::slug($data['name']);
        $product->save();

        if (isset($data['category'])) {
            $product->categories()->sync($data['category']);
        }

        //need to create file helper function
        // if (isset($data['product_image'])) {
        //     // $product = ProductImage::findOrFail($id)->delete();
        //     $file = $data['product_image'];
        //     $name = '/product/' . uniqid() . '.' . $file->extension();
        //     $file->storePubliclyAs('public', $name);
        //     $product_image = new ProductImage;
        //     $product_image->name = $name;
        //     $product_image->product_id = $product->id;
        //     $product_image->save();
        // }

        return new ProductResource($product);
    }

    /**
     * It deletes a product  by id, flushes the cache, and returns the deleted product 
     * 
     * @param id The id of the product  you want to delete.
     * 
     * @return The product  is being returned.
     */
    public function delete($id)
    {
        // delete product 
        $product = Product::findOrFail($id)->delete();

        return $product;
    }

    public function restore($id)
    {
        // restore product 
        $product = Product::withTrashed()->findOrFail($id)->restore();

        return $product;
    }

    /**
     * It deletes a product  from the database
     * 
     * @param id The id of the product  you want to force delete.
     * 
     * @return The product  that was force deleted.
     */
    public function forceDelete($id)
    {
        // force delete product 
        $product = Product::onlyTrashed()->findOrFail($id)->forceDelete();

        return $product;
    }
    
}
