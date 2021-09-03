<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $customers = Customer::orderBy('name', 'ASC');

        if (request()->q != '') {
            $customers = Customer::orderBy('name', 'ASC')
                ->where(function ($q) {
                    $q->where("name", "like", "%" . request()->q . "%")
                        ->orWhere("email", "like", "%" . request()->q . "%");
                });
        }

        $customers = $customers->paginate(10);
        $verified = Customer::where('email_verified_at', '!=', null)->count();
        $unverified = Customer::where('email_verified_at', '=', null)->count();
        $deleted = Customer::onlyTrashed()->count();
        return view('users.index', compact('customers', 'verified', 'unverified', 'deleted'));
    }

    /**
     * Menampilkan Halaman Create new User
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Menangani pembuatan akun Customer baru
     * 
     * @param CustomerRequest $customerRequest
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(CustomerRequest $customerRequest)
    {
        $customer = $customerRequest->all();
        $customer['password'] = Hash::make($customer['password']);
        if ($customer['email_verified_at'] == 'admin') {
            $customer['email_verified_at'] = Carbon::now();
        } else {
            $customer['email_verified_at'] = null;
        }

        Customer::create($customer);

        return redirect(route('customer.index'))->withToastSuccess('Successfully Created');
    }

    /**
     * Menampilkan Halaman Customer Edit
     * 
     * @return \Illuminate\View\View
     */
    public function edit(Customer $customer)
    {
        return view('users.edit', compact('customer'));
    }

    /**
     * Menangani Update data customer
     * 
     * @param Customer $customer
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Customer $customer)
    {
        $this->validate(request(), [
            'name' => 'required|string|min:3',
            'phone' => 'required',
            'gender' => 'required'
        ]);

        if (request()->email_verified_at == 'now') {
            $date = Carbon::now();
        } else {
            $date = request()->email_verified_at;
        }

        $customer->update([
            'name' => request()->name,
            'phone' => request()->phone,
            'gender' => request()->gender,
            'email_verified_at' => $date,
        ]);
        return back()->withToastSuccess('Successfully Updated');
    }

    /**
     * Menghapus sementara Customer
     * 
     * @param Customer $customer
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return back()->withToastSuccess('Successfully Deleted');
    }

    /**
     * Menampilkan halaman User yang sudah di delete
     * 
     * @return \Illuminate\View\View
     */
    public function deletedUser()
    {
        $customers = Customer::onlyTrashed()->orderBy('name', 'asc');

        if (request()->q != '') {
            $customers = Customer::onlyTrashed()->orderBy('name', 'ASC')
                ->where(function ($q) {
                    $q->where("name", "like", "%" . request()->q . "%")
                        ->orWhere("email", "like", "%" . request()->q . "%");
                });
        }

        $customers = $customers->paginate(10);

        return view('users.deleted', compact('customers'));
    }

    /**
     * Mengahapus spesifik user secara permanent
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function force($id)
    {
        $customer = Customer::onlyTrashed()->whereId($id)->first();

        if ($customer->addressBooks()->count() > 0) {
            $customer->addressBooks()->delete();
        }
        $customer->forceDelete();

        return back()->withToastSuccess('Successfully Deleted');
    }

    /**
     * Mengembalikan user yang telah terhapus(soft delete)
     * 
     * @param mixed $id
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $customer = Customer::onlyTrashed()->whereId($id)->first();

        $customer->restore();

        return back()->withToastSuccess('Successfully Restored');
    }

    /**
     * Menghapus semua user yang telah dihapus secara permanent
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAll()
    {
        Customer::onlyTrashed()->forceDelete();

        return redirect(route('customer.index'))->withToastSuccess('Successfully Deleted');
    }

    /**
     * Mengembalikan /restore semua user yang telah dihapus
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restoreAll()
    {
        Customer::onlyTrashed()->restore();

        return redirect(route('customer.index'))->withToastSuccess('Successfully Restored');
    }
}
