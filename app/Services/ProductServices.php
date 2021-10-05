<?php

namespace App\Services;

use App\Models\{Category, Images, Product, ProductStock};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductServices
{
  /**
   * @param mixed $keyword
   * 
   * @return [type]
   */
  public function getProducts($keyword)
  {
    $products = Product::with(['category', 'merk']);

    if ($keyword != '') {
      $products = Product::with(['category', 'merk'])->where("name", "like", "%$keyword%");
    }

    $products = $products->latest()->paginate(10);

    return $products;
  }

  /**
   * @param mixed $validate
   * 
   * @return [type]
   */
  public function storeProduct($validate)
  {
    $product = $validate->except(['value', 'images']);
    DB::beginTransaction();

    try {
      // image
      if ($validate->hasFile('image')) {
        $file = $validate->image;
        $filename = Str::slug($validate->name) . '.' . $validate->image->extension();
        $file->storeAs('products', $filename);

        $product['image'] = 'products/' . $filename;
      }

      $product['slug'] = Str::slug($validate->name);
      $product['status'] = $validate->status;
      $product['desc'] = $validate->desc;

      $product_create = Product::create($product);

      // multiple images
      if ($validate->hasFile('images')) {
        $images = $validate->images;
        foreach ($images as $image) {
          $filename = time() . Str::random(6) . '.' . $image->extension();
          $image->storeAs('images', $filename);

          Images::create([
            'product_id' => $product_create->id,
            'name' => 'images/' . $filename
          ]);
        }
      }

      // attach specifications data
      $value = request()->value;
      $sync_data = collect($value)->map(function ($value) {
        return ['value' => $value];
      });

      if ($sync_data) {
        $product_create->specifications()->attach($sync_data);
      }

      // create stock
      ProductStock::create([
        'product_id' => $product_create->id
      ]);

      DB::commit();

      return true;
    } catch (\Throwable $th) {

      if ($validate->hasFile('image')) {
        Storage::delete($product_create->image);
      }

      if ($validate->has('images')) {
        foreach ($product_create->images as $image) {
          Storage::delete($image);
        }
      }

      DB::rollBack();

      return false;
    }
  }

  /**
   * @param mixed $validate
   * @param mixed $product
   * 
   * @return [type]
   */
  public function updateProduct($validate, $product)
  {
    $products = $validate->except(['value', 'images']);

    DB::transaction(function () use ($validate, $product, $products) {

      if ($validate->hasFile('image')) {
        Storage::delete($product->image);
        $file = $validate->image;
        $filename = Str::slug($validate->name) . '.' . $file->extension();
        $file->storeAs('products', $filename);
        $products['image'] = 'products/' . $filename;
      } else {
        $products['image'] = $product->image;
      }

      if ($validate->has('images')) {
        $images = $validate->images;
        foreach ($images as $image) {
          $filename = time() . Str::random(6) . '.' . $image->extension();
          $image->storeAs('images', $filename);

          Images::create([
            'product_id' => $product->id,
            'name' => 'images/' . $filename
          ]);
        }
      }

      // sync data relasi
      $value = request()->value;
      $sync_data = collect($value)->map(function ($value) {
        return ['value' => $value];
      });

      $product->specifications()->sync($sync_data);

      $products['merk_id'] = $validate->merk_id == 'null' ? null : $validate->merk_id;
      ($products['size'] ?? '') ? $products['size'] : $products['size'] = null;

      $product->update($products);
    });

    return true;
  }

  /**
   * @param mixed $product
   * 
   * @return [type]
   */
  public function destroyProduct($product)
  {
    DB::beginTransaction();

    try {
      $product->stock->delete();
      $product->specifications()->detach();
      if ($product->images()->count() > 0) {
        foreach ($product->images() as $images) {
          Storage::delete($images);
        }
        $product->images()->delete();
      }
      Storage::delete($product->image);
      $product->delete();

      DB::commit();

      return true;
    } catch (\Throwable $th) {
      DB::rollBack();

      return false;
    }
  }

  public function editData($category)
  {
    if ($category == 1) {
      $data['size'] = DB::table('keyboard_sizes')->get();
      $data['type'] = DB::table('key_switchs')->get();
    } elseif ($category == 2) {
      $data['type'] = DB::table('keycap_types')->get();
    } else {
      $json = [
        ['name' => 'Clicky'],
        ['name' => 'Linear'],
        ['name' => 'Tactile'],
      ];
      $data['type'] = json_decode(json_encode($json));
    }

    return $data;
  }
}
