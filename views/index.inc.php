<?php
/** @var ViewService $this */

use Recallback\Common\Common;
use Recallback\Services\ViewService;
use Recallback\Controllers\WebController;

$data = $this->variables['data'];
$this->display('_head.inc.php');

$queryStringMaxLength = 50;

?>

<style>
    * {
        margin: 0;
    }

    html, body {
        height: 100%;
    }

    .page-wrap {
        min-height: 100%;
        /* equal to footer height */
        margin-bottom: -160px;
    }

    .page-wrap:after {
        content: "";
        display: block;
    }

    .site-footer, .page-wrap:after {
        height: 160px;
    }

    .site-footer {

    }

    .forPost {
        background-color: rgb(24, 111, 175) !important;
    }

    .forGet {
        background-color: rgb(47, 129, 50) !important;
    }

    .forPut {
        background-color: rgb(149, 80, 124) !important;
    }

    .methodBox {
        width: 9ex;
        display: inline-block;
        height: 13px;
        line-height: 13px;
        background-color: rgb(51, 51, 51);
        border-radius: 3px;
        background-repeat: no-repeat;
        background-position: 6px 4px;
        font-size: 7px;
        font-family: Verdana, sans-serif;
        color: white;
        text-transform: uppercase;
        text-align: center;
        font-weight: bold;
        vertical-align: middle;
        margin-right: 6px;
        margin-top: 2px;
        box-sizing: border-box;
        -webkit-tap-highlight-color: rgba(255, 255, 255, 0);
    }

    .headerShowBox {
        width: 100px;
        display: inline-block;
        height: 13px;
        line-height: 13px;
        background-color: #17a2b8;
        border-radius: 3px;
        background-repeat: no-repeat;
        background-position: 6px 4px;
        font-size: 7px;
        font-family: Verdana, sans-serif;
        color: white;
        text-transform: uppercase;
        text-align: center;
        font-weight: bold;
        vertical-align: middle;
        margin-right: 6px;
        margin-top: 2px;
        box-sizing: border-box;
        -webkit-tap-highlight-color: rgba(255, 255, 255, 0);
    }

    .preStyle {
        white-space: pre;
        padding: 8px;
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, Liberation Mono, Courier New, monospace;
        font-size: 11px;
    }

    .noWhite {
        white-space: normal !important;
    }


</style>
<body style="padding-top: 20px;">
<script>
    <?php  if (isset($_GET['rk'])) { ?>
    $(document).ready(function () {

    });
    <?php } ?>

