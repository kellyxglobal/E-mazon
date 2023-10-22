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

    public function BecomeVendor(){
        return view('auth.become_vendor');
    } // End Mehtod 

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




     //Updating all input fields in the vendor profile form into the database
     public function VendorProfileStore(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;
        $data->vendor_join = $request->vendor_join;
        $data->vendor_short_info = $request->vendor_short_info;

        //Updating vendor profile photo and aking sure the new profile photo rplaces the old one  using the laravl @unlink method.
        if($request->file('photo')){
            $file = $request->file('photo');
            @unlink(public_path('upload/vendor_images/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/vendor_images'),$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        //Adding notification in the admin profile page to display a message when ever the admin update his/her profile bu uisng the toastr function
        $notification = array(
            'message' => 'Vendor Profile Updated Succesfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

        return view('vendor.vendor_profile_store_view');
    }//End Method

    public function VendorChangePassword(Request $request){
        return view('vendor.vendor_change_password');
       
    }//End Method

    public function VendorUpdatePassword(Request $request){
        //Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        //Check if old password matches input matches the value in the database password field
        if(!Hash::check($request->old_password, auth::user()->password)){
            return back()->with("error", "Old Password Does not Match!!");

        }

        //Updating the new Password by passing to a hash string and upating it in the password table filed
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
        return back()->with("status", " Password Changed Succesfully");
       
    }//End Method

}