<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends ResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'username' => ['required','max:45'],
            'email' => ['required','email','max:45','unique:users'], 
            'password' => ['required','min:8'], 
        ]);
        
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $user = new User();
        
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();

        return $this->sendResponse($user->toArray(), 'Customer created');  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    /***
     * Login
     */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return $this->sendError("email/password wrong.");  
        }

        Auth::user()->token = Auth::user()->createToken(Auth::user()->username)->plainTextToken;

        return $this->sendResponse(Auth::user()->toArray(), "Login ok");          
    }
}
