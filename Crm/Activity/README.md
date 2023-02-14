# Как использовать классы пространства Activity

## Planer

#### Константы

- Planer::DIRECTION_INCOMING - Исходящий звонок
- Planer::DIRECTION_OUTGOING - Входящий звонок
- Planer::MINUTE_TEXT - Минуты
- Planer::HOURS_TEXT - Часы
- Planer::DAYS_TEXT - Дни

## Call

Инициализация классадля создания звонка
```
use GraDus59\Bitrix24\Crm\Activity\Call;
use GraDus59\Bitrix24\Crm\Activity\Planer;

$call = new Call();
```

Установка заголовка звонка
- Заголовок
- Описание
- От кого создаем
- Ответственный
```
$call->setHead('subject','description',1,1);
```

Установка выполненного звонка
```
$call->callCompleted();
```

Установка важности звонка
```
$call->callImportant();
```

Установка напоминания звонка
```
$notify = [ "VALUE" => 15, "TYPE" => Planer::MINUTE_TEXT ];
$call->setNotify($notify);
```

Установка типа звонка
```
$call->setDirection(Planer::DIRECTION_INCOMING);
```

Установка "С кем" с привязкой к компании или контакту
```
$call->setRelations(123);

$whith = [ 
    "ENTITY_NAME" => \CCrmOwnerType::CompanyName, 
    "ID" => 753 
];
$call->setRelations(123,$whith);
```
- Дата от и до, если не передавать ключ, встанет now()
- TO_USER если установлена то время будет не по серверу передано, а по настройкам из профиля
- Детальное указание диапазона
```
$call->add(
    [
        "START" => "", 
        "END" => ""
    ],
    [
        "VALUE" => 15, 
        "TYPE" => Planer::MINUTE_TEXT
    ]
);
```