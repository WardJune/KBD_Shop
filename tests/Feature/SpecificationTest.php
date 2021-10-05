<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\SpecificationController;
use App\Http\Requests\SpecificationRequest;
use App\Models\Specification;
use Tests\TestCase;

class SpecificationTest extends TestCase
{
    /** @test */
    public function index_page_can_rendered()
    {
        $this->loginAsAdmin();
        $response = $this->get(route('spec.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function specification_request_uses_the_correct_form_request()
    {
        $this->assertActionUsesFormRequest(SpecificationController::class, 'store', SpecificationRequest::class);
    }

    /** @test */
    public function specification_request_has_the_correct_rules()
    {
        $this->assertValidationRules([
            'name' => 'required|unique:specifications,name'
        ], (new SpecificationRequest())->rules());
    }

    /** @test */
    public function user_admin_can_create_specification()
    {
        $this->withExceptionHandling();
        $this->loginAsAdmin();

        $response = $this->from(route('spec.index'))->post(route('spec.store'), [
            'name' => 'new specification'
        ]);

        $this->assertDatabaseHas('specifications', ['name' => 'new specification']);

        $response->assertStatus(302);
        $response->assertRedirect(route('spec.index'));
    }

    /** @test */
    public function specification_update_request_uses_the_correct_form_request()
    {
        $this->assertActionUsesFormRequest(SpecificationController::class, 'update', SpecificationRequest::class);
    }

    /** @test */
    public function specification_update_reques_has_the_correct_rules()
    {
        $this->assertValidationRules([
            'name' => 'required|unique:specifications,name'
        ], (new SpecificationRequest())->rules());
    }

    /** @test */
    public function user_admin_can_update_specification()
    {
        $this->loginAsAdmin();

        $specification = Specification::create([
            'name' => 'before upadated'
        ]);

        $response = $this->from(route('spec.index'))->patch(route('spec.update', $specification->id), [
            'name' => 'after updated'
        ]);

        $this->assertDatabaseHas('specifications', ['name' => 'after updated']);
        $this->assertDatabaseMissing('specifications', ['name' => $specification->name]);

        $response->assertStatus(302);
        $response->assertRedirect(route('spec.index'));
    }


    /** @test */
    public function user_admin_can_delete_specification()
    {
        $this->loginAsAdmin();

        $specification = Specification::create([
            'name' => 'specification to be deleted'
        ]);

        $response = $this->from(route('spec.index'))->delete(route('spec.destroy', $specification->id));

        $this->assertDatabaseMissing('specifications', [
            'name' => 'specification to be deleted'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('spec.index'));
    }
}


// squirrel oil cook theory again finish usage antique payment skirt live nerve