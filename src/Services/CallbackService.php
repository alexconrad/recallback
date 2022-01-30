<?php
declare(strict_types=1);

namespace Recallback\Services;

use JsonException;
use Recallback\Dao\CallbackDao;
use RuntimeException;

class CallbackService
{
    private CallbackDao $callbackDao;

    public function __construct(CallbackDao $callbackDao)
    {
        $this->callbackDao = $callbackDao;
    }

    public function saveReceivedData(
        ?string $ipAddress,
        ?string $requestMethod,
        array|false $requestHeaders,
        ?string $queryString,
        array $postData,
        string|false $phpInputData
    ): int
    {

        if ($ipAddress === null) {
            throw new RuntimeException('Invalid request - no ip !');
        }
        if ($requestMethod === null) {
            throw new RuntimeException('Invalid request - no method !');
        }

        if ($requestHeaders === false) {
            throw new RuntimeException('Invalid request - no headers !');
        }

        $inserInputData = null;
        if ($phpInputData !== false && $phpInputData !== '') {
            $inserInputData = $phpInputData;
        }

        try {
            return $this->callbackDao->insert(
                $ipAddress,
                $requestMethod,
                json_encode($requestHeaders, JSON_THROW_ON_ERROR),
                $queryString,
                json_encode($postData, JSON_THROW_ON_ERROR),
                $inserInputData,
            );
        } catch (JsonException $e) {
            throw new RuntimeException($e->getMessage());
        }

    }

    public function read()
    {
        $data = $this->callbackDao->read(0, 10);
        try {
            foreach ($data as $key => $value) {
                $data[$key]['headers'] = json_decode($value['headers'], true, 512, JSON_THROW_ON_ERROR);
                $data[$key]['post_data'] = json_decode($value['post_data'], true, 512, JSON_THROW_ON_ERROR);
            }
        } catch (JsonException $e) {
            throw new RuntimeException($e->getMessage());
        }
        return $data;
    }

}
