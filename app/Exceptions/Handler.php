<?php

namespace App\Exceptions;

use App\API\ApiError;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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
        if ($request->is('api/contatos/*')) {
            if (config('app.debug')) {
                return \response()->json(ApiError::errorMessage($exception->getMessage(), $exception->getCode()), 500);
            }
            if ($exception->getCode() == 0) {
                return \response()->json(ApiError::errorMessage('Contato não encontrado', 404), 404);
            } else if ($exception->getCode() == 42000) {
                return \response()->json(ApiError::errorMessage('Erro no SQL', 500), 500);
            } else {
                return \response()->json(ApiError::errorMessage("Algo deu errado", 400), 404);
            }
        }
        // Nesse caso aqui o Laravel retorna a pagina 404
        return parent::render($request, $exception);
    }
}
