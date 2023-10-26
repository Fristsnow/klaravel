<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\StoreAuthRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Http\Requests\UpdatePhotoRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $admin = User::where('is_admin', true)->get(['id', 'email', 'full_name', 'create_time']);
        return $this->success200($admin);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($id)
    {
        //
        $admin = User::find($id);
        if (!$admin || $admin->is_admin === 0) throw new BaseException(404, "not found");
        $pwd = Str::random(8);
        $admin->password = Hash::make($pwd);
        $admin->save();
        return response()->json([
            'msg' => 'success',
            'data' => [
                'id' => $admin->id,
                'password' => $pwd
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminRequest $request)
    {
        //
//        dd($request->full_name);
        $user = User::create([
            'email' => $request->email,
            'password' => $request->password,
            'full_name' => $request->full_name,
            'create_time' => Carbon::now()->format('Y-m-d H:i'),
            'is_admin' => true,
        ]);
        return $this->success200($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $admin = User::find($id);
        if (!$admin || $admin->is_admin === 0 || $admin->id === Auth::guard('api')->user()->id) throw new BaseException(404, "not found");
        $admin->delete();
        return $this->success200();
    }

    public function resetUser(UpdatePhotoRequest $request){
        $resetUser = Auth::guard('api')->user();
        //你怎么可以改admin的密码呢！！！【拖出去打死！！！】
        if (!$resetUser->is_admin === 1){
            return $this->notFoundMsg();
        }

        if (!Hash::check($request->original_password,Auth::user()->getAuthPassword())){
            throw new BaseException(422,"data error");
        }
        $resetUser->password = Hash::make($request->new_password);
        $resetUser->save();

        $resetUser['username'] = $resetUser['full_name'];
        unset($resetUser['full_name']);
        return $this->success200($resetUser->only(['id','email','username','create_time']));
    }
}
