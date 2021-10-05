<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MerkRequest;
use App\Http\Requests\MerkUpdateRequest;
use App\Models\Merk;
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
        $merks = Merk::orderBy('id', 'asc')->paginate('10');
        return view('admin.merks.index', compact(['merks']));
    }

    /**
     * Membuat Data Merk
     *
     * @return \Illuminate\Http\Response
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(MerkRequest $request)
    {
        $merk = $request->all();

        if ($request->hasFile('image')) {
            $merk['image'] = $this->imageUpdload($request->image, $request->name);
        } else {
            $merk['image'] = 'merks/default.jpg';
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
    public function update(MerkUpdateRequest $request, Merk $merk)
    {
        $merks = $request->all();

        if ($request->image) {
            // delete image yg sudah ada
            if ($merk->image != 'merks/default.jpg') {
                Storage::delete($merk->image);
            }
            // tambahkan image baru
            $merks['image'] = $this->imageUpdload($request->image, $request->name);
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
        if ($merk->image != 'merks/default.jpg') {
            Storage::delete($merk->image);
        }
        $merk->delete();

        alert()->success('Successfully Deleted');

        return redirect(route('merk.index'));
    }

    /**
     * @param mixed $image
     * @param mixed $name
     * 
     * @return string
     */
    private function imageUpdload($image, $name)
    {
        $file = $image;
        $filename = Str::slug($name) . '.' . $file->extension();
        $file->storeAs('merks', $filename);
        $merk['image'] = 'merks/' . $filename;

        return $merk['image'];
    }
}
