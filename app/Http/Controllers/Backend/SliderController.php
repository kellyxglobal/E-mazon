<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\slider;
use Image;

class SliderController extends Controller
{
    //
    public function AllSlider(){
        $sliders = slider::latest()->get();
        return view('backend.slider.slider_all',compact('sliders'));
    } // End Method 
}
