<?php
declare(strict_types=1);

namespace Recallback\Common;

use DI\Container;
use EasyMysql\Config;
use EasyMysql\Enum\MysqlDriverEnum;
use Exception;
use RuntimeException;

class Common
{

    public static function link(callable|array $where, array $extraData = []): string
    {
        $ret = (empty($_SERVER['HTTPS'] ?? null) ? 'http://' : 'https://') . ($_SERVER['HTTP_HOST'] ?? '') . '/index.php?a=' . $where[1];
        if (!empty($extraData)) {
            $ret .= '&' . http_build_query($extraData);
        }
        return $ret;
    }

    public static function escape($string, $ampBack = false): string
    {
        $escaped = htmlspecialchars((string)$string, ENT_QUOTES | ENT_HTML5);
        if ($ampBack) {
            return str_replace('&amp;', '&', $escaped);
        }
        return $escaped;
    }

    public static function is_list(array $list): bool
    {
        return (array_keys($list) === range(0, count($list) - 1));
    }

    /**
     * @return Container
     */
    public static function getDiContainer(): Container
    {
        $builder = new \DI\ContainerBuilder();
        $builder->addDefinitions([
            Config::class => \DI\create()->constructor(
                MysqlDriverEnum::PDO(),
                DB_HOST,
                DB_USER,
                DB_PASS,
                DB_NAME,
                DB_PORT
            )
        ]);
        try {
            return $builder->build();
        } catch (Exception $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

}
