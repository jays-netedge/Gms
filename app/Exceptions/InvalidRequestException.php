<?php

namespace App\Exceptions;

use Exception;

class InvalidRequestException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request,$exception){
        ob_clean();
        $response['code'] = 0;
        $response['status'] = 'error';
        $response['message'] = 'Message';
        $response['data'] = '';
        if(!$request->ajax()){
            // non ajax response
        }else{
            return response()->json($response);
        }
    }
}
