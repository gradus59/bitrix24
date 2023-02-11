# bitrix24

## Подключение к composer

В **autoload** **psr-4** добавляем строку

tools путь к папке с сабмодулем
```
    "autoload": {
        "psr-4": {
            "GraDus59\\Bitrix24\\": "tools/"
        }
    }
```