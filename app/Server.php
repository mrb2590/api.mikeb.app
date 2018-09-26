<?php

namespace App;

class Server
{
    /**
     * Get the size in a readable format.
     *
     * @return string   
     */
    public static function bytesToReadable($sizeInBytes)
    {
        $decimals = '2';
        $size = ['B','KB','MB','GB','TB','PB','EB','ZB','YB'];

        $factor = floor((strlen($sizeInBytes) - 1) / 3);

        $sizeReadable = sprintf("%.2f", $sizeInBytes / pow(1024, $factor));
        $sizeReadable .= ' '.@$size[$factor];

        return $sizeReadable;
    }

    /**
     * Get the total space on the server.
     *
     * @return array
     */
    public static function totalSpace()
    {
        $totalSpace = disk_total_space(storage_path());

        return [
            'bytes' => $totalSpace,
            'readable' => self::bytesToReadable($totalSpace)
        ];
    }

    /**
     * Get the total free space on the server.
     *
     * @return array
     */
    public static function freeSpace()
    {
        $freeSpace = disk_free_space(storage_path());

        return [
            'bytes' => $freeSpace,
            'readable' => self::bytesToReadable($freeSpace)
        ];
    }

    /**
     * Get the total used space on the server.
     *
     * @return array
     */
    public static function usedSpace()
    {
        $usedSpace = self::totalSpace()['bytes'] - self::freeSpace()['bytes'];

        return [
            'bytes' => $usedSpace,
            'readable' => self::bytesToReadable($usedSpace)
        ];
    }
}
