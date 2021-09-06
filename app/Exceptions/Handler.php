<?php

namespace App\Exceptions;

use App\Utils\Result;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

//    /**
//     * Render an exception into an HTTP response.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  \Exception  $exception
//     * @return \Illuminate\Http\Response
//     */

    /**
     * 截取异常
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     * @throws Exception
     * author hy
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ApiException) {
            return Result::fail($exception->getMessage());
        }

        return parent::render($request, $exception);
    }
}
