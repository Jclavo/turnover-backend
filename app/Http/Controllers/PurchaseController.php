<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\User;
use App\Models\UserType;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends ResponseController
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
            return $this->sendError("Only User type 'Customer' can make a purchase.");
        }

        $validator = Validator::make($request->all(), [
            'amount' => ['required','gte:0','regex:/^[0-9]{1,6}+(?:\.[0-9]{1,2})?$/'],
            'description' => ['required','max:200'], 
        ]);
        
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        //check enough balance and do the transaction
        $user = User::findOrFail(Auth::user()->id); 
        if(!$user->updateBalance($request->amount,'-')){
            return $this->sendError('Your balance is not enough.');
        }

        //save
        $purchase = new purchase();
        $purchase->amount = $request->amount;
        $purchase->description = $request->description;
        $purchase->user_id = Auth::user()->id;
        $purchase->save();

        return $this->sendResponse($purchase->toArray(), 'Purchase created');  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        //
    }

    /**
     * Pagination (it is not implemented yet the pagination feature, now it is getting all records)
    */

    public function pagination(Request $request)
    {
        if (Auth::user()->type_id != UserType::getForCustomer()){
            return $this->sendError("Only User type 'Customer' can make a purchase.");
        }

        $validator = Validator::make($request->all(), [
            'created_at' => ['required','date','before_or_equal:today'],
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $query = Purchase::query();
        $query->whereBetween('created_at',[$request->created_at.' 00:00:00', $request->created_at.' 23:59:59']);
        $query->where('user_id', '=', Auth::user()->id);
        $query->orderBy('created_at','DESC');
        $purchases = $query->get();

        return $this->sendResponse($purchases->toArray(), 'Purchases gotten.');  
    }
}
