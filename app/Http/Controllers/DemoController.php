<?php

namespace App\Http\Controllers;

use App\Helpers\ENV;
use App\Helpers\Project;
use Illuminate\Http\Request;

/**
 * Class DemoController
 * @package App\Http\Controllers
 */
class DemoController extends Controller
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function hello(Request $request): array
    {
//        $test = config(['app.test' => 'lllllll']);

        $path = Project::getInstance()->getAppDirName() . '/.env';

        $envKey = 'ZZZZZZ';
        $envValue = '1111111';

        ENV::putEnv($envKey, $envValue);
        dd(1);

        file_put_contents($path, str_replace(
            $envKey . '=' .env($envKey),
            $envKey .'='.$envValue, file_get_contents($path)
        ));



        return $request->all();
    }
}
