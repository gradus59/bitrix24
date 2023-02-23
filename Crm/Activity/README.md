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

Установка файлов для звонка по id из диска
```
$call->setFiles([123,232]);
```

- Дата от и до, если не передавать ключ, встанет now()
- TO_USER если установлена то время будет не по серверу передано, а по настройкам из профиля
- Детальное указание диапазона
```
$call->setDate(
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

Добавить звонок
- Возвращает bool
```
$return = $call->add();
```

Получаем результат: массив данных или ошибок.
```
$result = $call->getResult();
```

Обновить элемент, передаем в ключах значения из таблицы b_crm_act
```
$result = $call->update($id,$data);
```

Удалить элемент
```
$result = $call->delete($id);
```

## CallList

Инициализация классадля создания звонка
```
use GraDus59\Bitrix24\Crm\Activity\CallList;
use GraDus59\Bitrix24\Crm\Activity\Planer;

$callList = new CallList();
```

Практически единственное отличие, тут указывается int типа привязки сущности,
а вторым параметром массив с id элементов
```
$callList->setRelations(\CCrmOwnerType::Company,[1,2,3]);
```

Установка заголовка звонка
- Заголовок
- Описание
- От кого создаем
- Ответственный
```
$callList->setHead('subject','description',1,1);
```

Установка выполненного звонка
```
$callList->callCompleted();
```

Установка важности звонка
```
$callList->callImportant();
```

Установка напоминания звонка
```
$notify = [ "VALUE" => 15, "TYPE" => Planer::MINUTE_TEXT ];
$callList->setNotify($notify);
```

Установка типа звонка
```
$callList->setDirection(Planer::DIRECTION_INCOMING);
```

- Дата от и до, если не передавать ключ, встанет now()
- TO_USER если установлена то время будет не по серверу передано, а по настройкам из профиля
- Детальное указание диапазона
```
$callList->setDate(
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

Добавить звонок
- Возвращает bool
```
$return = $callList->add();
```

Получаем результат: массив данных или ошибок.
```
$result = $callList->getResult();
```

Обновить элемент, передаем в ключах значения из таблицы b_crm_act
```
$result = $callList->update($id,$data);
```

Удалить элемент
```
$result = $callList->delete($id);
```