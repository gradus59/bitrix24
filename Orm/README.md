# Как пользоваться классами в пространстве имен Orm

## Support

- Для всех сущностей возможно начать работать с методами getList add update
- Кроме Smart, этот класс требует дополнительных действий


Превращает строку типа **LEAD** в Orm класс

```
$leadClass = GraDus59\Bitrix24\Orm\Support::getClassOrmByType("LEAD");
```

Вернет ID инфоблока по его api коду

```
$IB_ID = GraDus59\Bitrix24\Orm\Support::getIbIdByApiCode("departments");
```

Вернет ID инфоблока по его CODE

```
$IB_ID = GraDus59\Bitrix24\Orm\Support::getIbIdByCode("departments");
```
