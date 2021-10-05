<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;
use Tests\TestCase;

class CategoryTest extends TestCase
{

    /** @test */

    public function index_page_can_rendered()
    {
        $this->loginAsAdmin();
        $response = $this->get(route('category.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function category_request_uses_the_correct_form_request()
    {
        $this->assertActionUsesFormRequest(CategoryController::class, 'store', CategoryRequest::class);
    }

    /** @test */
    public function category_request_has_correct_rules()
    {
        $this->assertValidationRules([
            'name' => ['required', 'string', 'max:50', 'unique:categories,name']
        ], (new CategoryRequest())->rules());
    }

    /** @test */
    public function user_admin_can_create_category()
    {
        $this->loginAsAdmin();

        $response = $this->post(route('category.store'), [
            'name' => 'new category'
        ]);
        $this->assertDatabaseHas('categories', ['name' => 'new category']);
        $response->assertStatus(302);
        $response->assertRedirect(route('category.index'));
    }

    /** @test */
    public function category_update_request_uses_the_correct_form_request()
    {
        $this->assertActionUsesFormRequest(CategoryController::class, 'update', CategoryUpdateRequest::class);
    }

    /** @test */
    public function category_update_request_has_the_correct_rules()
    {
        $this->assertValidationRules([
            'name' => ['required', 'string', 'max:50', 'unique:categories,name']
        ], (new CategoryUpdateRequest())->rules());
    }

    /** @test */

    public function user_admin_can_update_category()
    {
        $this->loginAsAdmin();

        $category = Category::create([
            'name' => 'before updated',
            'slug' => 'before-updated'
        ]);

        $response = $this->patch(route('category.update', $category->id), ['name' => 'after updated']);

        $category1 = Category::whereId($category->id)->first();

        $this->assertEquals($category1->name, 'after updated');
        $this->assertDatabaseMissing('categories', [
            'name' => 'before updated'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('category.index'));
    }

    /** @test */
    public function user_admin_can_delete_category()
    {
        $this->loginAsAdmin();

        $category = Category::create([
            'name' => 'category to be deleted',
            'slug' => 'category-to-be-deleted'
        ]);

        $response = $this->delete(route('category.destroy', $category->id));

        $this->assertDatabaseMissing('categories', ['name' => 'category to be deleted']);

        $response->assertStatus(302);
        $response->assertRedirect(route('category.index'));
    }
}