</script>
<div class="page-wrap">
    <div class="container justify-content-center">
        <div class="row justify-content-center">
            <h3><img src="/assets/recallback.svg" alt="call" width="40">Re-Callback<img src="assets/recallback.svg" alt="call"
                                                                                        width="60" style="display: none;"></h3>
        </div>
    </div>

    <center>
    <div style="width: 600px">
        <div class="input-group">
            <input id="callbackUrl" type="text" class="form-control" placeholder="URL to recallback to" aria-label="Recipient's username" aria-describedby="basic-addon2" value="<?php
                echo $_COOKIE['callbackUrl'] ?? '';
            ?>">
            <div class="input-group-append">
                <button class="btn btn-outline-primary btn-sm" type="button" onclick="setCookie('callbackUrl', $('#callbackUrl').val(), 100);return true;" data-toggle="modal" data-target="#savedToCookie">Save</button>
            </div>
        </div>
    </div>
    </center>
    <br>

    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th scope="col" class="w-1">#</th>
            <th scope="col">From IP</th>
            <th scope="col">Method</th>
            <th scope="col">Request Headers</th>
            <th scope="col">QueryString</th>
            <th scope="col">PostData</th>
            <th scope="col">&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $row) {

            $methodCssClass = '';
            if ($row['method'] === 'GET') {
                $methodCssClass = 'forGet';
            }
            if ($row['method'] === 'POST') {
                $methodCssClass = 'forPost';
            }
            if ($row['method'] === 'PUT') {
                $methodCssClass = 'forPut';
            }

            ?>
            <tr>
                <th scope="row" style="width: 20px;"><?php echo $row['id']; ?></th>
                <td style="width: 120px;"><?php echo $row['ipaddr']; ?></td>
                <td style="width: 50px;">
                <span class="btn btn-<?php echo $row['method'] === 'GET' ? 'success':'info'; ?> btn-sm">
                <?php echo $row['method']; ?></span>
                </td>
                <td>
                    <span class="headerShowBox" type="button" data-toggle="collapse" data-target="#headersFor<?php echo $row['id']; ?>"
                          aria-expanded="false" aria-controls="headersFor">
                    show headers
                    </span>
                    <span class="preStyle">&nbsp;</span>
                    <div class="collapse" id="headersFor<?php echo $row['id']; ?>">
                        <div class="card card-body preStyle"><?php
                            foreach ($row['headers'] as $header => $value) {
                                echo Common::escape($header) . ':' . Common::escape($value) . "<br>";
                            }
                            ?></div>
                    </div>
                </td>
                <td>
                    <?php
                    if ($row['query_string'] !== null) {
                        $length = mb_strlen($row['query_string']);
                        $show = Common::escape($row['query_string']);
                        parse_str($row['query_string'], $array);
                        ?>
                        <span class="headerShowBox" type="button" data-toggle="collapse"
                              data-target="#queryStringFor<?php echo $row['id']; ?>"
                              aria-expanded="false" aria-controls="headersFor">
                        show parsed
                        </span>
                        <span class="preStyle">?<?php
                            if ($length > $queryStringMaxLength) {
                                echo mb_substr($show, 0, $queryStringMaxLength) . ' <span style="font-weight:bold;font-size: 10px;"> ...(more)</span>';
                            } else {
                                echo $show;
                            }
                            ?>
                        </span>
                        <div class="collapse" id="queryStringFor<?php echo $row['id']; ?>">
                            <div class="card card-body preStyle"><?php
                                echo Common::escape(print_r($array, true));
                                ?></div>
                        </div>
                    <?php } else {
                        ?><span style="color: #d0d0d0;">n/a</span><?php
                    }
                    ?>
                </td>
                <td>
                    <?php

                    if ($row['input_data'] !== null) {
                    $length = mb_strlen($row['input_data']);
                    $show = Common::escape($row['input_data']);
                    parse_str($row['input_data'], $result);
                    ?>
                    <span class="headerShowBox" type="button" data-toggle="collapse"
                          data-target="#dataFor<?php echo $row['id']; ?>"
                          aria-expanded="false" aria-controls="headersFor">
                        show parsed
                        </span>
                    <?php
                    if ((mb_strlen($row['input_data']) > 2) && count($row['post_data']) === 0) {
                        try {
                            $result = json_decode($row['input_data'], true, 512, JSON_THROW_ON_ERROR);
                        } catch (JsonException $e) {

                        }
                    }

                    ?><span class="preStyle noWhite"><?php
                        if ($length > $queryStringMaxLength) {
                            echo mb_substr($show, 0, $queryStringMaxLength) . '<span style="font-weight:bold;font-size: 10px;"> ...(more)</span>';
                        } else {
                            echo $show;
                        }
                        echo '</span>';
                        ?>
                        <div class="collapse" id="dataFor<?php echo $row['id']; ?>">
                            <div class="card card-body preStyle"><?php
                                echo Common::escape(print_r($result, true));
                                ?></div>
                        </div>
                    <?php } else {
                        ?><span style="color: #d0d0d0;">n/a</span><?php
                    }
                    ?>

                </td>
                <Td>
                    <button type="button" class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target="#modalFor<?php echo $row['id']; ?>">

  <img src="/assets/resend.svg" width="20">ReSend
</button>
                </Td>
            </tr>
        <?php } ?>


        </tbody>
    </table>

<div class="modal fade" id="savedToCookie" tabindex="-1" role="dialog" aria-labelledby="savedToCookie" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="savedToCookie">Saved !</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        The URL was saved in COOKIES for future use.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


    <?php
