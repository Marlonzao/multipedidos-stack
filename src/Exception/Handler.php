<?php

namespace Multipedidos;

use \Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as LaravelHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Output\ConsoleOutput;

class ExceptionHandler extends LaravelHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        if(env('APP_ENV') != 'production'){
            parent::report($exception);
            return;
        }

        if($this->checkIsCloudwatchException($exception)){
            return;
        }

        if($this->checkIsMultipedidosException($exception)){
            // \Cloudwatch::logException($exception, 'MultipedidosException/prod');
            return;
        }

        if ($this->shouldntReport($exception)) {
            return;
        }

        if (method_exists($exception, 'report')) {
            return $exception->report();
        }

        // \Cloudwatch::logException($exception, env('CLOUDWATCH_GROUP'), 'info', $body);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if($this->checkIsMultipedidosException($exception)){
            return response()->json([
                'error' => $exception->getMessage(),
                'alertMethod' => $exception->getAlertMethod()
            ], $exception->getCode());    
        }

        if(env('APP_ENV') == 'testing'){
            $outputFormatter = new \Symfony\Component\Console\Formatter\OutputFormatter(false, [
                'error' => new \Symfony\Component\Console\Formatter\OutputFormatterStyle('yellow', 'blue')
            ]);

            $output = new ConsoleOutput(ConsoleOutput::VERBOSITY_VERBOSE, null, $outputFormatter);

            (new ConsoleApplication)->renderThrowable($exception, $output);
            exit(1);
        }

        return parent::render($request, $exception);
    }

    public function checkIsMultipedidosException($exception)
    {
        return is_in_namespace('MultipedidosException', $exception) || $exception instanceof \MultipedidosException;
    }

    public function checkIsCloudwatchException($exception)
    {
        return is_in_namespace('Aws\CloudWatchLogs\Exception\CloudWatchLogsException', $exception) || $exception instanceof \Aws\CloudWatchLogs\Exception\CloudWatchLogsException;
    }
}
