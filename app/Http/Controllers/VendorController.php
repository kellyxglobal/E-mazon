<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    public function VendorDashboard(){
        return view('vendor.index');
    }
    //

    public function VendorLogin(){
        return view('vendor.vendor_login');
    }

    //A function or method to destroy the vendor profile session or to logout of the admin profile page.
    public function VendorDestroy(Request $request){
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/vendor/login');
    }//End Method

    //A method to view a specific vendor profile page using the id concept since its a unique value among all the fields.
    public function VendorProfile(Request $request){
        $id = Auth::user()->id;
        $vendorData = User::find($id);
        return view('vendor.vendor_profile_view', compact('vendorData'));
    }//End Method

}