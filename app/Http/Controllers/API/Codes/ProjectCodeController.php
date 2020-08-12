<?php

namespace App\Http\Controllers\API\Codes;

use App\Http\Resources\Codes\ProjectCodeResource;
use App\Models\Codes\ProjectCode;
use App\Models\Ledger;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ProjectCodeResource::collection(ProjectCode::all()->sortByDesc('created_at'));
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
        ProjectCode::create($request->all());

        return ProjectCodeResource::collection(ProjectCode::all()->sortByDesc('created_at'));
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
    public function update(Request $request, ProjectCode $projectCode)
    {
        $projectCode->update($request->all());

        return ProjectCodeResource::collection(ProjectCode::all()->sortByDesc('created_at'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectCode $projectCode)
    {
        if (count(Ledger::where('project_code_id', $projectCode->id)->get())) {
            return response()->json(['deleted' => false]);
        } else {
            $projectCode->delete();
            return response()->json(['deleted' => true]);
        }
    }
}