foreach ($data as $row) {
    ?>
<div class="modal fade" id="modalFor<?php echo $row['id'];?>" tabindex="-1" role="dialog" aria-labelledby="modalFor<?php echo $row['id'];?>" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalFor<?php echo $row['id'];?>"><?php echo $row['method'] ?> http request</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php
    if ($row['method'] === 'GET') {
        ?>
        <a id="getRecallback<?php echo $row['id']?>" href="?<?php
            echo Common::escape($row['query_string'], true);
        ?>" target="_blank"><span id="url<?php echo $row['id']; ?>"></span>?<?php

            $length = mb_strlen($row['query_string']);
            $show = Common::escape($row['query_string']);
            if ($length > $queryStringMaxLength) {
                echo mb_substr($show, 0, $queryStringMaxLength) . '<span style="font-weight:bold;font-size: 10px;"> ...(more)</span>';
            } else {
                echo $show;
            }
        ?></a>

        <?php
    }
    if ($row['method'] === 'POST') {
        $possible = false;

        if ((mb_strlen($row['input_data']) > 2) && count($row['post_data']) === 0) {
            echo 'ReCallback is not possible for this request, at least for the moment.';
        }else{
            //
            $result = [
                'a' => 'a',
                'b' => [1,2,3],
                'c' => [
                    'cc' => [1,2,3],
                    'dd' => [
                            'cdda'=> 'asd',
                            'cddb' => [1,2,2 => ['hello'=>'world']]
                    ]
                ],
                'x' => [1,2,3],
                'z' => 'zz',
            ];
            //parse_str($row['input_data'], $result);
            $inputs = make_inputs(null, $result);
            ?>
            <form id="postRecallback<?php echo $row['id']; ?>" action="<?php
            if (!empty($row['query_string'])) {
                echo '?'.Common::escape($row['query_string'], true);
            }
            ?>" method="POST" enctype="application/x-www-form-urlencoded" target="_blank"><?php
             foreach ($inputs as $input) {
                 ?><input type="hidden" name="<?php echo Common::escape($input[0]);?>" value="<?php echo Common::escape($input[1]);?>">
                 <?php
             }

             ?>
             <center>
             <input type="submit" class="btn btn-primary" value="Submit POST to recallback URL">
             </center>
             </form>

            <?php
            /*
            echo '<pre>';
            print_r($result);
            echo "\n====\n";
            print_r($hello);
            echo '</pre>';
            */

        }
        ?>
        <?php
    }

 ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
$('#exampleModal<?php echo $row['id'];?>').on('show.bs.modal', function (event) {
  //var button = $(event.relatedTarget) // Button that triggered the modal
  //var recipient = button.data('whatever') // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

  <?php if ($row['method'] === 'GET') { ?>
    $('#getRecallback<?php echo $row['id'];?>').prop('href',$('#callbackUrl').val()+'?'+'<?php echo Common::escape($row['query_string'], true); ?>');
    $('#url<?php echo $row['id'];?>').text($('#callbackUrl').val());
   <?php } ?>
   <?php if ($row['method'] === 'POST') { ?>
    $('#postRecallback<?php echo $row['id'];?>').prop('action', $('#callbackUrl').val()+'?'+'<?php echo Common::escape($row['query_string'], true); ?>');
   <?php } ?>

})

    </script>


    <?php
}


function make_inputs(?string $forKey, array $data) {
    $isList = Common::is_list($data);
    $hasBrackets = ($forKey !== null);
    $ret = [];
    foreach ($data as $key=>$value) {
        if (is_array($value)) {
            /** @noinspection SlowArrayOperationsInLoopInspection */
            $ret = array_merge($ret, make_inputs($forKey.($hasBrackets?'[':'').($isList?'':$key).($hasBrackets?']':''), $value));
        }else{
            $ret[] = [($forKey??'').($hasBrackets?'[':'').($isList?'':$key).($hasBrackets?']':'') , (string)$value];
        }
    }
    return $ret;
}


?>
</div>
<script>
        new ClipboardJS('.btn');
</script>


<footer class="site-footer text-center" style="padding: 15px;">

 <div class="card" style="margin: 0px 0px 10px 0px;border-width: 0;background: #f5f5f5">
        <div class="card-body">
            <div class="input-group mb-3">

                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon3" style="font-size: 20px;">
                        <A href="https://github.com/alexconrad/recallback">
                <svg height="32" class="octicon octicon-mark-github" viewBox="0 0 16 16" version="1.1" width="32" aria-hidden="true"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0016 8c0-4.42-3.58-8-8-8z"></path></svg>
                        </A>
                    </span>
                </div>


                <div class="input-group-prepend">
                    <span class="input-group-text" id="basic-addon3" style="font-size: 20px;">
                        URL to set for callback:
                        </A>
                    </span>
                </div>


                <input type="text" class="form-control" id="roomKey" aria-describedby="basic-addon3"
                       style="width:200px;height: 60px;font-size: 18px;text-align: center;"
                       value="<?php
                echo 'https://'.$_SERVER['HTTP_HOST'].'/callback.php';
            ?>">
                <div class="input-group-append">
    <span class="input-group-text">
                    <button class="btn" data-clipboard-target="#roomKey">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
     stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path
            d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4"
                                                                                                      rx="1" ry="1"></rect></svg>
</button>
    </span>

                </div>
            </div>
        </div>
    </div>
</footer>
</body>
