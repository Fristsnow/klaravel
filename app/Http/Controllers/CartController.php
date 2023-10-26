<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\CartRequest;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function resetUser($id)
    {
        $cartUser = User::find($id);
        if ($cartUser) {
            $cartUser->photos()->where('status', 'cart')->update(['status' => 'uploaded']);
            return $this->success200();
        } else {
            throw new BaseException(404,"not found");
        }

    }
    public function index()
    {
        //
//        $list = Photo::where([
//            'user_id' => Auth::id(),
//            'status' => 'cart'])->get();
////        dd($list);
//        return $this->success($list->makeHiddle(['status']));
        return $this->success(Photo::where(['user_id' => Auth::id(), 'status' => 'cart'])->get()->makeHidden(['status']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CartRequest $request)
    {
        //
        if (!$request->photo_id_list || !is_array($request->photo_id_list)) throw new BaseException(422);
        foreach ($request->photo_id_list as $id) {
            $photo = Photo::find($id);
            if (!$photo)
                throw new BaseException(404);
            if ($photo->status != 'uploaded')
                throw new BaseException(422);

        }
        foreach ($request->photo_id_list as $id) {
            $photo = Photo::find($id);
            $photo->status = 'cart';
            $photo->save();
        }
        return $this->success();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
