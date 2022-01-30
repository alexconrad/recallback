<?php
declare(strict_types=1);

namespace Recallback\Controllers;

use Recallback\Controllers\Globals\Variable;
use Recallback\Services\CallbackService;
use Recallback\Services\ViewService;

class WebController
{
    private Variable $variable;
    private ViewService $viewService;
    private CallbackService $callbackService;

    public function __construct(CallbackService $callbackService, Variable $variable, ViewService $viewService)
    {
        $this->variable = $variable;
        $this->viewService = $viewService;
        $this->callbackService = $callbackService;
    }

    public function index()
    {
        $data = $this->callbackService->read();
        $this->viewService->assign('data', $data);
        $this->viewService->display('index.inc.php');
    }

}
