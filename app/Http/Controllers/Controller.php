<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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
