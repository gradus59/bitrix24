# Как пользоваться классами в пространстве имен Crm

## HIghLoad example

Инициализация объекта для работы с конкретныйм **HighLoad**
```
use GraDus59\Bitrix24\Crm\HighLoad;

$hbCountry = HighLoad::getClass()->classById(3);
$hbCity = HighLoad::getClass()->classByName("City");
```
Возвращает сам класс инициализированного объекта
```
$hbCity->getDataClass();
```

Возвращает имя в формате **ENTITY_ID**
```
$hbCity->getEntityId();
```

Отдает элемент по его ID
```
$hbCity->getById($id);
```

Отдает элемент по значению поля
```
$hbCity->getBy($field,$value);
```

Работает как ORM возвращая объект **Result**
```
$hbCity->getList($parameters);
```

Добавляет элемент возвращая **ID** или массив ошибок
```
$hbCity->add($fields);
```

Обновляет элемент возвращая **true** или массив ошибок
```
$hbCity->update(14223,$fields);
```

Удаляет элемент возвращая **true** или массив ошибок
```
$hbCity->delete(14223);
```
