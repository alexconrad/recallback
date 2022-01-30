<?php
declare(strict_types=1);

namespace Recallback\Services;


class ViewService
{

    private array $variables = [];

    public function assign($key, $value): void {
        $this->variables[$key] = $value;
    }

    public function display($file): void
    {
        /** @noinspection PhpIncludeInspection */
        require_once VIEWS_DIR.'/'.$file;
    }

    public function print(string $string): void
    {
        echo $string;
    }

}
