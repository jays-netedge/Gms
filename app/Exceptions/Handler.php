<?php

namespace App\Exceptions;

use App\Http\Controllers\BaseController;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        $data = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];

        Log::info($data);

        if (!env('APP_DEBUG')) { //Release Mode
            //In release mode, do not send sensitive data to clients
            $data = ['message' => $exception->getMessage(),];
        }

        $repo = new BaseController();

        $code = $exception->getCode();
        $message = $exception->getMessage();

        if ($exception instanceof NotFoundHttpException) {
            $code = $repo::CODE_NOT_FOUND;
            $message = "Resource Not found";
        } else if ($exception instanceof InvalidRequestException) {
            $code = $repo::CODE_INVALID_REQUEST;
            $message = $message ?? 'Invalid Request';
        } else if ($exception instanceof ValidationException) {
            $code = $repo::CODE_INVALID_REQUEST;
            $message = $exception->validator->errors()->first();
            $data['validation'] = $exception->validator->errors();
        } else if ($exception instanceof AuthenticationException) {
            $data['validation'] = null;
            $message = 'unauthenticated';
            $code = $repo::CODE_UNAUTHORIZED;
        } else if ($exception instanceof QueryException) {
            $code = $repo::CODE_INTERNAL_SERVER_ERROR;
            $message = 'something went wrong';
            if (env('APP_DEBUG')) {
                dump($exception); //dump and continue
            }
        } else if ($exception instanceof AuthorizationException) {
            $code = $repo::CODE_UNAUTHORIZED;
            $message = $exception->getMessage();
        } else { //Compilation Error only
            $code = 500;
            $message = $exception->getMessage();
            if (env('APP_DEBUG')) {
                //Here, exception printing of compilation error is very helpful than the default short error mrssage.
                dump($exception); //dump and continue
            }
        }

        $validation = $data['validation'] ?? null;
        return $repo->prepareResponse($code, $message, null, $validation);
    }
}
