<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(Request $request)
    {
        if(in_array($request->method(),["POST","PATCH"])){
            $request->validate([
                'name' => $request->method() == "POST" ?'required|string|max:255|min:2': 'string|max:255|min:2',
                'email' => $request->method() == "POST" ? 'required|unique:users|email':'unique:users|email',
                'password' => $request->method() == "POST" ?'required':'null',
                'id' => $request->method() == "PATCH" ?'required':''
            ]);
        }
    }

    public function get(Request $request){
        if(isset($request->ids) && !empty($request->ids)){
            $users = User::find($request->ids);
        }else{
            $users = User::all();
        }
        return response()->json($users);
    }

    public function post(Request $request){
        $request = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => '1'
        ]);

    return response()->json($request);
    }

    public function patch(Request $request){
        $objectFromDB = User::Where("id",$request->id)->first();
        if( $request->name){
            $objectFromDB->name = $request->name;
        }

        if($request->email){
            $objectFromDB->email = $request->email;
        }

        $objectFromDB->save();
        return response()->json($objectFromDB);
    }
}
