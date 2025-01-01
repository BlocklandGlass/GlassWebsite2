<?php

namespace App\Traits;

trait ZipTrait
{
    // https://pastebin.com/1dqbzAQx
    public function zip($source, $destination)
    {
        if (extension_loaded('zip') === false || file_exists($source) === false) {
            return false;
        }

        $zip = new \ZipArchive();
        if ($zip->open($destination, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === false) {
            return false;
        }

        $source = str_replace('\\', DIRECTORY_SEPARATOR, realpath($source));
        $source = str_replace('/', DIRECTORY_SEPARATOR, $source);

        if (is_dir($source) === true) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file) {
                $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
                $file = str_replace('/', DIRECTORY_SEPARATOR, $file);

                if ($file == '.' || $file == '..' || empty($file) || $file == DIRECTORY_SEPARATOR) {
                    continue;
                }

                // Ignore "." and ".." folders
                if (in_array(substr($file, strrpos($file, DIRECTORY_SEPARATOR) + 1), ['.', '..'])) {
                    continue;
                }

                $file = realpath($file);
                $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
                $file = str_replace('/', DIRECTORY_SEPARATOR, $file);

                if (is_dir($file) === true) {
                    $d = str_replace($source.DIRECTORY_SEPARATOR, '', $file);
                    if (empty($d)) {
                        continue;
                    }
                    $zip->addEmptyDir($d);
                } elseif (is_file($file) === true) {
                    $zip->addFromString(str_replace($source.DIRECTORY_SEPARATOR, '', $file), file_get_contents($file));
                } else {
                    // do nothing
                }
            }
        } elseif (is_file($source) === true) {
            $zip->addFromString(basename($source), file_get_contents($source));
        }

        return $zip->close();
    }
}
