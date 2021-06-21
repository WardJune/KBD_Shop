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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $merk = Merk::orderBy('id', 'asc')->paginate('10');

        return view('admin.merks.index', compact(['merk']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $merk = $request->validate([
            'name' => 'required|unique:merks',
            'image' => 'required|image|mimes:png,jpg,jpeg'
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

        return redirect(route('merk.index'))->with(['success' => 'New Merk has beed added']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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

        // update database
        $merk->update($merks);

        return redirect(route('merk.index'))->with(['success' => 'Merk has been updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Merk $merk)
    {
        Storage::delete($merk->image);
        $merk->delete();
        return redirect(route('merk.index'))->with(['success' => 'The Data has been Deleted']);
    }
}
