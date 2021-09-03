<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Merk;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MerkController extends Controller
{
    /**
     * Menampilkan Halaman Admin Merk
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $merk = Merk::orderBy('id', 'asc')->paginate('10');
        return view('admin.merks.index', compact(['merk']));
    }

    /**
     * Membuat Data Merk
     *
     * @return \Illuminate\Http\Response
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $merk = $request->validate([
            'name' => 'required|unique:merks',
            'image' => 'image|mimes:png,jpg,jpeg'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->image;
            $filename = Str::slug($request->name) . '.' . $file->extension();
            $file->storeAs('merks', $filename);

            $merk['image'] = 'merks/' . $filename;
        } else {
            $merk['image'] = 'default.jpg';
        }
        $merk['slug'] = Str::slug($request->name);
        Merk::create($merk);

        alert()->success('Successfully Added');

        return redirect(route('merk.index'));
    }

    /**
     * Update Spesifik Data Merk
     * 
     * @param Request $request
     * @param Merk $merk
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Merk $merk)
    {
        $merks = $request->validate([
            'name' => 'required',
            'image' => 'image|mimes:png,jpg,jpeg'
        ]);

        if ($request->image) {
            // delete image yg sudah ada
            Storage::delete($merk->image);
            // tambahkan image baru
            $file = $request->image;
            $filename = Str::slug($request->name) . '.' . $file->extension();
            $file->storeAs('merks', $filename);
            $merks['image'] = 'merks/' . $filename;
        } else {
            $merks['image'] = $merk->image;
        }
        $merk->update($merks);

        alert()->success('Successfully Updated');

        return redirect(route('merk.index'));
    }

    /**
     * Menghapus Spesifik Data Merk
     * 
     * @param Merk $merk
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Merk $merk)
    {
        Storage::delete($merk->image);
        $merk->delete();

        alert()->success('Successfully Deleted');

        return redirect(route('merk.index'));
    }
}
