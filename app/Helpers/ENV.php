<?php

namespace App\Helpers;

/**
 * Class ENV
 * @package App\Helpers
 */
class ENV
{
    /**
     * @param string $key
     * @param        $value
     */
    public static function putEnv(string $key, $value): void
    {
        $path = Project::getInstance()->getAppDirName() . '/.env';

        $content = file_get_contents($path);

        if (strpos($content, "{$key}=") !== false) {
            file_put_contents($path,
                str_replace($key . '=' . env($key), $key . '=' . $value, $content)
            );

            return;
        }

        file_put_contents($path, $content . $key . '=' . $value . "\n");
    }
}
