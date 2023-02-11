# Как пользоваться классами в пространстве имен Crm

## Smart example

Инициализация объекта для работы с конкретныйм **Smart-процессом**
```
use GraDus59\Bitrix24\Crm\Smart;

$smart_1 = Smart::getObject()->classById(3);
$smart_2 = Smart::getObject()->classByEntityTypeId(159);
$smart_3 = Smart::getObject()->classByCode('TEST');
```

Возвращает фабрику, через которую осуществляется работа
```
$smart_2->getFactory();
```

Возвращает имя в формате **ENTITY_ID**
```
$smart_2->getEntityId();
```

Вернет информацию о полях таблицы
```
$smart_2->getFieldsInfo();
```

Отдает элемент по его ID
```
$smart_2->getById($id);
```

Отдает элемент по значению поля
```
$smart_2->getBy($field,$value);
```

Работает как ORM возвращая объект
```
$obResult = $smart_2->getList($parameters);
$oneItem = $obResult->fetch();
$allItems = $obResult->fetchAll();
$countItems = $obResult->getCount();
```

Получить количество элементов в смарте, по фильтру и без него
```
$smart_2->getCount($fields);
```

Добавляет элемент возвращая **ID** или массив ошибок
```
$smart_2->add($fields);
```

Обновляет элемент возвращая **true** или массив ошибок
```
$smart_2->update(14223,$fields);
```

Удаляет элемент возвращая **true** или массив ошибок
```
$smart_2->delete(14223);
```

Пересоздает индекс для элемента с указанным id
```
$smart_2->createIndex(133);
```

Общий метод обращается к таблице с типами смартов
```
$smart_2->getTypeList($parameters);
```