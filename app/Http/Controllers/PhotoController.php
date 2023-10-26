<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Models\Frame;
use App\Models\Photo;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePhotoRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $photo = Photo::where("user_id",Auth::guard('api')->user()->id);
        return $this->success200($photo);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        if (!$request->input('size_id') || !Size::where('id', $request->input('size_id'))->exists()) {
            return $this->notFoundMsg();
        }
        if (!$request->file('image')) {
            return $this->dataErrorMsg();
        }
        $path = $request->file('image')->store('image');

        $uploadPhoto = Photo::create([
            'edited_url' => null,
            'original_url' => asset('/storage/app/' . $path),
            'framed_url' => null,
            'status' => 'uploaded',
            'size_id' => $request->input('size_id'),
            'user_id' => auth()->id()
        ])->makeHidden(['size_id', 'user_id']);

        $uploadPhoto->save();

        return $this->success200($uploadPhoto);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePhotoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Photo $photo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePhotoRequest $request, Photo $photo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $deletePhoto = Photo::find($id);
        if (!$deletePhoto) throw new BaseException(404,"not found");
        $deletePhoto->delete();
        return $this->success200();
    }
    public function sizePhoto($id)
    {
        if (!Size::where('id', $id)->exists())
            return $this->notFoundMsg();
        $sizePhoto = Photo::where([
            'size_id' => $id,
            'status' => 'uploaded'
        ])->get(['id','edited_url','original_url','framed_url','status']);
        return $this->success($sizePhoto);
    }
    public function framePhoto(Request $request,$photo_id,$frame_id){
        if (!Frame::where('id',$frame_id)->exists())
            throw new BaseException(404,"not found");
        if (!$request->file('image'))
            throw new BaseException(422,"data error");
        $path = $request->file('image')->store('image');
        $photo = Photo::where('id',$photo_id)->first();
        $photo->frame_id =$frame_id;
        $photo->framed_url = asset('/storage/app/'.$path);
        $photo->save();
        return $this->success200($photo->only(['id','framed_url']));
    }
}
