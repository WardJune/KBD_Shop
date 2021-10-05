<?php

namespace App\Http\Controllers\Ecommerce;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Models\AddressBook;
use App\Models\City;
use App\Models\District;
use App\Models\Province;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Menampilkan Halaman Profile Customer Form untuk mengubah data Profile dari Customer 
     * 
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('ecommerce.user.profile');
    }

    /**
     * Update data profile customer
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userEdit()
    {
        $this->validate(request(), [
            'name' => 'required|string',
            'phone' => 'required|string',
            'gender' => 'required'
        ]);

        auth('customer')->user()->update(request()->all());

        return back()->withToastSuccess('Profile Updated');
    }

    /**
     * Menampilkan Halaman Form untuk mengubah customer Password
     * 
     * @return \Illuminate\View\View
     */
    public function userPassword()
    {
        return view('ecommerce.user.password');
    }

    /**
     * Upadate customer Password
     * 
     * @var string $oldPassword  Pengecekan terhadap password lama Customer
     * @return \Illuminate\Http\RedirectResponse
     */
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

            return back()->withToastSuccess('Password Updated');
        }

        return back()->withErrors(['old_password' => 'Invalid Current Password']);
    }

    /**
     * Menampilkan Halaman AddressBook
     * 
     * @var string|int  $user_id
     * @var array       $addresses  Query data dari AddressBook berdasarkan customer Id
     * @return \Illuminate\View\View
     */
    public function showAddress()
    {
        $user_id = auth('customer')->user()->id;
        $addresses = AddressBook::whereCustomerId($user_id)->get();

        return view('ecommerce.user.address-book', compact('addresses'));
    }

    /**
     * Menampilkan Halaman Form untuk membuat AddressBook
     * 
     * @var array $provinces  Query data dari Province
     * @return \Illuminate\View\View
     */
    public function showAddressForm()
    {
        $provinces = Province::latest()->get();
        return view('ecommerce.user.address-book-form', compact('provinces'));
    }

    /**
     * Membuat AddressBook baru
     * 
     * @param AddressRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addressHandle(AddressRequest $request)
    {
        $validate = $request->except(['province_id', 'city_id']);
        $validate['customer_id'] = auth('customer')->user()->id;

        AddressBook::create($validate);

        return redirect(route('profile.address'))->withToastSuccess('Successfully Added');
    }

    /**
     * Menampilkan Halaman edit dari spesifik AddressBook
     * 
     * @param AddressBook $addressBook
     * @var array $province
     * @var array $cities
     * @var array $districts
     * @return \Illuminate\View\View
     */
    public function edit(AddressBook $addressBook)
    {
        $provinces = Province::latest()->get();
        $cities = City::where('province_id', $addressBook->district->province->id)->get();
        $districts = District::where('city_id', $addressBook->district->city->id)->get();

        return view('ecommerce.user.address-book-edit', compact('provinces', 'cities', 'districts', 'addressBook'));
    }

    /**
     * Update Spesifik AddressBook
     * 
     * @param AddressBook $addressBook
     * @param AddressRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(AddressBook $addressBook, AddressRequest $request)
    {
        $validate = $request->except(['province_id', 'city_id']);
        $validate['customer_id'] = auth('customer')->user()->id;

        $addressBook->update($validate);

        return redirect(route('profile.address'))->withToastSuccess('Successfully Updated');
    }

    /**
     * Delete Spesifik AddressBook
     * 
     * @param AddressBook $addressBook
     * @return \Illuminate\Http\RedirectResponse
     * 
     */
    public function destroy(AddressBook $addressBook)
    {
        $addressBook->delete();
        return back()->withToastSuccess('Successfully Deleted');
    }
}
