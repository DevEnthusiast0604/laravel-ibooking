<?php

namespace App\Exceptions;

use Facade\Ignition\Exceptions\ViewException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use DB;
use Exception;
use PDOException;
use Illuminate\Support\Facades\Artisan;

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
     * @param  \Throwable  $exception
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            $status_code = $exception->getStatusCode();
            if (in_array($status_code, [404, 500, 503])) {
                return response()->view('Frontend::errors.' . $status_code, [], 200);
            }
        }

        if($exception instanceof ViewException || $exception instanceof QueryException){
            Artisan::call('migrate');
            Artisan::call('db:seed --class=TaxonomySeeder');
            if($exception instanceof ViewException) {
                return redirect()->to('/');
            }
        }

        return parent::render($request, $exception);
    }
}
