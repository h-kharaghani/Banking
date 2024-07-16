<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function jsonSuccessResponse(array $data = [])
    {
        return response()->json(['status' => true, 'data' => $data], 200);
    }
}
