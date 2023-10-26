<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Models\Frame;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFrameRequest;
use App\Http\Requests\UpdateFrameRequest;

class FrameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return $this->success200(Frame::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFrameRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Frame $frame)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Frame $frame)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFrameRequest $request,$id)
    {
        //
        $frame = Frame::find($id);
        if (!$frame) throw new BaseException(404,'not found');
        if (!is_numeric($request->price) && !$request->frame_name) throw new BaseException(422,'data error');
        $frame->price = $request->price;
        $frame->frame_name = $request->frame_name;
        $frame->save();
        $frame->refresh();
        return $this->success200($frame);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Frame $frame)
    {
        //
    }
}
