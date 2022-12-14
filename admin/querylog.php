<?php
    function printquery(){
        $file = fopen('querylog.json', 'r') or die('Unable to open file!');
        $logs = fread($file, filesize('querylog.json'));
        $logs = json_decode($logs, true);
        fclose($file);
        return $logs;
    } 

    function storequery($query){
        $file = fopen("querylog.json", "r") or die("Unable to open file!");
        $logs = fread($file, filesize("querylog.json"));
        $logs = json_decode($logs, true);
        fclose($file);
        date_default_timezone_set('Asia/Kolkata');
        $now = new DateTime();
        $logs[$now -> format('Y-m-d H:i:s:v')] = $query;
        $logs = json_encode($logs);
        $file = fopen("querylog.json", "w") or die("Unable to open file!");
        fwrite($file, $logs.PHP_EOL);
        fclose($file);
        return json_decode($logs, true);
    }
?>