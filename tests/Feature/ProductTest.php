<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Requests\ProductRequest;
use App\Models\{Category, Merk, Product, Specification};
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /** @test */
    public function index_page_can_rendered()
    {
        $this->loginAsAdmin();

        $this->get(route('product.index'))->assertStatus(200);
    }

    /** @test */
    public function user_admin_can_see_product_in_product_index_page()
    {
        $this->withExceptionHandling();
        $this->loginAsAdmin();

        $merk = Merk::factory()->count(2)->create();
        $category = Category::factory()->count(2)->create();
        $product = Product::create([
            'name' => 'Test Search',
            'slug' => 'test-search',
            'price' => 300,
            'category_id' => $category[0]->id,
            'merk_id' => $merk[0]->id,
            'weight' => 320,
            'status' => 1,
            'desc' => "['desc1', 'desc2']",
            'fulldesc' => 'Lorem ipsum dolor sit amet'
        ]);

        $product1 = Product::create([
            'name' => 'Test Search 1',
            'slug' => 'test-search-1',
            'price' => 301,
            'category_id' => $category[1]->id,
            'merk_id' => $merk[1]->id,
            'weight' => 320,
            'status' => 1,
            'desc' => "['desc1', 'desc2']",
            'fulldesc' => 'Lorem ipsum dolor sit amet'
        ]);

        $this->assertDatabaseHas('products', ['name' => $product->name]);
        $this->assertDatabaseHas('products', ['name' => $product1->name]);

        $response = $this->get(route('product.index'));

        $response->assertSee(['name' => $product->name]);
        $response->assertSee(['name' => $product1->name]);
        $response->assertSee(['name' => $merk[0]->name]);
        $response->assertSee(['name' => $category[0]->name]);
        $response->assertSee(['name' => $category[1]->name]);

        $response->assertOk();
    }

    /** @test */
    public function user_admin_can_search_product_by_keyword()
    {
        $this->loginAsAdmin();

        $merk = Merk::factory()->create();
        $category = Category::factory()->create();
        $product = Product::create([
            'name' => 'Test Search',
            'slug' => 'test-search',
            'price' => 300,
            'category_id' => $category->id,
            'merk_id' => $merk->id,
            'weight' => 320,
            'status' => 1,
            'desc' => "['desc1', 'desc2']",
            'fulldesc' => 'Lorem ipsum dolor sit amet'
        ]);

        $this->assertDatabaseHas('products', ['name' => $product->name]);

        $response = $this->get(route('product.index'), ['keyword' => 'test']);

        $response->assertSee(['name' => $product->name]);
        $response->assertOk();
    }

    /** @test */
    public function can_type_do_the_right_job()
    {
        // category_id =1
        $response = $this->get(route('type-catgories', ['category_id' => 1]));

        $response->assertOk();
        $response->assertSee([
            'name' => 'Cherry',
            'name' => 'Gateron',
            'name' => 'Tiny (40%)',
            'name' => 'Compact (60%)'
        ]);

        // category_id = 2
        $response_2 = $this->get(route('type-catgories', ['category_id' => 2]));

        $response_2->assertOk();
        $response_2->assertSee([
            'name' => 'Add-On',
            'name' => 'Artisan'
        ]);

        // category_id = 3
        $response_3 = $this->get(route('type-catgories', ['category_id' => 3]));

        $response_3->assertOk();
        $response_3->assertSee([
            'name' => 'Clicky',
            'name' => 'Linear'
        ]);
    }

    /** @test */
    public function create_page_can_rendered()
    {
        $this->loginAsAdmin();

        $this->get(route('product.create'))->assertOk();
    }

    /** @test */
    public function product_request_uses_the_correct_form_request()
    {
        $this->assertActionUsesFormRequest(ProductController::class, 'store', ProductRequest::class);
    }

    /** @test */
    public function product_request_has_the_correct_rules()
    {
        $this->assertValidationRules([
            'name' => ['required', 'string', 'max:100'],
            'price' => ['integer', 'required'],
            'weight' => ['numeric', 'required'],
            'category_id' => 'required|exists:categories,id',
            'fulldesc' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg',
            'images.*' => 'image|mimes:png,jpg,jpeg'
        ], (new ProductRequest())->rules());
    }

    /** @test */
    public function user_admin_can_create_product()
    {
        $this->withExceptionHandling();
        $this->loginAsAdmin();

        $response = $this->createProduct();

        $product = Product::whereName('Product Dummy')->first();

        $this->assertEquals('Product Dummy', $product->name);

        // assert stock product
        $this->assertDatabaseHas('product_stocks', [
            'product_id' => $product->id
        ]);

        // assert specifications relationship
        foreach ($product->specifications as $spec) {
            $this->assertDatabaseHas('product_specification', [
                'product_id' => $product->id,
                'specification_id' => $spec->id
            ]);
        }

        // assert image
        Storage::disk('public')->assertExists($product->image);

        // assert images
        foreach ($product->images as $image) {
            Storage::disk('public')->assertExists($image->name);
        }

        $response->assertStatus(302);
        $response->assertRedirect(route('product.index'));
    }

    /** @test */
    public function user_admin_can_create_product_with_error_expected()
    {
        $this->withExceptionHandling();
        $this->loginAsAdmin();

        $merk = Merk::factory()->create();
        $category = Category::first();

        $values = ['spec1', 'spec2', 'spec3', 'spec4'];

        Storage::fake('public');
        $file = File::create('image.jpg', 100);

        $response = $this->from(route('product.create'))->post(route('product.store'), [
            'name' => 'Product Dummy',
            'desc' => "['desc1', 'desc2','desc3']",
            'value' => $values,
            'fulldesc' => 'Lorem ipsum dolor sit amet',
            'status' => 1,
            'category_id' => $category->id,
            'type' => 'Gateron',
            'size' => 'Tiny (40%)',
            'merk_id' => $merk->id,
            'price' => 300,
            'weight' => 320,
            'image' => $file,
            'images' => [$file, $file]
        ]);

        $this->assertDatabaseMissing('products', [
            'name' => 'Product Dummy',
            'type' => 'Gateron'
        ]);

        // assert image
        Storage::disk('public')->assertMissing('products/product-dummy.jpg');

        $response->assertRedirect(route('product.create'));
    }

    /** @test */
    public function edit_page_can_rendered()
    {
        $this->loginAsAdmin();

        $merk = Merk::factory()->create();
        $category = Category::factory()->create();

        $product = Product::create([
            'name' => 'Product Edit Test',
            'slug' => 'product-edit-test',
            'price' => 300,
            'category_id' => $category->id,
            'merk_id' => $merk->id,
            'weight' => 320,
            'status' => 1,
            'desc' => "['desc1', 'desc2']",
            'fulldesc' => 'Lorem ipsum dolor sit amet'
        ]);

        $response = $this->get(route('product.edit', $product->id));

        $response->assertSee(['name' => $product->name]);
        $response->assertOk();
    }

    /** @test */
    public function product_update_request_uses_the_correct_form_request()
    {
        $this->assertActionUsesFormRequest(ProductController::class, 'update', ProductRequest::class);
    }

    /** @test */
    public function product_update_request_has_the_correct_rules()
    {
        $this->assertValidationRules([
            'name' => ['required', 'string', 'max:100'],
            'price' => ['integer', 'required'],
            'weight' => ['numeric', 'required'],
            'category_id' => 'required|exists:categories,id',
            'fulldesc' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg',
            'images.*' => 'image|mimes:png,jpg,jpeg'
        ], (new ProductRequest())->rules());
    }

    /** @test */
    public function user_admin_can_update_product()
    {
        $this->withExceptionHandling();
        $this->loginAsAdmin();

        Storage::fake('public');
        $merk = Merk::factory()->create();
        $category = Category::first();
        $category2 = Category::whereId(2)->first();

        $specifications = Specification::factory()->count(4)->create();
        $specifications2 = Specification::factory()->count(3)->create();

        $spec_value = ['spec1', 'spec2', 'spec3', 'spec4'];
        $spec_value2 = ['spec5', 'spec6', 'spec7'];

        $specs = (array) [];
        foreach ($specifications as $value) {
            $specs[] = $value->id;
        }
        $specs2 = (array) [];
        foreach ($specifications2 as $value) {
            $specs2[] = $value->id;
        }

        $arr = array_combine($specs, $spec_value);
        $arr2 = array_combine($specs2, $spec_value2);

        $file = File::create('image.jpg', 100);

        $this->post(route('product.store'), [
            'name' => 'Product Dummy',
            'desc' => "['desc1', 'desc2','desc3']",
            'value' => $arr,
            'fulldesc' => 'Lorem ipsum dolor sit amet',
            'status' => 1,
            'category_id' => $category->id,
            'type' => 'Gateron',
            'size' => 'Tiny (40%)',
            'merk_id' => $merk->id,
            'price' => 300,
            'weight' => 320,
            'image' => $file,
            'images' => [$file, $file]
        ]);

        $before_product = Product::whereName('Product Dummy')->first();

        $response = $this->patch(route('product.update', $before_product->id), [
            'name' => 'Product After Update',
            'desc' => "['desc3', 'desc4','desc5']",
            'value' => $arr2,
            'fulldesc' => 'amet dolor sit ipsum lorem',
            'status' => 0,
            'category_id' => $category2->id,
            'type' => 'Artisan',
            'merk_id' => $merk->id,
            'price' => 310,
            'weight' => 350,
            'image' => $file,
            'images' => [$file, $file]
        ]);

        $after_product = Product::whereId($before_product->id)->first();

        $this->assertDatabaseMissing('products', [
            'name' => $before_product->name,
        ]);
        $this->assertDatabaseHas('products', [
            'name' => $after_product->name
        ]);

        Storage::disk('public')->assertExists($after_product->image);
        Storage::disk('public')->assertMissing($before_product->image);

        foreach ($after_product->images as $value) {
            Storage::disk('public')->assertExists($value->name);
        }

        $response->assertStatus(302);
        $response->assertRedirect(route('product.index'));
    }

    /** @test */
    public function user_admin_can_destroy_images()
    {
        $this->loginAsAdmin();

        $response = $this->createProduct();

        $product = Product::whereName('Product Dummy')->first();

        $this->assertDatabaseHas('images', [
            'product_id' => $product->id
        ]);

        foreach ($product->images as $value) {
            Storage::disk('public')->assertExists($value->name);

            $respon = $this->from(route('product.edit', $product->id))->delete(route('product.image-destroy', $value->id));
            Storage::disk('public')->assertMissing($value->name);
            $respon->assertStatus(302);
            $respon->assertRedirect(route('product.edit', $product->id));
        }

        $this->assertDatabaseMissing('images', [
            'product_id' => $product->id
        ]);
    }

    /** @test */
    public function user_admin_can_destroy_product()
    {
        $this->withExceptionHandling();
        $this->loginAsAdmin();

        $this->createProduct();

        $product = Product::whereName('Product Dummy')->first();

        $this->assertDatabaseHas('products', [
            'name' => 'Product Dummy'
        ]);

        $response = $this->delete(route('product.destroy', $product->id));

        $this->assertDatabaseMissing('products', [
            'name' => 'Product Dummy'
        ]);
        $this->assertDatabaseMissing('images', [
            'product_id' => $product->id
        ]);
        $this->assertDatabaseMissing('product_specification', [
            'product_id' => $product->id
        ]);
        $this->assertDatabaseMissing('product_stocks', [
            'product_id' => $product->id
        ]);

        Storage::disk('public')->assertMissing($product->image);

        foreach ($product->images as $value) {
            Storage::disk('public')->assertMissing($value->name);
        }

        $response->assertStatus(302);
        $response->assertRedirect(route('product.index'));
    }

    /** @test */
    public function user_admin_can_upload_xlxs_file_for_mass_upload_products()
    {
        $this->withExceptionHandling();
        $this->loginAsAdmin();

        Storage::fake('public');

        $file = File::create('file.xlxs', 500);
        $category = Category::first();

        $response = $this->from(route('product.index'))->post(route('product.bulk'), [
            'category_id' => $category->id,
            'file' => $file
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('product.index'));
    }



    private function createProduct()
    {
        $merk = Merk::factory()->create();
        $category = Category::first();
        $specifications = Specification::factory()->count(4)->create();

        $values = ['spec1', 'spec2', 'spec3', 'spec4'];
        $specs = (array) [];
        foreach ($specifications as $value) {
            $specs[] = $value->id;
        }
        $arr = array_combine($specs, $values);

        Storage::fake('public');
        $file = File::create('image.jpg', 100);

        $response = $this->post(route('product.store'), [
            'name' => 'Product Dummy',
            'desc' => "['desc1', 'desc2','desc3']",
            'value' => $arr,
            'fulldesc' => 'Lorem ipsum dolor sit amet',
            'status' => 1,
            'category_id' => $category->id,
            'type' => 'Gateron',
            'size' => 'Tiny (40%)',
            'merk_id' => $merk->id,
            'price' => 300,
            'weight' => 320,
            'image' => $file,
            'images' => [$file, $file]
        ]);

        return $response;
    }
}
