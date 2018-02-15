<?php

function css($file)
{
    return asset('css/'.$file);
}

function icon($file)
{
    return asset('images/icons/'.$file);
}

function js($file)
{
    return asset('js/'.$file);
}

function image($file)
{
    return asset('images/'.$file);
}

function error($value = 'errors.generic_error', $about = 'message')
{
    return [
        'errors' => [
            $about => [
                __($value),
            ],
        ],
        'message' => __('errors.generic_error'),
    ];
}
