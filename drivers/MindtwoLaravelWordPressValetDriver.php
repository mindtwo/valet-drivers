<?php

class MindtwoLaravelWordPressValetDriver extends ValetDriver
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
        $wpDirExists = (is_dir($sitePath.'/public/blog/wp-systems') || is_dir($sitePath.'/public/blog/wp-system'));
        return is_dir($sitePath.'/bootstrap') && file_exists($sitePath.'/public/blog/wp-config.php') && $wpDirExists;
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
        foreach ([
            $sitePath.'/public'.$uri,
            $sitePath.'/public/blog/wp'.$uri,
            $sitePath.'/public/blog/wp-system'.$uri,
        ] as $staticFilePath) {
            if ($this->isActualFile($staticFilePath)) {
                return $staticFilePath;
            }
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
        $_SERVER['PHP_SELF'] = $uri;
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];

        if (strpos($uri, '/blog/wp/') === 0) {
            return is_dir($sitePath.'/public'.$uri)
                            ? $sitePath.'/public'.$this->forceTrailingSlash($uri).'/index.php'
                            : $sitePath.'/public'.$uri;
        }

        if (strpos($uri, '/blog/wp-system/') === 0) {
            return is_dir($sitePath.'/public'.$uri)
                ? $sitePath.'/public'.$this->forceTrailingSlash($uri).'/index.php'
                : $sitePath.'/public'.$uri;
        }

        if (strpos($uri, '/blog/wp-json/') === 0) {
            return $sitePath.'/public/blog/index.php';
        }

        if($uri !== '/' && file_exists($sitePath.'/public'.$uri)) {
            return $sitePath.'/public'.$uri;
        }

        return $sitePath.'/public/index.php';
    }

    /**
     * Redirect to uri with trailing slash.
     *
     * @param  string $uri
     * @return string
     */
    private function forceTrailingSlash($uri)
    {
        if (substr($uri, -1 * strlen('/blog/wp/wp-admin')) == '/blog/wp/wp-admin') {
            header('Location: '.$uri.'/'); die;
        }

        if (substr($uri, -1 * strlen('/blog/wp-system/wp-admin')) == '/blog/wp-system/wp-admin') {
            header('Location: '.$uri.'/'); die;
        }

        return $uri;
    }
}
