<?php

class MindtwoVnrLaravelValetDriver extends ValetDriver
{
    /**
     * Determine if the driver serves the request.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return bool
     */
    public function serves($sitePath, $siteName, $uri)
    {
        return file_exists($sitePath.'/src/artisan') && file_exists($sitePath.'/src/public/index.php');
    }

        /**
     * Determine if the incoming request is for a static file.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string|false
     */
    public function isStaticFile($sitePath, $siteName, $uri)
    {
        if (file_exists($staticFilePath = $sitePath.'/src/public'.$uri)
           && is_file($staticFilePath)) {
            return $staticFilePath;
        }

        $storageUri = $uri;

        if (strpos($uri, '/src/storage/') === 0) {
            $storageUri = substr($uri, 8);
        }

        if ($this->isActualFile($storagePath = $sitePath.'/src/storage/app/public'.$storageUri)) {
            return $storagePath;
        }

        return false;
    }

    /**
     * Get the fully resolved path to the application's front controller.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string
     */
    public function frontControllerPath($sitePath, $siteName, $uri)
    {
        // Shortcut for getting the "local" hostname as the HTTP_HOST
        if (isset($_SERVER['HTTP_X_ORIGINAL_HOST'], $_SERVER['HTTP_X_FORWARDED_HOST'])) {
            $_SERVER['HTTP_HOST'] = $_SERVER['HTTP_X_FORWARDED_HOST'];
        }

        return $sitePath.'/src/public/index.php';
    }
}
