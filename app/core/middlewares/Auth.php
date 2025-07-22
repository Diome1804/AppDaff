<?php

namespace App\Core\Middlewares;

use App\Core\Session;

class Auth
{
    public function __invoke()
    {
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        if (!Session::isset('user')) {
            header('Location: /');  // ✅ Changé de /login à /
            exit();
        }


    }
}
