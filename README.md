# MQTT Alarm Control Panel

Идея рабочая. Пока нет виджета и переводов, нужно протестировать и выявить недостатки.

Как настроить планшет [описано здесь](https://github.com/thanksmister/android-mqtt-alarm-panel#mqtt-alarm-panel-for-home-automation-platforms), там же можно найти релиз программы

Работает как простое устройство через MQTT модуль.

Поддерживает пароли, может работать без пароля.

После установки модуля в простых устройствах добавить новое устройство  

Тип() Gadget MAP 

Создаем новое устройство, появится объект вида MobileMAP01 

в параметре Имя устройства (пре топик) прописать имя устройства такое же как на планшете например SWM07E
это будет корневым именем топика

Топики которые нужно привязывать в MQTT модуле

        SWM07E/alarmpanel/command          LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    command       LINKED_METHOD
        SWM07E/alarmpanel/sensor/battery   LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY                  LINKED_METHOD    sensorsDetected
        SWM07E/alarmpanel/sensor/face      LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY                  LINKED_METHOD    faceDetected
        SWM07E/alarmpanel/sensor/motion    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY                  LINKED_METHOD    motionDetected
        SWM07E/alarmpanel/sensor/qrcode    LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY                  LINKED_METHOD    qrcodeDetected
        SWM07E/alarmpanel/state            LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY                  LINKED_METHOD    state
        SWM07E/home/alarm/event            LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY    alarmEvent    LINKED_METHOD
        SWM07E/home/alarm/set              LINKED_OBJECT    MobileMAP01    LINKED_PROPERTY                  LINKED_METHOD    alarmSet

Работают обратные статусы
статусы надо настроить в планшете

        SWM07E/home/alarm/sensor/face
        SWM07E/home/alarm/sensor/motion
        SWM07E/home/alarm/sensor/spech
        
Для того чтобы заработал spech вставить в   

Общие настройки -> Обработчики -> After SAY (code)

        callMethod('MobileMAP01.command', array('ph'=>$ph,'level'=>$level));



