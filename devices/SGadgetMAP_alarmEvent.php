<?php
/** SGadgetMAP_alarmEvent.php
 * 
 */
$ot = $this->object_title;
$topic = $this->getProperty('name');
SQLExec("UPDATE mqtt SET LINKED_OBJECT='" . $ot . "' WHERE PATH='" . $topic . "/home/alarm/set'"); 
SQLExec("UPDATE mqtt SET LINKED_PROPERTY='' WHERE PATH='" . $topic . "/home/alarm/set'"); 
SQLExec("UPDATE mqtt SET LINKED_METHOD='alarmSet' WHERE PATH='" . $topic . "/home/alarm/set'"); 


SQLExec("UPDATE mqtt SET LINKED_OBJECT='MobileMAP01', LINKED_PROPERTY='', LINKED_METHOD='alarmSet' WHERE PATH='SWM07E/alarmpanel/state/currentUrl'"); 

/*
LINKED_OBJECT      MobileMAP01
LINKED_PROPERTY    
LINKED_METHOD      alarmSet
TITLE   SWM07E/home/alarm/set
PATH    SWM07E/home/alarm/set

   $Record = SQLSelectOne("SELECT * FROM contacts WHERE ID='".$_POST['id']."'");
   $Record['FIRSTNAME'] = $_POST['firstname'];
   $Record['LASTNAME'] = $_POST['lastname'];
   $Record['EMAIL'] = $_POST['email'];
   SQLUpdateInsert('contacts', $Record);

SWM07E/alarmpanel
    SWM07E/alarmpanel/command    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    command    LINKED_METHOD
            SQLExec("UPDATE mqtt SET LINKED_OBJECT='". $ot ."', LINKED_PROPERTY='command' WHERE PATH='" . $topic . "/alarmpanel/command'"); 
        SWM07E/alarmpanel/command/alert
        SWM07E/alarmpanel/command/speak
        SWM07E/alarmpanel/command/audio
        SWM07E/alarmpanel/command/notification
        SWM07E/alarmpanel/command/temperature
        SWM07E/alarmpanel/command/forecast
        SWM07E/alarmpanel/command/condition
        SWM07E/alarmpanel/command/dashboard
        SWM07E/alarmpanel/command/sun
        SWM07E/alarmpanel/command/clearCache
        SWM07E/alarmpanel/command/relaunch
        SWM07E/alarmpanel/command/wake
        SWM07E/alarmpanel/command/humidity
        SWM07E/alarmpanel/command/weather
            SWM07E/alarmpanel/command/weather/condition
            SWM07E/alarmpanel/command/weather/forecast
            SWM07E/alarmpanel/command/weather/humidity
            SWM07E/alarmpanel/command/weather/temperature
        SWM07E/alarmpanel/sensor/battery    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    sensorsDetected
                SQLExec("UPDATE mqtt SET LINKED_OBJECT='". $ot ."', LINKED_METHOD='sensorsDetected' WHERE PATH='" . $topic . "/alarmpanel/sensor/battery'"); 
            SWM07E/alarmpanel/sensor/battery/acPlugged
            SWM07E/alarmpanel/sensor/battery/charging
            SWM07E/alarmpanel/sensor/battery/unit
            SWM07E/alarmpanel/sensor/battery/usbPlugged
            SWM07E/alarmpanel/sensor/battery/value
        SWM07E/alarmpanel/sensor/face    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    faceDetected
                SQLExec("UPDATE mqtt SET LINKED_OBJECT='". $ot ."', LINKED_METHOD='faceDetected WHERE PATH='" . $topic . "/alarmpanel/sensor/face'"); 
            SWM07E/alarmpanel/sensor/face/value
        SWM07E/alarmpanel/sensor/motion    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    motionDetected
                SQLExec("UPDATE mqtt SET LINKED_OBJECT='". $ot ."', LINKED_METHOD='motionDetected' WHERE PATH='" . $topic . "/alarmpanel/sensor/motion'"); 
            SWM07E/alarmpanel/sensor/motion/value
        SWM07E/alarmpanel/sensor/qrcode    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    qrcodeDetected
                SQLExec("UPDATE mqtt SET LINKED_OBJECT='". $ot ."', LINKED_METHOD='qrcodeDetected' WHERE PATH='" . $topic . "/alarmpanel/sensor/qrcode'"); 
            SWM07E/alarmpanel/sensor/qrcode/value
        SWM07E/alarmpanel/state    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    state
                SQLExec("UPDATE mqtt SET LINKED_OBJECT='". $ot ."', LINKED_METHOD='state' WHERE PATH='" . $topic . "/alarmpanel/state'"); 
            SWM07E/alarmpanel/state/brightness
            SWM07E/alarmpanel/state/currentUrl
            SWM07E/alarmpanel/state/presence
            SWM07E/alarmpanel/state/screenOn
    SWM07E/home/alarm
        SWM07E/home/alarm/event    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    alarmEvent    LINKED_METHOD
            SQLExec("UPDATE mqtt SET LINKED_OBJECT='". $ot ."', LINKED_PROPERTY='alarmEvent' WHERE PATH='" . $topic . "/home/alarm/event'"); 
        SWM07E/home/alarm/event/delay
        SWM07E/home/alarm/event/event
        SWM07E/home/alarm/event/state
    SWM07E/home/alarm/sensor
        SWM07E/home/alarm/sensor/face
        SWM07E/home/alarm/sensor/motion
        SWM07E/home/alarm/sensor/spech
    SWM07E/home/alarm/set    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    alarmSet
            SQLExec("UPDATE mqtt SET LINKED_OBJECT='". $ot ."', LINKED_METHOD='alarmSet' WHERE PATH='" . $topic . "/home/alarm/set'"); 
        SWM07E/home/alarm/set/code
        SWM07E/home/alarm/set/command
*/
