<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpecificationRequest;
use App\Models\Specification;

class SpecificationController extends Controller
{
    /**
     * Menampilkan halaman index specification menu
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $specs = Specification::latest()->paginate(15);
        return view('admin.specification.index', compact('specs'));
    }

    /**
     * Menyimpan data ke Spesification table di database
     * 
     * @param SpecificationRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(SpecificationRequest $request)
    {
        Specification::create($request->only(['name']));
        alert()->success('Successfully Added');
        return back();
    }

    /**
     * Memperbarui data dari spesifik specification menu
     * 
     * @param SpecificationRequest $request
     * @param Specification $specification
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(SpecificationRequest $request, Specification $spec)
    {
        $spec->update($request->only('name'));

        alert()->success('Successfully Updated');
        return back();
    }

    /**
     * Menghapus spesifik data dari specification menu
     * 
     * @param Specification $specification
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function destroy(Specification $spec)
    {
        $spec->delete();

        alert()->success('Successfully Deleted');
        return back();
    }
}
