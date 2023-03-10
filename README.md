# bitrix24

## Подключение к composer

В **autoload** **psr-4** добавляем строку

tools путь к папке с сабмодулем
```
    "autoload": {
        "psr-4": {
            "GraDus59\\Bitrix24\\": "gb_tools/"
        }
    }
```

## Возможности библиотеки

- Работа с данными, вспомогательный класс
- Работа со смарт-процессом
- Работа с HighLoad блоками
- Класс для работы с CSV файлом
- Работа с Входящим\Исходящим звонком
- Работа с обзвоном

## Планируется добавить

- Импорт из csv файла
- Кастомные поля
- - Select
- - Набираемый список
- - Диалог
- Вызов модального окна с бэка
- Событийная архитектура
- Запуск Бизнес процесса
- Уведомления
- Поля CRM и интерпритатор их

## git pull origin main

Модуль проектируется с учетом неизменности обращения к нему,
однако перед обновлением модуля рекомендуется проверить какие изменения будут в следующей версии
для этого создана папка version. 

Файл `v` содержит версию модуля.

Файл `annotation.md` содержит описание изменений

- Выполняем `git fetch origin main`
- Далее `git diff FETCH_HEAD -- version/v`, 
так вы узнаете о появлении новой версии
- Далее `git diff FETCH_HEAD -- version/annotation.md`, 
чтобы посмотреть аннотацию к грядущим изменениям

Такой подход позволит оценить нужна ли вам следующая версия или какие правки потребуется внести.

Это создаст возможность применения `git pull origin main` без замирания сердца.