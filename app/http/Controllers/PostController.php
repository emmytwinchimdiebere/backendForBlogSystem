<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }


    public function CreatePost(Request $request){

        //validates user  inputs 

        $validateInputs = validator($request->all(), [
           
            "postImage"=> ["image", "file", "max:3072"],
            "body"=>["string", "required"],
            "title"=>["string","required", "unique:posts,title", "min:10", "max:100"],
            "category_id"=>["required", "exists:categories,id"],
          

        ]);

        //check if the validation fails & return a response to the user 
        
        if($validateInputs->fails()){
            return response()->json([
                "error"=>$validateInputs->errors(),
                "status"=>401

            ]);
        }
            //retrieve all the validated inputs
        $validatedInputs = $request->input();
        $user_id = auth()->user()->id;
        //get the authenticated user id
        $post = new Post();
        

        try{
            
            if($request->hasFile("postImage")){
                $filename = uniqid(\strtolower(preg_replace('/[^a-z]+/i',"-", $validatedInputs['title'],)) . "-") . "." . $request->file("postImage")->getClientOriginalExtension();
              
                $filepath =Storage::disk("minio")->put("blogarticles", $filename);
               
              
            
            //initialize the post values from the request

            $post->slug = Str::slug(strtolower($validatedInputs["title"]), "-");
            $post->postImage = Storage::url($filepath);
            $post->title = $validatedInputs['title'];
            $post->body = $validatedInputs['body'];
            $post->user_id =$user_id;
            $post->category_id = $validatedInputs["category_id"];

              
            
            }

             // checking if the user inputs is not nulll & the user has the ability to create a post 

            if(isset($validatedInputs) && $request->user()->tokenCan("post:create")){


                //checking if the the post is initialized 
                if($post){
                    
                    //saving the post to the backend
                    $post->save();

                    //sync the post with its tags

                    $post->tags()->sync([1,2]);


                    // return a 200 response status code & a message
                    return response()->json([
                        "success"=>true,
                        "status"=>200,
                        "message"=>"your post has been saved successfully"
                    ]);

                }

            }
            



        }catch(\Exception $e){
                return response()->json([
                    "error"=>$e->getMessage(),
                    "status"=>500
                ]);
        }
    }
}
