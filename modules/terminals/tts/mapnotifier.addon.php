<?php

class mapnotifier extends tts_addon {

    function __construct($terminal) {
        $this->title="MapNotifier API";
        parent::__construct($terminal);
    }

    function say($phrase, $level = 0)
    {
        //$name = $this->terminal['NAME'];
        $title = $this->terminal['TITLE'];
        $host = $this->terminal['HOST'];
        if ($host == 'mqtt') {
            $topic = $title . '/alarmpanel/command'; //$topic = $name."/alarmpanel/command";
            $topic2 = $title . '/home/alarm/sensor/spech'; // пишет в сенсор
            $status = '{"speak":"' .$phrase. '"}';
            
            include_once(DIR_MODULES . 'mqtt/mqtt.class.php');
            $mqtt = new mqtt();
            $rezult = $mqtt->mqttPublish($topic, $status,  0, 0);
            $rezult = $mqtt->mqttPublish($topic2, $phrase,  0, 0);
        } else {
            $port = $this->terminal['PLAYER_PORT'];
            $language = SETTINGS_SITE_LANGUAGE;
            if (!$port) {
                $port = '8091';
            }
            $url = 'http://' . $host . ':' . $port . '/google-home-notifier?language=' . $language . '&text=' . urlencode($phrase);
            getURL($url, 0);
        }
    }

}
