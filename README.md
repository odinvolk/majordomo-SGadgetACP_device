# MQTT Alarm Control Panel

Идея рабочая. нужно протестировать и выявить недостатки.
Работает как простое устройство через MQTT модуль.
Поддержикает пароли, может работать без пароля.

Для начала нужно в параметре name прописать имя устройства такое же как на планшете например SWM07E
это будет корневым именем топика

Топики которые нужно привязывать в MQTT модуле

SWM07E/alarmpanel/command    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    command    LINKED_METHOD
SWM07E/alarmpanel/sensor/battery    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    sensorsDetected
SWM07E/alarmpanel/sensor/face    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    faceDetected
SWM07E/alarmpanel/sensor/motion    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    motionDetected
SWM07E/alarmpanel/sensor/qrcode    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    qrcodeDetected
SWM07E/alarmpanel/state    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    state
SWM07E/home/alarm/event    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    alarmEvent    LINKED_METHOD
SWM07E/home/alarm/set    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    LINKED_METHOD    alarmSet

Работают обратные статусы
статусы надо настроить в планшете

        SWM07E/home/alarm/sensor/face
        SWM07E/home/alarm/sensor/motion
        SWM07E/home/alarm/sensor/spech
Для того чтобы заработал spech вставить   Общие настройки -> Обработчики -> After SAY (code)

callMethod('MobileMAP01.command', array('ph'=>$ph,'level'=>$level));


