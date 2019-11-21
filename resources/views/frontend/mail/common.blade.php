<?php
$count = 0;
$msg = "";

if (!empty($var) && count($data) > 1) {
    foreach ($data as $key => $item) {
        if (isset($var[$count]) && $key !== count($data) - 1) {
            $msg .= $item . $var[$count];
            $count++;
        } else {
            $msg .= $item;
        }
    }
}
else{
    $msg.= $data[0];
}

$msg = nl2br($msg);
$msg .= "<br>" . nl2br($signStore);
echo $msg;
?>
