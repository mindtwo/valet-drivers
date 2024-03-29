<?php

namespace Valet\Drivers\Custom;

use Valet\Drivers\ValetDriver;

class MindtwoAwsBedrockValetDriver extends ValetDriver
{
    /**
     * Determine if the driver serves the request.
     */
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        return file_exists($sitePath.'/src/web/index.php') && file_exists($sitePath.'/src/web/wp-config.php');
    }

    /**
     * Determine if the incoming request is for a static file.
     *
     * @return string|false
     */
    public function isStaticFile(string $sitePath, string $siteName, string $uri)/*: string|false */
    {
        $staticFilePath = $sitePath.'/src/web'.$uri;

        if ($this->isActualFile($staticFilePath)) {
            return $staticFilePath;
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
            return is_dir($sitePath.'/src/web'.$uri)
                            ? $sitePath.'/src/web'.$this->forceTrailingSlash($uri).'/index.php'
                            : $sitePath.'/src/web'.$uri;
        }

        if ($uri !== '/' && file_exists($sitePath.'/src/web'.$uri)) {
            return $sitePath.'/src/web'.$uri;
        }

        return $sitePath.'/src/web/index.php';
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

        return $uri;
    }
}
