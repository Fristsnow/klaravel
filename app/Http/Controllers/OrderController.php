<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Models\Frame;
use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Photo;
use App\Models\Size;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        if (User::find(Auth::guard('api')->user()->id)->is_admin === true){
            return $this->success200(Order::with("photos")->get());
        }else{
            return $this->success(Auth::user()->orders()->with('photos')->get());
        }
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
    public function store(StoreOrderRequest $request)
    {
        //
        $order = new Order([
            "full_name" => $request->full_name,
            "phone_number" => $request->phone_number,
            "shipping_address" => $request->shipping_address,
            "card_number" => $request->card_number,
            "name_on_card" => $request->name_on_card,
            "exp_date" => $request->exp_date,
            "cvv" => $request->cvv,
            'status' => 'Valid',
            'order_placed' => Carbon::now()->format('Y-m-d'),
            'user_id' => Auth::id()
        ]);
        $total = 0;
        foreach ($request->photo_id_list as $id) {
            $photo = Photo::find($id);
            if (!$photo) throw new BaseException(404);
            if ($photo->status !== 'cart') throw new BaseException(422);
            if ($photo->frame_id) {
                $frame = Frame::find($photo->frame_id);
                if ($frame)
                    $total += $frame->frame_price;
            }
            if ($photo->size_id) {
                $size = Size::find($photo->size_id);
                if (!$size)
                    $total += $size->price;
            }
        }
        $order->total = $total;
        $order->save();

        foreach ($request->photo_id_list as $id) {
            $photo = Photo::find($id);
            $photo->status = 'order';
            $photo->order_id = $order->id;
            $photo->save();
        }
        return $this->success200($order);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
    public function CancelOrder($id){
        $order = Order::find($id);
        if ($order && $order->status == 'Valid'){
            $order->status = 'InValid';
            $order->save();
            return $this->success200();
        }else{
            throw new BaseException(404,"not found");
        }
    }

    public function CompleteOrder($id){
        $order = Order::find($id);

        if ($order && $order->status == 'Valid'){
            $order->status = 'Completed';
            $order->save();
            return $this->success200();
        }else{
            throw new BaseException(404,"not found");
        }
    }
    public function cancel($id)
    {
        $order = Order::find($id);
        if ($order && $order->status === 'Valid') {
            $order->status = 'Invalid';
            $order->save();
            return $this->success200();
        } else {
            throw new BaseException(404);
        }
    }
}
