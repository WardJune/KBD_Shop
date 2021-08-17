<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store()
    {
        $spec = request()->validate(['name' => 'required|unique:specifications,name']);

        Specification::create($spec);

        return back()->with(['success' => 'New Specification menu has beed added']);
    }

    /**
     * Memperbarui data dari spesifik specification menu
     * 
     * @param Specification $specification
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Specification $spec)
    {
        $specs = request()->validate(['name' => 'required|unique:specifications,name',]);

        $spec->update($specs);

        return back()->with(['success' => 'Specification has been updated']);
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
        return back()->with(['success' => 'Specification has been deleted']);
    }
}
