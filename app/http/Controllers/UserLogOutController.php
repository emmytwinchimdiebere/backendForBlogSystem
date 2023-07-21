<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class UserLogOutController extends Controller
{
    //get the user making the request and revoke the token

    use AuthorizesRequests, ValidatesRequests; 
   
   
    public function revokeToken(Request $request){
      try{
            //revoke the user access token
        $token = $request->user()->currentAccessToken()->delete();

        if($token){
            return response()->json([
                "success"=>true,
                "status"=>200
            ]);
        }

      }catch(\Exception $e ){
            return response()->json([
                'success'=>false,
                "status"=>500,
                "errorMsg"=>$e->getMessage(),
            ]);
      }
    }
}
