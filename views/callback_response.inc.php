<?php
/** @var ViewService $this */
use Recallback\Services\ViewService;

echo "===========".$this->variables['id']."]]s";

echo $_SERVER['REMOTE_ADDR']."<br>=========\n";

        $a = getallheaders();
        $debug_var = $a;echo '<pre>Debug of $a in '.__FILE__.':'.__LINE__.'<hr>';print_r($debug_var);echo '</pre>';

        echo print_r($_GET, true);
        echo print_r($_POST, true);

        echo "METHOD: ". $_SERVER['REQUEST_METHOD']."<br>";
        echo "QUER STRING".$_SERVER['QUERY_STRING']."<br>";

        // takes raw data from the request
        $phpInput = file_get_contents('php://input');
        // Converts it into a PHP object
        var_dump($phpInput);

?>

