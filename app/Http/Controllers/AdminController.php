<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function AdminDashboard(){
        return view('admin.index');
    }//End Method

    public function AdminLogin(){
        return view('admin.admin_login');
    }

    //A function or method to destroy the admin profile session or to logout of the admin profile page.
    public function AdminDestroy(Request $request){
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }//End Method

    //A method to view a specific admin profile page using the id concept since its a unique value among all the fields.
    public function AdminProfile(Request $request){
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.admin_profile_view', compact('adminData'));
    }//End Method


    //Updating all input fields in the admin profile form into the database
    public function AdminProfileStore(Request $request){
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        //Updating admin profile photo and aking sure the new profile photo rplaces the old one  using the laravl @unlink method.
        if($request->file('photo')){
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/'.$data->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'),$filename);
            $data['photo'] = $filename;
        }

        $data->save();

        //Adding notification in the admin profile page to display a message when ever the admin update his/her profile bu uisng the toastr function
        $notification = array(
            'message' => 'Admin Profile Updated Succesfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);

        return view('admin.admin_profile_store_view');
    }//End Method

    public function AdminChangePassword(Request $request){
        return view('admin.admin_change_password');
       
    }//End Method

    public function AdminUpdatePassword(Request $request){
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


    public function InactiveVendor(){
        $inActiveVendor = User::where('status','inactive')->where('role','vendor')->latest()->get();
        return view('backend.vendor.inactive_vendor',compact('inActiveVendor'));

    }// End Mehtod 

    public function ActiveVendor(){
        $ActiveVendor = User::where('status','active')->where('role','vendor')->latest()->get();
        return view('backend.vendor.active_vendor',compact('ActiveVendor'));

    }// End Mehtod 

}