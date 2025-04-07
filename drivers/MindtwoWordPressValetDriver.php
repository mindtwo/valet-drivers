<?php

namespace Valet\Drivers\Custom;

use Valet\Drivers\ValetDriver;

class MindtwoWordPressValetDriver extends ValetDriver
{
    /**
     * Determine if the driver serves the request.
     */
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        $wpDirExists = (is_dir($sitePath.'/public/wp') || is_dir($sitePath.'/public/wp-system'));

        return is_dir($sitePath.'/bootstrap') && file_exists($sitePath.'/public/wp-config.php') && $wpDirExists;
    }

    /**
     * Determine if the incoming request is for a static file.
     *
     * @return string|false
     */
    public function isStaticFile(string $sitePath, string $siteName, string $uri)/*: string|false */
    {
        foreach ([
            $sitePath.'/public'.$uri,
            $sitePath.'/public/wp'.$uri,
            $sitePath.'/public/wp-system'.$uri,
        ] as $staticFilePath) {
            if ($this->isActualFile($staticFilePath)) {
                return $staticFilePath;
            }
        }

        return false;
    }

    /**
     * Get the fully resolved path to the application's front controller.
     */
    public function frontControllerPath(string $sitePath, string $siteName, string $uri): ?string
    {
        $_SERVER['PHP_SELF'] = $uri;
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];

        if (strpos($uri, '/wp/') === 0) {
            return is_dir($sitePath.'/public'.$uri)
                            ? $sitePath.'/public'.$this->forceTrailingSlash($uri).'/index.php'
                            : $sitePath.'/public'.$uri;
        }

        if (strpos($uri, '/wp-system/') === 0) {
            return is_dir($sitePath.'/public'.$uri)
                ? $sitePath.'/public'.$this->forceTrailingSlash($uri).'/index.php'
                : $sitePath.'/public'.$uri;
        }

        if (strpos($uri, '/lumen/') === 0) {
            return $sitePath.'/public/lumen/index.php';
        }

        if (strpos($uri, '/api/') === 0) {
            return $sitePath.'/public/api/index.php';
        }

        if ($uri !== '/' && file_exists($sitePath.'/public'.$uri)) {
            return $sitePath.'/public'.$uri;
        }

        return $sitePath.'/public/index.php';
    }

    /**
     * Redirect to uri with trailing slash.
     *
     * @param  string  $uri
     * @return string
     */
    private function forceTrailingSlash($uri)
    {
        if (substr($uri, -1 * strlen('/wp/wp-admin')) == '/wp/wp-admin') {
            header('Location: '.$uri.'/');
            exit;
        }

        if (substr($uri, -1 * strlen('/wp-system/wp-admin')) == '/wp-system/wp-admin') {
            header('Location: '.$uri.'/');
            exit;
        }

        return $uri;
    }
}
