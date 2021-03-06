<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\LedgerResource;
use App\Models\Ledger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Promise\all;

class ReportController extends Controller
{
    public function index()
    {
        return LedgerResource::collection(Ledger::all()->sortByDesc('date_encoded'));

        /*$from = '2020-08-12';
        $to = '2020-08-17';
        return LedgerResource::collection(Ledger::whereBetween('date_encoded', [$from, $to])->whereBetween('amount', [0, 20])->where('account_code_id', '23')->where('project_code_id', '13')->get()->sortByDesc('date_encoded'));*/
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function filterData(Request $request) {
        /*$from = '2020-08-12';
        $to = '2020-08-17';
        return LedgerResource::collection(Ledger::whereBetween('date_encoded', [$from, $to])->whereBetween('amount', [0, 20])->where('account_code_id', '23')->where('project_code_id', '13')->get()->sortByDesc('date_encoded'));*/

        //return LedgerResource::collection(Ledger::where('account_code_id', '23')->where('project_code_id', '11')->get());

        if ($request->date_encoded_from) {
            $date_encoded = array($request->date_encoded_from, $request->date_encoded_to);
        } else {
            $date_encoded = false;
        }

        if ($request->amount_from) {
            $amount = array($request->amount_from, $request->amount_to);
        } else {
            $amount = false;
        }

        $account_code_id = $request->account_code_id;
        $project_code_id = $request->project_code_id;

        return LedgerResource::collection(DB::table('ledgers')
            ->when($date_encoded, function ($query, $date_encoded) {
                return $query->whereBetween('date_encoded', [date($date_encoded[0]), date($date_encoded[1])]);
            })
            ->when($account_code_id, function ($query, $account_code_id) {
                return $query->where('account_code_id', $account_code_id);
            })
            ->when($project_code_id, function ($query, $project_code_id) {
                return $query->where('project_code_id', $project_code_id);
            })
            ->when($amount, function ($query, $amount) {
                return $query->whereBetween('amount', [$amount[0], $amount[1]]);
            })
            ->orderByDesc('date_encoded')
            ->get()
        );

        /*
        //1111
        if (($request->date_encoded_from && $request->date_encoded_to) && $request->account_code_id && $request->project_code_id && ($request->amount_from && $request->amount_to)) {
            return LedgerResource::collection(Ledger::whereBetween('date_encoded', [date($request->date_encoded_from), date($request->date_encoded_to)])->where('account_code_id', $request->account_code_id)->where('project_code_id', $request->project_code_id)->whereBetween('amount', [$request->amount_from, $request->amount_to])->get()->sortByDesc('date_encoded'));
        //1110
        } elseif (($request->date_encoded_from && $request->date_encoded_to) && $request->account_code_id && $request->project_code_id && !($request->amount_from && $request->amount_to)) {
            return LedgerResource::collection(Ledger::whereBetween('date_encoded', [date($request->date_encoded_from), date($request->date_encoded_to)])->where('account_code_id', $request->account_code_id)->where('project_code_id', $request->project_code_id)->get()->sortByDesc('date_encoded'));
        //1101
        } elseif (($request->date_encoded_from && $request->date_encoded_to) && $request->account_code_id && !$request->project_code_id && ($request->amount_from && $request->amount_to)) {
            return LedgerResource::collection(Ledger::whereBetween('date_encoded', [date($request->date_encoded_from), date($request->date_encoded_to)])->where('account_code_id', $request->account_code_id)->whereBetween('amount', [$request->amount_from, $request->amount_to])->get()->sortByDesc('date_encoded'));
        //1100
        } elseif (($request->date_encoded_from && $request->date_encoded_to) && $request->account_code_id && !$request->project_code_id && !($request->amount_from && $request->amount_to)) {
            return LedgerResource::collection(Ledger::whereBetween('date_encoded', [date($request->date_encoded_from), date($request->date_encoded_to)])->where('account_code_id', $request->account_code_id)->get()->sortByDesc('date_encoded'));
        //1011
        } elseif (($request->date_encoded_from && $request->date_encoded_to) && !$request->account_code_id && $request->project_code_id && ($request->amount_from && $request->amount_to)) {
            return LedgerResource::collection(Ledger::whereBetween('date_encoded', [date($request->date_encoded_from), date($request->date_encoded_to)])->where('project_code_id', $request->project_code_id)->whereBetween('amount', [$request->amount_from, $request->amount_to])->get()->sortByDesc('date_encoded'));
        //1010
        } elseif (($request->date_encoded_from && $request->date_encoded_to) && !$request->account_code_id && $request->project_code_id && !($request->amount_from && $request->amount_to)) {
            return LedgerResource::collection(Ledger::whereBetween('date_encoded', [date($request->date_encoded_from), date($request->date_encoded_to)])->where('project_code_id', $request->project_code_id)->get()->sortByDesc('date_encoded'));
        //1001
        } elseif (($request->date_encoded_from && $request->date_encoded_to) && !$request->account_code_id && !$request->project_code_id && ($request->amount_from && $request->amount_to)) {
            return LedgerResource::collection(Ledger::whereBetween('date_encoded', [date($request->date_encoded_from), date($request->date_encoded_to)])->whereBetween('amount', [$request->amount_from, $request->amount_to])->get()->sortByDesc('date_encoded'));
        //1000
        } elseif (($request->date_encoded_from && $request->date_encoded_to) && !$request->account_code_id && !$request->project_code_id && !($request->amount_from && $request->amount_to)) {
            return LedgerResource::collection(Ledger::whereBetween('date_encoded', [date($request->date_encoded_from), date($request->date_encoded_to)])->get()->sortByDesc('date_encoded'));
        //0111
        } elseif (!($request->date_encoded_from && $request->date_encoded_to) && $request->account_code_id && $request->project_code_id && ($request->amount_from && $request->amount_to)) {
            return LedgerResource::collection(Ledger::where('account_code_id', $request->account_code_id)->where('project_code_id', $request->project_code_id)->whereBetween('amount', [$request->amount_from, $request->amount_to])->get()->sortByDesc('date_encoded'));
        //0110
        } elseif (!($request->date_encoded_from && $request->date_encoded_to) && $request->account_code_id && $request->project_code_id && !($request->amount_from && $request->amount_to)) {
            return LedgerResource::collection(Ledger::where('account_code_id', $request->account_code_id)->where('project_code_id', $request->project_code_id)->get()->sortByDesc('date_encoded'));
        //0101
        } elseif (!($request->date_encoded_from && $request->date_encoded_to) && $request->account_code_id && !$request->project_code_id && ($request->amount_from && $request->amount_to)) {
            return LedgerResource::collection(Ledger::where('account_code_id', $request->account_code_id)->whereBetween('amount', [$request->amount_from, $request->amount_to])->get()->sortByDesc('date_encoded'));
        //0100
        } elseif (!($request->date_encoded_from && $request->date_encoded_to) && $request->account_code_id && !$request->project_code_id && !($request->amount_from && $request->amount_to)) {
            return LedgerResource::collection(Ledger::where('account_code_id', $request->account_code_id)->get()->sortByDesc('date_encoded'));
        //0011
        } elseif (!($request->date_encoded_from && $request->date_encoded_to) && !$request->account_code_id && $request->project_code_id && ($request->amount_from && $request->amount_to)) {
            return LedgerResource::collection(Ledger::where('project_code_id', $request->project_code_id)->whereBetween('amount', [$request->amount_from, $request->amount_to])->get()->sortByDesc('date_encoded'));
        //0010
        } elseif (!($request->date_encoded_from && $request->date_encoded_to) && !$request->account_code_id && $request->project_code_id && !($request->amount_from && $request->amount_to)) {
            return LedgerResource::collection(Ledger::where('project_code_id', $request->project_code_id)->get()->sortByDesc('date_encoded'));
        //0001
        } elseif (!($request->date_encoded_from && $request->date_encoded_to) && !$request->account_code_id && !$request->project_code_id && ($request->amount_from && $request->amount_to)) {
            return LedgerResource::collection(Ledger::whereBetween('amount', [$request->amount_from, $request->amount_to])->get()->sortByDesc('date_encoded'));
        //0000
        } else {
            return LedgerResource::collection(Ledger::all()->sortByDesc('date_encoded'));
        }*/
    }
}
