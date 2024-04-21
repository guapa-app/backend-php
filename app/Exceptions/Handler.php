<?php

namespace App\Exceptions;

use App\Mail\ErrorAlarmMail;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Mail;
use League\OAuth2\Server\Exception\OAuthServerException;
use Moyasar\Exceptions\ApiException as MoyasarApiException;
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
        OAuthServerException::class,
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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
            throw new NotFoundException();
        } elseif ($exception instanceof MoyasarApiException || $exception instanceof ClientException) {
            Mail::to(config('app.support_email'))
                ->send(new ErrorAlarmMail("LOG HANDLER - {$request->path()} \n {$exception->getMessage()}", $exception));
        }

        if ($request->wantsJson()) {
            return (new ApiException(__('api.server_error'), 400))->render($request);
        }
        return parent::render($request, $exception);
    }
}
