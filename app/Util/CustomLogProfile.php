<?php

namespace App\Util;

use Illuminate\Http\Request;
use Spatie\HttpLogger\LogProfile;
use Auth;

class CustomLogProfile implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        if (Auth::guest() &&
          !empty($request->header('Authorization')) &&
          $request->getPathInfo() == '/graphql') {
          return false;
        }

        if (strpos($request->url(), 'broadcasting') !== false) {
          return false;
        }

        $content = $request->getContent();

        return in_array(strtolower($request->method()), ['post', 'put', 'patch', 'delete']);
    }
}
