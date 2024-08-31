<?php

namespace NGFramer\NGFramerPHPBase\defaults\actions;

use NGFramer\NGFramerPHPBase\action\Action;

class LogOnFile extends Action
{
    /**
     * Function to log the request.
     * @param string $message
     * @return void
     */
    public function execute(string $message = ''): void
    {
        $path = 'logs/log.txt';
        $log = fopen($path, 'a');
        fwrite($log, date('Y-m-d H:i:s') . ' @ ' . $_SERVER['REMOTE_ADDR'] . ' @ ' . $_SERVER['REQUEST_URI'] . ' @ ' . $message . PHP_EOL);
        fclose($log);
    }
}