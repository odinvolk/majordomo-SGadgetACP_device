<?php
/**
 * Summary of mqttPublish
 * 
 * mqttPublish($topic, $value, $qos = 0, $retain = 0);
 * 
 * @param mixed $topic Topic MQTT
 * @param mixed $value Param (default '')
 * @param mixed $qos (default 0)
 * @param mixed $retain (default 0)
 * @return $rezult
 */
function mqttMob($topic, $value = '', $qos = 0, $retain = 0)
{
    include_once(DIR_MODULES . 'mqtt/mqtt.class.php');
    $mqtt = new mqtt();
    $rezult = $mqtt->mqttPublish($topic, $value, $qos, $retain);
    return $rezult;
}
//----------------------------------------------------------------------------------

function iotMen($top, $room = '', $array = '', $qos = 0, $retain = 0)
{
    $json = json_encode($array, JSON_UNESCAPED_UNICODE); //
    $topic = "/".$top."/".$room."/config";// $topic = "/IoTmanager/Hall/".$ot."/status";
    include_once(DIR_MODULES . 'mqtt/mqtt.class.php');
    $mqtt = new mqtt();
    $rezult = $mqtt->mqttPublish($topic, $json, $qos, $retain);
    return $rezult;
}

function iotXmen($top, $room, $array = '', $qos = 0, $retain = 0)
{
    $json = json_encode($array, JSON_UNESCAPED_UNICODE); //$output = shell_exec('ls -lart');
    $topic = "/".$top."/".$room."/config";// $topic = "/IoTmanager/Hall/".$ot."/status";
    $exec = "mosquitto_pub -h localhost -u xbmc -P xbmc -t '$topic' -m '$json'";
    return exec($exec);
}
 /**
 * https://php.ru/manual/function.shell-exec.html
 * 
 *  $output = shell_exec('ls -lart');
 *  echo "<pre>$output</pre>";
 *  exec($cmd . " > /dev/null &");
 */
function iotXmenStatus($top, $room, $ot, $array = '', $qos = 0, $retain = 0)
{
    $json = json_encode($array, JSON_UNESCAPED_UNICODE); //$output = shell_exec('ls -lart');
    $topic = "/".$top."/".$room."/".$ot."/status";// $topic = "/IoTmanager/Kabinet/Tuya_relay11/status";
    $exec = "mosquitto_pub -h localhost -u xbmc -P xbmc -t '$topic' -m '$json'";
    return exec($exec . " > /dev/null &");
}
