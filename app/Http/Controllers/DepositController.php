<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\DepositStatus;
use App\Models\UserType;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class DepositController extends ResponseController
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->type_id != UserType::getForCustomer()){
            return $this->sendError("Only User type 'Customer' can make a deposit.");
        }

        $validator = Validator::make($request->all(), [
            'amount' => ['required','gte:0','regex:/^[0-9]{1,6}+(?:\.[0-9]{1,2})?$/'],
            'description' => ['required','max:200'], 
            // 'image' => ['required','image','max:1024']
            'image' => ['required','image']
        ]);
        
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        //logic to work with image
        $imageFile = $request->file('image');
        
        $path = Auth::user()->id;
        $imageNewName = Carbon::now()->timestamp . '.' . $imageFile->getClientOriginalExtension();
        
        $filename = Storage::disk('images')->putFileAs($path, $imageFile, $imageNewName);
        //$imageFullPath = Storage::disk('images')->path('') . $filename ;

        //save
        
        $deposit = new Deposit();
        
        $deposit->amount = $request->amount;
        $deposit->description = $request->description;
        $deposit->image = $filename;
        $deposit->status_id = DepositStatus::getForPending();
        $deposit->user_id = Auth::user()->id;
        $deposit->save();

        return $this->sendResponse($deposit->toArray(), 'Deposit created');  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function show(Deposit $deposit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function edit(Deposit $deposit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deposit $deposit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Deposit  $deposit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deposit $deposit)
    {
        //
    }
}
