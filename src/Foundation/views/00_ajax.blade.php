<?php
$final = View::getSections();

$shared = View::getShared();
if (array_key_exists('title', $shared)) {
    $final['title'] = $shared['title'];
}

header('Content-Type: application/json');
echo json_encode($final);
exit;
