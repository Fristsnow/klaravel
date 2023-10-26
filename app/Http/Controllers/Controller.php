<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function successReturn($message, $code, $data = null)
    {
        $return = [
            "message" => $message
        ];
        ($data || is_array($data)) && $return['data'] = $data;
        return response()->json($return, $code);
    }

    function success201($data = null)
    {
        return $this->successReturn('success', 201, $data);
    }
    function success200($data = null)
    {
        return $this->successReturn('success', 200, $data);
    }
}
