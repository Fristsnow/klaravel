<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Models\Size;
use App\Http\Requests\StoreSizeRequest;
use App\Http\Requests\UpdateSizeRequest;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return $this->success200(Size::all());
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
    public function store(StoreSizeRequest $request,Size $size)
    {
        //

    }

    /**
     * Display the specified resource.
     */
    public function show(Size $size)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Size $size)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSizeRequest $request,$id)
    {
        //
        $sizeId = Size::find($id);
        if (!$sizeId) throw new BaseException(404,"not found");
        if (!is_numeric($request->price)) throw new BaseException(422,"data error");
        $sizeId->price = $request->price;
        $sizeId->save();
        $sizeId->refresh();
        return $this->success200($sizeId);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Size $size)
    {
        //
    }
}
