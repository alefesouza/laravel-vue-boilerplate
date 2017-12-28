<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function makeError($value = 'errors.generic_error', $about = 'message') {
        return [
            'errors' => [
                $about => [
                    __($value),
                ],
            ],
            'message' => __('errors.generic_error'),
        ];
    }
}
