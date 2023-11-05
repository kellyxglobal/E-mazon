<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\MultiImg;
use App\Models\Brand;
use App\Models\Product;
use App\Models\User; 

class IndexController extends Controller
{
   public function Index(){

        $skip_category_8 = Category::skip(8)->first();
        $skip_product_8 = Product::where('status',1)->where('category_id',$skip_category_8->id)->orderBy('id','DESC')->limit(5)->get();
        $skip_category_2 = Category::skip(2)->first();
        $skip_product_2 = Product::where('status',1)->where('category_id',$skip_category_2->id)->orderBy('id','DESC')->limit(5)->get();
        $skip_category_7 = Category::skip(7)->first();
        $skip_product_7 = Product::where('status',1)->where('category_id',$skip_category_7->id)->orderBy('id','DESC')->limit(5)->get();
        return view('frontend.index',compact('skip_category_8','skip_product_8','skip_category_2','skip_product_2','skip_category_7','skip_product_7'));

   } //End Method

   
     public function ProductDetails($id,$slug){

        $product = Product::findOrFail($id);
        $color = $product->product_color;
        $product_color = explode(',', $color);

        $size = $product->product_size;
        $product_size = explode(',', $size);

        $multiImage = MultiImg::where('product_id',$id)->get();

        $cat_id = $product->category_id;
        $relatedProduct = Product::where('category_id',$cat_id)->where('id','!=',$id)->orderBy('id','DESC')->limit(4)->get();


        return view('frontend.product.product_details',compact('product','product_color','product_size','multiImage','relatedProduct'));

     } // End Method 



}