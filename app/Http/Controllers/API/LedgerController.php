<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\LedgerResource;
use App\Models\Ledger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return LedgerResource::collection(Ledger::all()->sortByDesc('date_encoded'));

        /*$from = date('2020-08-15');
        $to = date('2020-08-15');
        return LedgerResource::collection(Ledger::whereBetween('date_encoded', [$from, $to])->get());*/
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
        Ledger::create($request->all());

        return LedgerResource::collection(Ledger::all()->sortByDesc('date_encoded'));
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
    public function update(Request $request, Ledger $ledger)
    {
        $ledger->update($request->all());

        return LedgerResource::collection(Ledger::all()->sortByDesc('date_encoded'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function destroy(Ledger $ledger)
    {
        $ledger->delete();

        return LedgerResource::collection(Ledger::all()->sortByDesc('date_encoded'));
    }
}
