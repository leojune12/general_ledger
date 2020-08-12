<?php

namespace App\Http\Controllers\API\Codes;

use App\Http\Resources\Codes\AccountCodeResource;
use App\Models\Codes\AccountCode;
use App\Models\Ledger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AccountCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return AccountCodeResource::collection(AccountCode::all()->sortByDesc('created_at'));
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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function store(Request $request)
    {
        AccountCode::create($request->all());

        return AccountCodeResource::collection(AccountCode::all()->sortByDesc('created_at'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function update(Request $request, AccountCode $accountCode)
    {
        $accountCode->update($request->all());

        return AccountCodeResource::collection(AccountCode::all()->sortByDesc('created_at'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountCode $accountCode)
    {
        if (count(Ledger::where('account_code_id', $accountCode->id)->get())) {
            return response()->json(['deleted' => false]);
        } else {
            $accountCode->delete();
            return response()->json(['deleted' => true]);
        }
    }
}
