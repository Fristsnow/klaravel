<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\StoreAuthRequest;
use App\Http\Requests\StroeAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Http\Requests\UpdateAuthRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $adminUser = User::where('is_admin', false)->get(['id', 'email', 'full_name', 'create_time']);

        $adminUser->each(function ($item) {
            $item->cart_total = 0;
        });
        return $this->success200($adminUser);
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
    public function store(StroeAdminRequest $request)
    {
        $registerUser = User::create([
            'email' => $request->email,
            'full_name' => $request->username,
            'password' => $request->password,
            'create_time' => Carbon::now()->format('Y-m-d H:i'),
            'is_admin' => false
        ]);
        $registerUser->token = md5($request->email);
        $registerUser['username'] = $registerUser['full_name'];
        unset($registerUser['full_name']);
        return $this->success200($registerUser->only(['id', 'email', 'username', 'token', 'create_time']));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage. Login
     */
    public function update(UpdateAuthRequest $request, User $user)
    {
        $client = User::where('email', $request->email)->first();
        if (!$client || !password_verify($request->password, $client->password))
            throw new BaseException(422, 'user credentials are invalid');

        $client->token = md5($client->email);
        $client->save();

        return $this->success200($client);
    }

    /**
     * Remove the specified resource from storage. Logout
     */
    public function destroy(Request $request)
    {
        $user = Auth::guard('api')->user();

        $user->token = null;
        $user->save();

        return response()->json(['msg' => 'success']);
    }

    public function reset($id)
    {
        $user = User::find($id);
        if (!$user || $user->is_admin === 1) throw new BaseException(404,"not found");

        $pwd = Str::random(8);
        $user->password = Hash::make($pwd);
        $user->save();
        return response()->json([
            'msg' => 'success',
            'data' => [
                'id' => $user->id,
                'password' => $pwd
            ]
        ]);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if (!$user || $user->is_admin === 1) throw new BaseException(404,"not found");
        $user->delete();
        return $this->success200();

    }

}
