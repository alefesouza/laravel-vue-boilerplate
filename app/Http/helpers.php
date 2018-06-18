<?php

use Illuminate\Support\Facades\Auth;

function error($value = 'errors.generic_error', $about = 'message', $code = 500)
{
    return response()->json([
        'errors' => [
            $about => [
                __($value),
            ],
        ],
        'message' => __($value),
    ], $code);
}

function middlewareError()
{
    $code = Auth::guest() ? 401 : 403;
    $value = Auth::guest() ? 'errors.unauthorized' : 'errors.forbidden';

    return response()->json([
        'errors' => [
            'message' => [
                __($value),
            ],
        ],
        'message' => __($value),
    ], $code);
}
