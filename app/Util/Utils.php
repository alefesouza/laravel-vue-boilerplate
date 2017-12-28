<?php

namespace App\Util;

class Utils
{
    public static function pathCombine()
    {
        $paths = func_get_args();

        return join(DIRECTORY_SEPARATOR, $paths);
    }

    public static function filterFile($path, $fileName)
    {
        $finalPath = self::pathCombine($path, $fileName);

        if (file_exists($finalPath)) {
            $date = date('d_m_Y__H_i_s');

            $extension = pathinfo($fileName)['extension'];

            $fileName = str_replace('.'.$extension, '', $fileName);

            $fileName .= '_'.$date.'.'.$extension;

            return $fileName;
        }

        return $fileName;
    }

    public static function getSettingsFile()
    {
        return base_path('storage/.settings');
    }
}
