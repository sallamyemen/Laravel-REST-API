"Создание REST API приложения"

Описание.
Необходимо реализовать REST API приложения для справочника Организаций, Зданий, Деятельности.
    1. Организация - Представляет собой карточку организации в справочнике и должна содержать в себе следующую информацию:
        ◦ Название: Например ООО “Рога и Копыта”
        ◦ Номер телефона: организация может иметь несколько номеров телефонов (2-222-222, 3-333-333, 8-923-666-13-13)
        ◦ Здание: Организация должна находится в одном конкретном здании (Например, Блюхера, 32/1)
        ◦ Деятельность: Организация может заниматься несколькими видами деятельностей (Например, “Молочная продукция”, “Мясная продукция”)
    2. Здание - Содержит в себе как минимум информацию о конкретном здании, а именно:
        ◦ Адрес: Например - г. Москва, ул. Ленина 1, офис 3
        ◦ Географические координаты: Местоположение здания должно быть в виде широты и долготы.
    3. Деятельность - позволяет классифицировать род деятельности организаций в каталоге. Имеет название и может в древовидном виде вкладываться друг в друга. Пример возможного дерева деятельности:
  - Еда
    - Мясная продукция
    - Молочная продукция
  - Автомобили
    - Грузовые
  - Легковые
      - Запчасти
      - Аксессуары

Функционал приложения.
Взаимодействие с пользователем происходит посредством HTTP запросов к API серверу с использованием статического API ключа. Все ответы должны быть в формате JSON. Необходимо реализовать следующие методы:
    • список всех организаций находящихся в конкретном здании
    • список всех организаций, которые относятся к указанному виду деятельности
    • список организаций, которые находятся в заданном радиусе/прямоугольной области относительно указанной точки на карте. список зданий
    • вывод информации об организации по её идентификатору
    • искать организации по виду деятельности. Например, поиск по виду деятельности «Еда», которая находится на первом уровне дерева, и чтобы нашлись все организации, которые относятся к видам деятельности, лежащим внутри. Т.е. в результатах поиска должны отобразиться организации с видом деятельности Еда, Мясная продукция, Молочная продукция.
    • поиск организации по названию
    • ограничить уровень вложенности деятельностей 3 уровням

Задание
    • Спроектировать БД + Создать необходимые миграции + Заполнить БД тестовыми данными
    • Реализовать API согласно разделу Функционал приложения
    • Завернуть приложения в Docker контейнер, чтобы его можно было развернуть на любой машине (Если необходимо, то написать инструкцию по разворачиванию)
    • Добавить в проект документацию Swagger UI или Redoc с описанием всех методов приложения.
