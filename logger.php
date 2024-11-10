<?php

function logError($message)
{
    $logFile = __DIR__ . '/logs/error.log';
    $logMessage = date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}