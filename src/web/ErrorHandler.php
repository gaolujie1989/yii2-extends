<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\workerman\web;

use Yii;
use yii\base\ErrorException;
use yii\base\ExitException;

/**
 * Class ErrorHandler
 * @package lujie\workerman\web
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ErrorHandler extends \yii\web\ErrorHandler
{
    /**
     * @inheritdoc
     */
    public function register(): void
    {
        ini_set('display_errors', false);
        //not register any handler
    }

    public function unregister(): void
    {
    }

    /**
     * Handles uncaught PHP exceptions.
     *
     * This method is implemented as a PHP exception handler.
     *
     * @param \Exception $exception the exception that is not caught
     */
    public function handleException($exception): void
    {
        if ($exception instanceof ExitException) {
            return;
        }

        $this->exception = $exception;

        // disable error capturing to avoid recursive errors while handling exceptions
        $this->unregister();

        try {
            $this->logException($exception);
            if ($this->discardExistingOutput) {
                $this->clearOutput();
            }
            $this->renderException($exception);
        } catch (\Exception $e) {
            // an other exception could be thrown while displaying the exception
            $this->handleFallbackExceptionMessage($e, $exception);
        } catch (\Throwable $e) {
            // additional check for \Throwable introduced in PHP 7
            $this->handleFallbackExceptionMessage($e, $exception);
        }

        $this->exception = null;
    }

    /**
     * @param \Exception|\Throwable $exception
     * @param \Exception $previousException
     * @inheritdoc
     */
    protected function handleFallbackExceptionMessage($exception, $previousException): void
    {
        $msg = "An Error occurred while handling another error:\n";
        $msg .= (string) $exception;
        $msg .= "\nPrevious exception:\n";
        $msg .= (string) $previousException;
        if (YII_DEBUG) {
            echo '<pre>' . htmlspecialchars($msg, ENT_QUOTES, Yii::$app->charset) . '</pre>';
        } else {
            echo 'An internal server error occurred.';
        }
    }

    /**
     * @param int $code
     * @param string $message
     * @param string $file
     * @param int $line
     * @throws ErrorException
     * @inheritdoc
     */
    public function handleError($code, $message, $file, $line): bool
    {
        if (error_reporting() & $code) {
            $exception = new ErrorException($message, $code, $code, $file, $line);

            // in case error appeared in __toString method we can't throw any exception
            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            array_shift($trace);
            foreach ($trace as $frame) {
                if ($frame['function'] === '__toString') {
                    $this->handleException($exception);
                    return true;
                }
            }

            throw $exception;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function handleFatalError(): void
    {
        $error = error_get_last();

        if (ErrorException::isFatalError($error)) {
            $exception = new ErrorException($error['message'], $error['type'], $error['type'], $error['file'], $error['line']);
            $this->exception = $exception;

            $this->logException($exception);

            if ($this->discardExistingOutput) {
                $this->clearOutput();
            }
            $this->renderException($exception);
        }
    }

    /**
     * ob start after clear for workerman can get error response output
     * @inheritdoc
     */
    public function clearOutput(): void
    {
        parent::clearOutput();
        ob_start();
    }
}
