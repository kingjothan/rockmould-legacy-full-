<?php

use App\Http\Middleware\EnsureRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => EnsureRole::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            if ($request->is('portal') || $request->is('portal/*')) {
                return route('portal.login');
            }

            return null;
        });

        $middleware->redirectUsersTo(function (Request $request) {
            $user = $request->user();

            if ($request->is('portal') || $request->is('portal/*')) {
                if ($user?->role === 'client') {
                    return route('portal.dashboard');
                }

                if ($user?->isAdminRole()) {
                    return route('admin.dashboard');
                }

                return route('home');
            }

            if ($request->is('admin') || $request->is('admin/*')) {
                if ($user?->isAdminRole()) {
                    return route('admin.dashboard');
                }

                if ($user?->role === 'client') {
                    return route('portal.dashboard');
                }

                return route('home');
            }

            if ($user?->isAdminRole()) {
                return route('admin.dashboard');
            }

            if ($user?->role === 'client') {
                return route('portal.dashboard');
            }

            return route('home');
        });

    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (PostTooLargeException $exception, Request $request) {
            $message = 'The uploaded media is larger than the current server limit. Upload files up to 200MB each, and if this appears again restart Apache/PHP so the project upload settings can load.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 413);
            }

            return redirect()
                ->back()
                ->withInput($request->except(['asset', 'thumbnail', 'media_uploads', 'cover_image_upload', 'hero_media', 'feature_image', 'story_image', 'image_upload', 'cover_image_upload']))
                ->withErrors(['media_upload' => $message]);
        });
    })->create();
