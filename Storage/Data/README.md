# Справка по Storage\Data

## BaseChange
Реализует простые повседневные операции в чуть более явном описании

Инициализация
```
// DC - data change for example
use GraDus59\Bitrix24\Storage\Data\BaseChange as DC;
```

- Превращаем в массив строку или объект
- Работает и с объектами втч многоуроовеневыми
- Если получит не объект и не строку, просто вернет обратно
- Если разделитель строки - ';' можем не указывать его вторым параметром
```
$ob = (object)[1,[2]];
DC::toArray($ob);

$string1 = "1|2|3";
DC::toArray($string1,';');

$string2 = "1|;2;3";
DC::toArray($string2);
```

- Массив в строку
- Если получит не массив, просто вернет обратно
- 
```
DC::toString([1,2,3],';');
// 1;2;3
```

Проверка подстроки
```
DC::inString('qwe','q');
// true
```

Обрезка строки или массива строк
```
DC::trimVal(' 1 ');
DC::trimVal([' 1 ']);
```

Добавить префикс пользователя
```
DC::putUserPrefix(3);
DC::putUserPrefix('user_3');
// user_3
```

Убрать префикс пользователя
```
DC::removeUserPrefix('user_3');
// 3
```

Преобразовать в bool или в свое значение bool

Специальные значения:
- Числа: 0,1
- Символы: 'N','Y','0','1'
- Строки: 'true','false','TRUE','FALSE','No','Нет','Yes','Да'
- Bool: true,false
- Пустоты(false): 0.00, array(), null, ""

Остальные значения будут обработаны как стандартное преобразование в bool тип
```
DC::toBool('N',['Ага','Неа']);
// Неа

DC::toBool('N');
// false
```

Из строки пространства имен вернет последнее значение, если передаем класс то и получаем чистое имя класса, 
для этого и создан
```
namespace GraDus59\Bitrix24\Storage\Data;
class BaseChange{}
DC::getClassNameByNamespace(__CLASS__);
// BaseChange
```

Вспомогалка, для явного чтения что берется тип сущности и получается id из строки
```
DC::getIdByEntityId('CRM_1');
// 1
```

Немного измененный **array_shift**(&$array)
- Тоже принимаем ссылку
- Если не передать второй параметр, то отработает стандартный shift
- Если передаем второй параметр, то можем вытащить значение любого элемента по ключу

**shiftArrayItems** вернет массив значений работает на базе 1 элемента, 
но не использует стандартный shift

Методы не получают, а перекладывают значения.
```
$array = [1,2,3,4,'five' => 5];
DC::shiftArrayItem($array);
// 1
DC::shiftArrayItem($array,'five')
// 5

DC::shiftArrayItems($array,[0,1]);
// [1,2]
```

## Find 

Ответвление data change, класса BaseChange, реализует методы связанные с поиском

Инициализация
```
// DCFind - data change for example
use GraDus59\Bitrix24\Storage\Data\Find as DCFind;
```

Достать из строки подстроку лежащую между символами

**Быть аккуратнее, так как метод может вместо символов и выражение принять**
```
$string = "I'm are busy[so-so] human.";
DCFind::getValuesBetweenInChars($string,'[',']');
// so-so

DCFind::getValuesBetweenOutChars($string,'[',']');
// [so-so]

DCFind::getValuesBetweenChars($string,'[',']');
// 0 - [so-so]
// 1 - so-so

!!!!!!!!
DCFind::getValuesBetweenChars($string,"^",".+");
DCFind::getValuesBetweenChars($string,"I",".+");
// 0 - I'm are busy[so-so] human."
// 1 - 
```