<?php
/**
 * Created by PhpStorm.
 * User: tsmith
 * Date: 2014-11-23 21:21 EST
 */

function sendAjaxError($errorMsg) {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => $errorMsg, 'context' => $_POST]);
    exit;
}


// Get the user input.
file_put_contents('../content/debug.txt', json_encode($_POST, JSON_PRETTY_PRINT));

$contentId = filter_input(INPUT_POST, 'contentId', FILTER_SANITIZE_STRIPPED);

if (!preg_match('/^[A-Za-z0-9_\-]+$/', $contentId)) {
    sendAjaxError('Invalid contentId: ' . $contentId);
}

$filename = "../content/$contentId.html";

if (!file_exists($filename)) {
    if (!is_writable('../content')) {
        sendAjaxError('Cannot create new files in the content directory.');
    }
}
else {
    if (!is_writable($filename)) {
        sendAjaxError("Cannot write to $filename.");
    }
}

file_put_contents($filename, $_POST['body']);
