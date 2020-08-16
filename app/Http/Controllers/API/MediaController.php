<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\MediaResource;
use App\Models\Ledger;
use App\Models\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function store(Request $request)
    {
        if ($request->hasFile('media')) {
            //get image file.
            $media = $request->media;
            //get file extension.
            $ext = $media->getClientOriginalExtension();
            //make a unique name
            $unique_name = uniqid() . '.' . $ext;
            //upload the image
            request()->media->move(public_path('storage/files'), $unique_name);
            //create thumbnail
            //$thumbnail = Image::make(public_path('storage/image/profile_picture/') . $unique_name);

            //$thumbnail->resize(50, 50, function ($constraint) {
            //    $constraint->aspectRatio();
            //})->save(public_path('storage/image/thumbnail/') . $unique_name);

            Media::create([
                'ledger_id' => $request->ledger_id,
                'file_name' => $unique_name,
                'original_name' => $media->getClientOriginalName(),
            ]);
        }

        $ledger = Ledger::find($request->ledger_id);

        return MediaResource::collection($ledger->medias()->get()->sortByDesc('created_at'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show($id)
    {
        $ledger = Ledger::find($id);

        return MediaResource::collection($ledger->medias()->get()->sortByDesc('created_at'));

        //return response()->json(['data' => $ledger]);
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function destroy(Media $media)
    {
        if ($media->delete()) {
            $file_path = public_path('storage/files/'.$media->file_name);

            File::delete($file_path);

            $ledger = Ledger::find($media->ledger_id);

            return MediaResource::collection($ledger->medias()->get()->sortByDesc('created_at'));
        };
    }

    public function download($id)
    {
        $media = Media::find($id);

        $file_path = public_path('storage/files/'.$media->file_name);

        return response()->download($file_path, $media->original_name);
    }
}
