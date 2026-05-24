<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $status = 500;
                $message = 'Đã xảy ra lỗi máy chủ nội bộ. Vui lòng thử lại sau.';
                $errors = null;

                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    $status = 422;
                    $message = 'Dữ liệu không hợp lệ.';
                    $errors = $e->errors();
                } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    $status = 404;
                    $message = 'Không tìm thấy trang hoặc tài nguyên yêu cầu.';
                } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                    $status = 405;
                    $message = 'Phương thức HTTP không được hỗ trợ cho route này.';
                } elseif ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    $status = 401;
                    $message = 'Vui lòng đăng nhập để tiếp tục.';
                } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedException || $e instanceof \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException) {
                    $status = 403;
                    $message = 'Bạn không có quyền thực hiện hành động này.';
                } else {
                    if (config('app.debug')) {
                        $message = $e->getMessage();
                        $errors = [
                            'exception' => get_class($e),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                            'trace' => collect($e->getTrace())->take(5),
                        ];
                    }
                }

                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => $errors,
                ], $status);
            }
        });
    })->create();
