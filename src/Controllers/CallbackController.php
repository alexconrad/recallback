<?php
declare(strict_types=1);

namespace Recallback\Controllers;

use Recallback\Services\CallbackService;
use Recallback\Services\ViewService;

class CallbackController {

    private CallbackService $callbackService;
    /**
     *
     */
    private ViewService $viewService;

    public function __construct(CallbackService $callbackService, ViewService $viewService)
    {
        $this->callbackService = $callbackService;
        $this->viewService = $viewService;
    }

    public function index() {

        $receivedDataId = $this->callbackService->saveReceivedData(
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['REQUEST_METHOD'] ?? null,
            getallheaders(),
            $_SERVER['QUERY_STRING'] ?? null,
            $_POST,
            file_get_contents('php://input'),
        );

        $this->viewService->assign('id', $receivedDataId);
        $this->viewService->display('callback_response.inc.php');

    }

}
