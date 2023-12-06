<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Image;
use Carbon\Carbon;

class BlogController extends Controller
{
    public function AllBlogCategory(){

        $blogcategories = BlogCategory::latest()->get();
        return view('backend.blog.category.blogcategory_all',compact('blogcategories'));

    } // End Method 

    public function AddBlogCategory(){
        return view('backend.blog.category.blogcategory_add');

    } // End Method 

    public function StoreBlogCategory(Request $request){

        BlogCategory::insert([
            'blog_category_name' => $request->blog_category_name,
            'blog_category_slug' => strtolower(str_replace(' ', '-',$request->blog_category_name)),
            'created_at' => Carbon::now(), 
        ]);

       $notification = array(
            'message' => 'Blog Category Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.blog.category')->with($notification); 

    }// End Method 



    public function EditBlogCategory($id){

        $blogcategories = BlogCategory::findOrFail($id);
        return view('backend.blog.category.blogcategory_edit',compact('blogcategories'));

    }// End Method 

     public function UpdateBlogCategory(Request $request){

      $blog_id = $request->id;

        BlogCategory::findOrFail($blog_id)->update([
            'blog_category_name' => $request->blog_category_name,
            'blog_category_slug' => strtolower(str_replace(' ', '-',$request->blog_category_name)), 
        ]);

       $notification = array(
            'message' => 'Blog Category Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('admin.blog.category')->with($notification); 

    }// End Method 


    public function DeleteBlogCategory($id){
        BlogCategory::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Blog Category Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification); 
    }// End Method 



}