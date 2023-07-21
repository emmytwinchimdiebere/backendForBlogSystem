<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function LoginUser (Request $request){
     
   
        // valiadtes all user inputs
        
        $validateUserInputs = validator($request->all(),[
            'email'=>["email", "string","required"],
            'password'=>["string", "required"]

        ]);

        
        //returns a respons if the the validation fails
        if($validateUserInputs->fails()){
            return response()->json([
                "error"=>$validateUserInputs->errors(),
                "status"=>403
            ]);;
        }

        //checking if the credential the user provided matches our record then return true false
        if(Auth::attempt(["password"=>$request->password, "email"=>$request])){
            $user =  User::where("email", $request->email)->first();

            

            if($user){
                $token  = $user->createToken("token", ['post:create', "post:update", "post:delete"]);

                return response()->json([
                    'token'=>$token,
                    'success'=>" Login Succcessfull",
                    'status'=>200
                ]);
                
            }
        }

        return response()->json([
            'error'=>"Email or pasword invalid",
            'status'=> 401
        ]);

    }
}
