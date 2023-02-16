# Как пользоваться классами в пространстве имен Storage

## HIghLoad example

Инициализация объекта для работы с конкретныйм **HighLoad**
```
use GraDus59\Bitrix24\Crm\HighLoad;

$hb_1 = HighLoad::getObject()->classById(3);
$hb_2 = HighLoad::getObject()->classByName("City");
```
Возвращает сам класс инициализированного объекта
```
$hb_2->getDataClass();
```

Возвращает имя в формате **ENTITY_ID**
```
$hb_2->getEntityId();
```

Отдает элемент по его ID
```
$hb_2->getById($id);
```

Отдает элемент по значению поля
```
$hb_2->getBy($field,$value);
```

Работает как ORM возвращая объект **Result**
```
$hb_2->getList($parameters);
```

Добавляет элемент возвращая **ID** или массив ошибок
```
$hb_2->add($fields);
```

Обновляет элемент возвращая **true** или массив ошибок
```
$hb_2->update(14223,$fields);
```

Удаляет элемент возвращая **true** или массив ошибок
```
$hb_2->delete(14223);
```

## Csv example

Инициализация объекта Csv
```
use GraDus59\Bitrix24\Storage\Csv;

$obCsv = Csv::getInstance();
```

Стандартный разделитель - ';', чтобы сменить:
```
$obCsv->setSeparator("|");
```

Заменяет заголовки на новые имена, допустимо указать не полный набор алиасов:
```
$obCsv->headAliases(["id" => "ID", "test" => "UF_TEST"]);
```

Метод включает первую строку файла для чтения и не ставит ее в качестве 
ключей для всех остальных строк, если метод не вызывать вернет чтение со 2й строки
и ключами.
```
$obCsv->notSetHeader();
```

Стандартный разделитель - ';', чтобы сменить:
```
$path = $_SERVER['DOCUMENT_ROOT'] . "/upload/csv_import/test.csv";
$obCsv->read($path);
```

Выдать следующую строку
```
$obCsv->Next();
```