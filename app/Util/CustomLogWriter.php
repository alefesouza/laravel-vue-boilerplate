<?php

namespace App\Util;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Spatie\HttpLogger\LogWriter;
use Auth;
use App\User;

class CustomLogWriter implements LogWriter
{
    public function logRequest(Request $request)
    {
        $method = strtoupper($request->getMethod());

        $uri = $request->getPathInfo();

        $bodyAsJson = json_encode($request->except(config('http-logger.except')));

        $files = (new Collection(iterator_to_array($request->files)))
            ->map([$this, 'flatFiles'])
            ->flatten()
            ->implode(',');

        $userName = 'Guest';

        $user = $request->user();

        if (!empty($user)) {
            $userName = $user->name . ' - ID: ' . $user->id;
        }

        $referer = $request->headers->get('referer');
        $domain = $request->getHost();

        $message = "{$method} {$uri} - User: {$userName} - Domain: {$domain} - Referer: {$referer} - Body: {$bodyAsJson} - Files: ". $files;

        Log::info($message);
    }

    public function flatFiles($file)
    {
        if ($file instanceof UploadedFile) {
            return $file->getClientOriginalName();
        }
        if (is_array($file)) {
            return array_map([$this, 'flatFiles'], $file);
        }
        return (string) $file;
    }
}
