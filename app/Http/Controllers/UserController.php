<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function UserDashboard(){
        $id = Auth::user()->id;
        $userData = User::find($id);
        return view('index', compact('userData'));
    } //End Method


//Updating all input fields in the admin profile form into the database
public function userProfileStore(Request $request){
    $id = Auth::user()->id;
    $data = User::find($id);
    $data->name = $request->name;
    $data->username = $request->username;
    $data->email = $request->email;
    $data->phone = $request->phone;
    $data->address = $request->address;

    //Updating admin profile photo and aking sure the new profile photo rplaces the old one  using the laravl @unlink method.
    if($request->file('photo')){
        $file = $request->file('photo');
        @unlink(public_path('upload/user_images/'.$data->photo));
        $filename = date('YmdHi').$file->getClientOriginalName();
        $file->move(public_path('upload/user_images'),$filename);
        $data['photo'] = $filename;
    }

    $data->save();

    //Adding notification in the user profile page to display a message when ever the user update his/her profile bu uisng the toastr function
    $notification = array(
        'message' => 'User Profile Updated Succesfully',
        'alert-type' => 'success'
    );
    return redirect()->back()->with($notification);

    return view('user.user_profile_store_view');
}//End Method

 //A function or method to destroy the user profile session or to logout of the admin profile page.
 public function UserLogout(Request $request){
    Auth::guard('web')->logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();
    //Adding notification in the user profile page to display a message when ever the logs out of his/her profile bu uisng the toastr function
    $notification = array(
        'message' => 'User Profile logout Succesfully',
        'alert-type' => 'success'
    );

    return redirect('/login');
}//End Method


public function UserUpdatePassword(Request $request){
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