<?php
declare(strict_types=1);

namespace Recallback\Dao;

use EasyMysql\EasyMysql;
use EasyMysql\Exceptions\EasyMysqlQueryException;
use RuntimeException;

class CallbackDao
{
    private EasyMysql $easyMysql;

    public function __construct(EasyMysql $easyMysql)
    {
        $this->easyMysql = $easyMysql;
    }

    public function insert(
        string $ipAddress,
        string $requestMethod,
        string $requestHeaders,
        ?string $queryString,
        string $postData,
        ?string $inputData
    ): int
    {
        $binds = [
            ':method' => $requestMethod,
            ':ipaddr' => $ipAddress,
            ':headers' => $requestHeaders,
            ':postData' => $postData
        ];

        if ($queryString !== null) {
            $binds[':queryString'] = $queryString;
        }

        if ($inputData !== null) {
            $binds[':inputData'] = $inputData;
        }

        $lastId = $this->easyMysql->insert('INSERT INTO received_data SET
        `dated` = NOW(6),
        `method` = :method,
        `ipaddr` = :ipaddr,
        `headers` = :headers,
        `query_string` = ' . ($queryString === null ? 'NULL' : ':queryString') . ',
        `post_data` = :postData,
        `input_data` = ' . ($inputData === null ? 'NULL' : ':inputData') . ';', $binds);


        if (((string)(int)$lastId === $lastId) && ($lastId > 0)) {
            return (int)$lastId;
        }

        throw new RuntimeException('Invalid autoincrement id ' . $lastId);

    }

    /**
     * @param int $start
     * @param int $howMany
     * @return array
     * @throws EasyMysqlQueryException
     */
    public function read(int $start, int $howMany): array
    {
        $query = "SELECT * FROM received_data ORDER BY id DESC LIMIT $start, $howMany";
        return $this->easyMysql->fetchAllAssociative($query, []);
    }

}
