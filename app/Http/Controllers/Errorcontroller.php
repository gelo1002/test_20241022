<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

use App\Models\Error;

class ErrorController extends Controller
{
    public function create($type_error_id, $detail, $file_id=null) {
        Error::create([
            'file_id'       => $file_id,
            'type_error_id' => $type_error_id,
            'detail'        => $detail,
        ]);
    }
}
