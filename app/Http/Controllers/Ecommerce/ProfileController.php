<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Models\AddressBook;
use App\Models\City;
use App\Models\Customer;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // edit user informaton
    public function show()
    {
        return view('ecommerce.user.profile');
    }

    public function userEdit()
    {
        $this->validate(request(), [
            'name' => 'required|string',
            'phone' => 'required|string',
            'gender' => 'required'
        ]);

        auth('customer')->user()->update(request()->all());

        return back()->with(['status' => 'success']);
    }

    // edit password user 
    public function userPassword()
    {
        return view('ecommerce.user.password');
    }

    public function userPasswordUpdate()
    {
        $this->validate(request(), [
            'old_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $oldPassword = Hash::check(request()->old_password, auth('customer')->user()->password);

        if ($oldPassword) {
            auth('customer')->user()->update([
                'password' => Hash::make(request()->password)
            ]);

            return back()->with(['status' => 'success']);
        }

        return back()->withErrors(['old_password' => 'Invalid Current Password']);
    }

    // address book
    public function showAddress()
    {
        $user_id = auth('customer')->user()->id;
        $addresses = AddressBook::where('customer_id', $user_id)->get();

        return view('ecommerce.user.address-book', compact('addresses'));
    }

    public function showAddressForm()
    {
        $provinces = Province::latest()->get();
        return view('ecommerce.user.address-book-form', compact('provinces'));
    }

    public function addressHandle(AddressRequest $request)
    {
        $request->all();
        $address = AddressBook::create([
            'customer_id' => auth('customer')->user()->id,
            'title' => $request->title,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'district_id' => $request->district_id
        ]);

        return redirect(route('profile.address'));
    }

    public function edit($id)
    {
        $address = AddressBook::where('id', $id)->first();
        $provinces = Province::latest()->get();
        $cities = City::where('province_id', $address->district->province->id)->get();
        $districts = District::where('province_id', $address->district->province->id)
            ->where('city_id', $address->district->city->id)
            ->get();
        return view('ecommerce.user.address-book-edit', compact('provinces', 'cities', 'districts', 'address'));
    }

    public function update($id, AddressRequest $request)
    {
        $request->all();
        $address = AddressBook::find($id);
        $address->update([
            'customer_id' => auth('customer')->user()->id,
            'title' => $request->title,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'district_id' => $request->district_id
        ]);

        return redirect(route('profile.address'))->with(['status' => 'success']);
    }

    public function destroy($id)
    {
        AddressBook::find($id)->delete();
        return back()->with(['status' => 'success']);
    }
}
