# Laravel NovaPoshta API 2.0

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/daaner/novaposhta/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/daaner/novaposhta/?branch=master)
[![Laravel Support](https://img.shields.io/badge/Laravel-7+-brightgreen.svg)]()
[![PHP Support](https://img.shields.io/badge/PHP-7.2.5+-brightgreen.svg)]()
[![Official Site](https://img.shields.io/badge/official-site-blue.svg)](https://daaner.github.io/NovaPoshta/)

[![Latest Stable Version](https://poser.pugx.org/daaner/novaposhta/v)](//packagist.org/packages/daaner/novaposhta)
[![Total Downloads](https://poser.pugx.org/daaner/novaposhta/downloads)](//packagist.org/packages/daaner/novaposhta)
[![License](https://poser.pugx.org/daaner/novaposhta/license)](//packagist.org/packages/daaner/novaposhta)



Управление отправками NovaPoshta ([novaposhta.ua](https://novaposhta.ua/)) с помощью фреймворка Laravel 7 и старше ([Laravel](https://laravel.com)).

Удобный пакет для отправки и проверки ТТН через сервис [NovaPoshta.ua](https://novaposhta.ua/)


__ВНИМАНИЕ__

```php

// Не доделал и не оттестил все модели
// используйте ветку `dev-master`
// В ней все самое последнее. Документацию стараюсь не затягивать
```



#### Laravel > 7, PHP >= 7.2.5
Минимальная версия Laravel `7.0`, для более низкой версии нужно использовать `guzzle/guzzle`

Работает на последнем Laravel 9 и PHP 8.1


## Установка
Установите пакет через композер.

``` bash
composer require daaner/novaposhta
```


Если вы НЕ используете autodiscover - добавьте сервис провайдер в конфигурационный файл `config/app.php`.

```php
Daaner\NovaPoshta\NovaPoshtaServiceProvider::class,
```

Добавьте фасад `NovaPoshta` в массив в `config/app.php`:
```php
'NovaPoshta' => Daaner\NovaPoshta\Facades\NovaPoshta::class,
```

Выполните публикацию конфига и локализационных файлов командой:
``` bash
php artisan vendor:publish --provider="Daaner\NovaPoshta\NovaPoshtaServiceProvider"
```


## Конфигурация

После публикации ресурсов поправьте файл `config/novaposhta.php` и заполните `.env` новыми полями.

- Создайте аккаунт на сайте [novaposhta.ua](https://novaposhta.ua)
- Скопируйте `Ключ API` в настройках безопасности в разделе `Мои ключи API` и добавьте в соответствующий параметр в `config/novaposhta.php` либо в .env файл
- `point` поддерживается только `json` (вряд ли добавится `xml`)
- `dev` режим отладки запросов. Включает в лог каждый запрос на API Новой Почты (не оставляйте на продакшене) и в массиве ответа данные с ключем `dev` без преобразования, а та как возвращает Новая Почта


## Использование и API
- `setAPI($apiKey)` - установка API ключа, отличного от значения по умолчанию

```php
$cp = new Counterparty;
$cp->setAPI('391e241b2c********************e7');
```

- `getResponse($model, $calledMethod, $methodProperties, $auth = true)` - кастомная отправка данных, если добавятся новые методы

```php
use NovaPoshta;
$model = 'TrackingDocument'; //нужная модель
$calledMethod = 'getStatusDocuments'; //нужный метод
$methodProperties = [
  //данные по документации
];
$np = new NovaPoshta;
$data = $np->getResponse($model, $calledMethod, $methodProperties, $auth = true);

dd($data);
```


## Поддержка моделей / методов
#### Хелперы (более детальные хелперы можно увидеть в документации к модели)
Хелперы вызываются до главного метода обращения:

```php
$foo = new Common;
$list = $foo->getPaymentForms();

$bar = new Address;
$bar->setLimit(5)->setPage(2);
$cities = $bar->getCities();

dd($cities);
```

Очень много моделей имеют в ответе дубляж на русском. В некоторых справочниках нет русской локализации.
- `setLimit(100)` - лимит запроса записей
- `setPage(3)` - пагинация при лимите



## Официально не документированный функционал
- [x] Получение данных по бонусной карте
- [x] Обновить описание реестра
- [x] Краткий список накладных реестра
- [x] Получение данных об Контрагенте по номеру телефона



## Статус обертки над API новой почты
[Официальная документация API Новой почты](https://developers.novaposhta.ua/)
[Документация по пакету](https://daaner.github.io/NovaPoshta/#/)


### [API Адреса](https://daaner.github.io/NovaPoshta/#/Address)
#### Работа с адресами
- [x] Онлайн поиск в справочнике населенных пунктов
- [x] Онлайн поиск улиц в справочнике населенных пунктов
- [x] Создать адрес контрагента (отправитель/получатель)
- [x] Редактировать адрес контрагента (отправитель/получатель)
- [x] Удалить адрес контрагента (отправитель/получатель)
- [x] Справочник городов компании
- [x] Справочник населенных пунктов Украины
- [x] Справочник географических областей Украины
- [x] Справочник отделений и типов отделений
- [x] Справочник улиц компании
- [ ] Создать адрес контрагента (отправитель/получатель)
- [ ] Редактировать адрес контрагента (отправитель/получатель)
- [ ] Удалить адрес контрагента (отправитель/получатель)


### [API Контрагенты](https://daaner.github.io/NovaPoshta/#/Counterparty)
#### Работа с данными Контрагента
- [x] Создать Контрагента
- [ ] Создать контактное лицо Контрагента
- [x] Создать Контрагента с типом (юридическое лицо) организация
- [x] Создать Контрагента с типом третьего лица
- [x] Загрузить список адресов Контрагентов
- [x] Загрузить параметры Контрагента
- [x] Загрузить список контактных лиц Контрагента
- [x] Загрузить список контрагентов
- [ ] Обновить данные Контрагента
- [ ] Обновить данные контактного лица Контрагента
- [ ] Удалить Контрагента получателя
- [ ] Удалить Контактное лицо Контрагента


### [API Печатные формы](https://daaner.github.io/NovaPoshta/#/)
#### Это коллекция методов для получения печатных форм документов.
- [ ] Маркировки - печатная форма
- [ ] Реестры - печатная форма
- [ ] Экспресс-накладная - печатные формы


### [API Реестры](https://daaner.github.io/NovaPoshta/#/ScanSheet)
#### Работа с реестрами экспресс-накладных
- [x] Добавить экспресс-накладные в реестр
- [x] Загрузить информацию по одному реестру
- [x] Загрузить список реестров
- [x] Обновить описание реестра
- [x] Краткий список накладных реестра
- [x] Удалить (расформировать) реестр отправлений
- [x] Удалить экспресс-накладные из реестра


### [API Справочники](https://daaner.github.io/NovaPoshta/#/Common)
#### Работа со справочниками.
- [x] Виды временных интервалов
- [x] Виды груза
- [x] Виды обратной доставки груза
- [x] Виды паллет
- [x] Виды плательщиков
- [x] Виды плательщиков обратной доставки
- [x] Виды упаковки
- [x] Виды шин и дисков
- [x] Описания груза
- [x] Перечень ошибок
- [x] Технологии доставки
- [x] Типы контрагентов
- [x] Формы оплаты
- [x] Формы собственности


### [API Услуга возврат отправления](https://daaner.github.io/NovaPoshta/#/AdditionalService)
#### Возможность самостоятельного оформления Клиентом услуги «Возврат отправления» при использовании API. Услуга доступна только для клиентов отправителей.
- [x] Проверка возможности создания заявки на возврат
- [x] Получение списка причин возврата
- [x] Получение списка подтипов причины возврата
- [x] Создание заявки на возврат
- [x] Получение списка заявок на возврат
- [x] Удаление заявки на возврат


### [API Услуга Изменение данных](https://daaner.github.io/NovaPoshta/#/)
#### Возможность самостоятельного оформления Клиентом услуги «Изменение данных» при использовании API.
- [ ] Проверка возможности создания заявки по изменению данных
- [ ] Создание заявки по изменению данных
- [x] Удаление заявки
- [ ] Получение списка заявок


### [API Услуга переадресация отправления](https://daaner.github.io/NovaPoshta/#/AdditionalService)
#### Возможность самостоятельного оформления Клиентом услуги «Переадресация» при использовании API. Услуга доступна для клиентов отправителей и получателей.
- [x] Проверка возможности создания заявки на переадресацию отправления
- [x] Создание заявки переадресация отправления (отделение/адрес)
- [x] Удаление заявки
- [x] Получение списка заявок


### [API Экспресс-накладная](https://daaner.github.io/NovaPoshta/#/)
#### Работа с экспресс-накладными
- [ ] Рассчитать стоимость услуг
- [x] Прогноз даты доставки груза
- [x] Создать экспресс-накладную
- [x] Создать экспресс-накладную на адрес
- [x] Создать экспресс-накладную на отделение
- [x] Создать экспресс-накладную на почтомат "Нова пошта"
- [x] Создать экспресс-накладную с обратной доставкой
- [x] Редактировать экспресс-накладную (НЕ ТЕСТИРОВАЛОСЬ)
- [x] Получение полной инфо об ЭН
- [ ] Получить список ЭН
- [x] Удалить экспресс-накладные
- [ ] Формирование запроса для получения полного отчета по накладным
- [ ] Формирование запросов на создание ЭН с дополнительными услугами
- [ ] Формирование запросов на создание ЭН с различными видами груза


***

### Пропущенный функционал (не вижу потребности или не могу проверить)
- Создание Контрагента с типом юрлицо или третье лицо
  - не добавлена возможность указывать `CityRef`


***

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


## Contributing

Всегда рад поддержке, указаниям на ошибки и ПР!

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.



## Credits

- [Daan](https://github.com/daaner)
- [Telegram](https://t.me/neodaan)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
