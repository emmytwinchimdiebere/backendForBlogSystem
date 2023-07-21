<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Db;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

        //register a new User
            public function UserSignUp(Request $request){
              
              //validate all user inputs
                $validateUserInput = validator($request->all(), [
                    "email"=> ["email","string", "required", "unique:users,email"],
                      "name"=> ["string" , "required"  , "max:20" , "min:2"],
                      "password"=>['required', 'max:20', 'min:8'],
                      "confirm_password"=>['same:password', 'required']
                ]);

                // return all validation errors if any

                if($validateUserInput->fails()){
                    return response()->json([
                        "error"=>$validateUserInput->errors(),
                        "status"=>401
                    ]);
                }

                $validatedInputs = $request->input();


                try{
                    //initilaize new user instancee
                        $user = new User();

                        //retrieve all the validated user inputs
                        
                       
                       //check if the values are not null & initialize
                        if(isset($validatedInputs)){
                            $user->password = Hash::make($validatedInputs["password"]);
                            $user->email = $validatedInputs["email"];
                            $user->name = $validatedInputs["name"];

                            //saving the user inputs to the backend
                            $saveInputs =  $user->save();

                            //check if the data is successfully save & return a message

                            if($saveInputs){
                                return response()->json([
                                    "success"=>true,
                                    "status" =>200
                                ]);
                            }

                        }

                    
                    }catch(\Exception $e){
                    
                        //catch all errors & return a response
                        return response()->json([
                            'success'=>false,
                            "errorMsg" =>$e->getMessage(),
                            "status"=>500
                        ]);
                }
    }
            

}
   
