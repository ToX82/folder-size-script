<?php
$nl = (php_sapi_name() == "cli") ? PHP_EOL : '<br>';
$disk_used = foldersize(realpath(dirname(__FILE__)), $nl, true);

// Echoes the total size in a human readable format.
echo ($nl . 'diskspace used: ' . format_size($disk_used) . $nl);

/**
 * Get folder size
 *
 * @param string $path Folder path
 * @param string $nl NewLine characters
 * @param boolean $firstLevel True only for the first call
 * @return int Folder size in bytes
 */
function foldersize($path, $nl, $firstLevel = false)
{
    $total_size = 0;
    $files = scandir($path);
    $cleanPath = rtrim($path, '/') . '/';

    foreach ($files as $t) {
        if ($t != "." && $t != "..") {
            $currentFile = $cleanPath . $t;
            if (is_dir($currentFile)) {
                $size = foldersize($currentFile, $nl);

                if ($firstLevel == true) {
                    echo ($currentFile . ': ' . format_size($size) . $nl);
                }
                $total_size += $size;
            } else {
                $size = @filesize($currentFile);
                $total_size += $size;
            }
        }
    }

    return $total_size;
}

/**
 * Fprmat size in bytes to human readable
 *
 * @param int $size Size in bytes
 * @return string Formatted size
 */
function format_size($size)
{
    $units = explode(' ', 'B KB MB GB TB PB');
    $mod = 1024;

    for ($i = 0; $size > $mod; $i++) {
        $size /= $mod;
    }

    $endIndex = strpos($size, ".") + 3;

    return substr($size, 0, $endIndex) . ' ' . $units[$i];
}
