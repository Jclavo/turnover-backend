<?php

namespace App\Http\Controllers;

use App\Models\DepositStatus;
use Illuminate\Http\Request;

class DepositStatusController extends ResponseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $depositStatuses = DepositStatus::all();
      
        return $this->sendResponse($depositStatuses, 'records gotten.');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DepositStatus  $depositStatus
     * @return \Illuminate\Http\Response
     */
    public function show(DepositStatus $depositStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DepositStatus  $depositStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(DepositStatus $depositStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DepositStatus  $depositStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DepositStatus $depositStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DepositStatus  $depositStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(DepositStatus $depositStatus)
    {
        //
    }
}
