<?php
declare(strict_types=1);

namespace Recallback\Controllers\Globals;

class Variable
{

    public function post($key)
    {
        return $_POST[$key] ?? null;
    }

    public function get($key)
    {
        return $_GET[$key] ?? null;
    }


}
