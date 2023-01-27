<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use validator;

class UserController extends Controller
{

    public function index()
    {
        return Users::get();
    }

    public function saveUser(Request $req)
    {
        $validated = $req->validate(
            [
                'name' => 'required|min:3',
                'email' => 'required|email',
            ],
            [
                'name.required' => 'Name is required very much',
                'name.min' => 'Name be like name yaar',
            ]
        );


        $obj = new Users();
        $obj->name = $req->name;
        $obj->email = $req->email;
        $obj->password = 'password';
        $obj->save();
        response()->json(['success' => 'user save successfully'], 200);

    }

    public function updateUser(Request $req, $user_id)
    {

        
        $validated = $req->validate(
            [
                'name' => 'required|min:3',
                'email' => 'required|email',
            ],
            [
                'name.required' => 'Name is required very much',
                'name.min' => 'Name be like name yaar',
            ]
        );

        $data['name'] = $req->input('name');
        $data['email'] = $req->input('email');

        $query = Users::where('id', $user_id)->update($data);
        response()->json(['success' => 'user updated successfully'], 200);
    }

    public function deleteUser(Request $req, $user_id)
    {

        
        // $validated = $req->validate(
        //     [
        //         'name' => 'required|min:3',
        //         'email' => 'required|email',
        //     ],
        //     [
        //         'name.required' => 'Name is required very much',
        //         'name.min' => 'Name be like name yaar',
        //     ]
        // );

        // $data['name'] = $req->input('name');
        // $data['email'] = $req->input('email');

        $query = Users::where('id', $user_id)->delete();
        response()->json(['success' => 'user delete successfully'], 200);
    }
    

}