<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\DepositStatus;
use App\Models\UserType;
use App\Models\User;

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
        if (Auth::user()->type_id != UserType::getForCustomer()) {
            return $this->sendError("Only User type 'Customer' can make a deposit.");
        }

        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'gte:0', 'regex:/^[0-9]{1,6}+(?:\.[0-9]{1,2})?$/'],
            'description' => ['required', 'max:200'],
            'image' => ['required']
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        // * logic to work with image
 
        //get image
        $imageBase64 = $request->image;

        //get image extension and validate it
        $image_parts = explode(";base64,", $imageBase64);
        $image_extension_aux = explode("image/", isset($image_parts[0]) ? $image_parts[0] : '' );
        $image_extension = isset($image_extension_aux[1]) ? $image_extension_aux[1]: '';
        if(!in_array($image_extension,["png","jpeg","bmp"])){
            return $this->sendError("Something wrong, check your format and remember accepted extension are: png|jpeg|bmp");
        }

        //fancy code
        $imageBase64 = substr($imageBase64, strpos($imageBase64, ",") + 1);

        //set image name
        $imageNewName = Auth::user()->id . '/' . Carbon::now()->timestamp . '.' . $image_extension;

        //create dir if not exist
        if (!is_dir(Storage::disk('images')->path('') . Auth::user()->id)) {
            mkdir(Storage::disk('images')->path('') . Auth::user()->id);
        }

        //save image
        file_put_contents(Storage::disk('images')->path('') . $imageNewName, base64_decode($imageBase64));

        //save deposit

        $deposit = new Deposit();

        $deposit->amount = $request->amount;
        $deposit->description = $request->description;
        $deposit->image = $imageNewName;
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


    /**
     * Updated Status 
     */
    public function updatedStatus(Request $request)
    {

        if (Auth::user()->type_id != UserType::getForAdmin()) {
            return $this->sendError("Only User type 'Admin' can do this action.");
        }

        $validator = Validator::make($request->all(), [
            'id' => ['required', 'exists:deposits'],
            'status_id' => ['required', 'exists:deposit_statuses,code'],
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        if (!in_array($request->status_id, [DepositStatus::getForAccepted(), DepositStatus::getForRejected()])) {
            return $this->sendError("Status not accepted.");
        }

        $deposit = Deposit::findOrFail($request->id);

        if ($deposit->status_id != DepositStatus::getForPending()) {
            return $this->sendError("The current status can not be updated.");
        }

        //update balance
        if ($request->status_id == DepositStatus::getForAccepted()) {
            $user = User::findOrFail($deposit->user_id);
            $user->updateBalance($deposit->amount, '+');
        }

        //update deposit status
        $deposit->status_id = $request->status_id;
        $deposit->save();

        return $this->sendResponse($deposit->toArray(), 'Deposit status updated.');
    }

    /**
     * Pagination (it is not implemented yet the pagination feature, now it is getting all records)
     */

    public function pagination(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'created_at' => ['required', 'date', 'before_or_equal:today'],
        ]);

        $validator->sometimes('status_id', ['required', 'exists:deposit_statuses,code'], function ($request) {
            return Auth::user()->type_id == UserType::getForCustomer();
        });

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $query = Deposit::query();
        $query->whereBetween('created_at', [$request->created_at . ' 00:00:00', $request->created_at . ' 23:59:59']);


        if (Auth::user()->type_id == UserType::getForAdmin()) {
            $query->where('status_id', '=', DepositStatus::getForPending());
        } else {
            $query->where('user_id', '=', Auth::user()->id);
            $query->where('status_id', '=', $request->status_id);
        }

        $query->orderBy('created_at','DESC');

        $deposits = $query->get();

        return $this->sendResponse($deposits->toArray(), 'Deposits gotten.');
    }
}
