<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const CODE_OK = 200;
    const CODE_CREATED = 201;
    const CODE_DELETED = 204;
    const CODE_NOT_FOUND = 404;
    const CODE_UNAUTHORIZED = 401;
    const CODE_CONFLICT_DATA = 409;
    const CODE_INVALID_REQUEST = 422; // request parameters not valid
    const CODE_INTERNAL_SERVER_ERROR = 500;

    const INVALID_REQUEST = "Validation Error";
    const INTERNAL_SERVER_ERROR = "Something Error";
    const OPERATION_SUCCESS = "Operation Successful.";

    //common response function
    function prepareResponse($code, $message = null, $response = null, $errors = null, $status_code = null)
    {
        $message = ($message == null) ? "" : $message;
        $response = ($response == null) ? "" : $response;
        $errors = ($errors == null) ? "" : $errors;
        $status_code = ($status_code == null) ? "" : $status_code;
        return Response::json(['code' => $code, 'status_code' => $status_code, 'message' => $message, 'data' => $response, 'errors' => $errors], $code);
    }

    //response function for success
    function successResponse($code, $message = null, $response = null)
    {
        $message = ($message == null) ? "" : $message;
        $response = ($response == null) ? "" : $response;
       // $error = ($error == null) ? "" : $error;
        return Response::json(['code' => $code, 'message' => $message, 'data' => $response], $code);
    }

    //response function for errors
    function errorResponse($code, $message = null, $errors = null, $status_code = null)
    {
        $message = ($message == null) ? "" : $message;
        $errors = ($errors == null) ? "" : $errors;
        $status_code = ($status_code == null) ? "" : $status_code;
        return Response::json(['code' => $code, 'message' => $message, 'errors' => $errors, 'status_code' => $status_code,], $code);
    }

}
