<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\MerkController;
use App\Http\Requests\MerkRequest;
use App\Http\Requests\MerkUpdateRequest;
use App\Models\Merk;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MerkTest extends TestCase
{

    /** @test */
    public function index_page_can_rendered()
    {
        $this->loginAsAdmin();

        $response = $this->get(route('merk.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function merk_request_uses_the_correct_form_request()
    {
        $this->assertActionUsesFormRequest(MerkController::class, 'store', MerkRequest::class);
    }

    /** @test */

    public function merk_request_has_the_correct_rules()
    {
        $this->assertValidationRules([
            'name' => 'required|unique:merks,name',
            'image' => 'image|mimes:png,jpg,jpeg'
        ], (new MerkRequest())->rules());
    }

    /** @test */
    public function user_admin_can_create_merk()
    {
        $this->withExceptionHandling();
        $this->loginAsAdmin();

        Storage::fake('public');

        $file = File::create('image.jpg', 100);

        $response = $this->post(route('merk.store'), [
            'name' => 'merk test',
            'image' => $file
        ]);

        $merk = Merk::first();

        $this->assertEquals('merk test', $merk->name);
        $this->assertNotNull($merk->image);

        Storage::disk('public')->assertExists($merk->image);

        $response->assertStatus(302);
        $response->assertRedirect(route('merk.index'));
    }

    /** @test */
    public function create_merk_without_image()
    {
        $this->loginAsAdmin();

        $response = $this->post(route('merk.store'), [
            'name' => 'merk test',
        ]);

        $merk = Merk::first();

        $this->assertEquals('merk test', $merk->name);
        $this->assertNotNull($merk->image);

        Storage::disk('public')->assertExists($merk->image);

        $response->assertStatus(302);
        $response->assertRedirect(route('merk.index'));
    }

    /** @test */

    public function merk_update_request_uses_the_correct_form_request()
    {
        $this->assertActionUsesFormRequest(MerkController::class, 'update', MerkUpdateRequest::class);
    }

    /** @test */
    public function merk_update_request_has_correct_rules()
    {
        $this->assertValidationRules([
            'name' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg'
        ], (new MerkUpdateRequest())->rules());
    }


    /** @test */

    public function user_admin_can_update_merk()
    {
        $this->loginAsAdmin();

        Storage::fake('public');

        $file = File::create('before-updated.jpg', 100);
        $file2 = File::create('after-updated.jpg', 100);

        $merk = Merk::create([
            'name' => 'before updated',
            'slug' => 'before-updated',
            'image' => 'merks/before-updated.jpg'
        ]);
        $file->storeAs('merks', 'before-updated.jpg');
        Storage::disk('public')->assertExists($merk->image);

        $response = $this->patch(route('merk.update', $merk), [
            'name' => 'after updated',
            'image' => $file2
        ]);

        $merk1 = Merk::first();

        $this->assertDatabaseMissing('merks', [
            'name' => $merk->name
        ]);

        $this->assertEquals('after updated', $merk1->name);
        $this->assertEquals('merks/after-updated.jpg', $merk1->image);

        $response->assertStatus(302);
        $response->assertRedirect(route('merk.index'));
    }

    /** @test */
    public function user_admin_can_delete_merk()
    {
        $this->loginAsAdmin();

        Storage::fake('public');
        $file = File::create('merk-to-be-deleted.jpg', 100);
        $file->storeAs('merks', 'merk-to-be-deleted.jpg');

        $merk = Merk::create([
            'name' => 'merk to be deleted',
            'slug' => 'merk-to-be-deleted',
            'image' => 'merks/merk-to-be-deleted.jpg'
        ]);

        $response = $this->delete(route('merk.destroy', $merk->id));

        $this->assertDatabaseMissing('merks', [
            'id' => $merk->id
        ]);

        Storage::disk('public')->assertMissing($merk->image);

        $response->assertStatus(302);
        $response->assertRedirect(route('merk.index'));
    }

    /** @test */

    public function user_admin_can_delete_merk_with_default_image_on_merk()
    {
        $this->withExceptionHandling();
        $this->loginAsAdmin();

        Storage::fake('public');

        $merk = Merk::create([
            'name' => 'merk to be deleted with default image',
            'slug' => 'merk-to-be-deleted-with-default-image',
        ]);

        $response = $this->delete(route('merk.destroy', $merk->id));

        $this->assertDatabaseMissing('merks', [
            'id' => $merk->id
        ]);

        Storage::disk('public')->assertExists($merk->image);

        $response->assertStatus(302);
        $response->assertRedirect(route('merk.index'));
    }
}
