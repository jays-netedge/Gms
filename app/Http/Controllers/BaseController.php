<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class BaseController extends Controller
{
    const CODE_OK = 200;
    const CODE_CREATED = 201;
    const CODE_DELETED = 204;
    const CODE_NOT_FOUND = 404;
    const CODE_UNAUTHORIZED = 401;
    const CODE_INVALID_REQUEST = 422; // request parameters not valid
    const CODE_INTERNAL_SERVER_ERROR = 500;


    function prepareResponse($code, $message = null, $response = null, $errors = null, $status_code = null)
    {
        $message = ($message == null) ? "" : $message;
        $response = ($response == null) ? "" : $response;
        $errors = ($errors == null) ? "" : $errors;
        $status_code = ($status_code == null) ? "" : $status_code;
        return Response::json(['code' => $code, 'status_code' => $status_code, 'message' => $message, 'data' => $response, 'errors' => $errors], $code);
    }

}
