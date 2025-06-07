# SoftDir

Добро пожаловать в SoftDir — моё веб-приложение на PHP и MySQL!

Здесь демонстрируются навыки работы с базой данных, MVC-подход и динамическая генерация страниц.

---

## 📦 Содержание

* [Цель проекта](#цель-проекта)
* [Установка и запуск](#установка-и-запуск)
* [Структура](#структура)
* [Использованные технологии](#использованные-технологии)
* [Лицензия](#лицензия)

---

## Цель проекта

SoftDir — это простой CRUD-сайт с регистрацией, авторизацией, валидацией и чистым кодом.
Задачи:

* Минимум внешних зависимостей, только нативный PHP
* Безопасный доступ к БД через PDO
* Чёткое разделение логики по MVC

## Установка и запуск

1. **Клонируйте репозиторий**:

   ```bash
   git clone https://github.com/Cicafa3310/SoftDir.git
   cd SoftDir
   ```

2. **Подготовьте окружение**:

   * PHP 8.2+
   * OpenServer, XAMPP или другой локальный сервер
   * Скопируйте `example.env` в `.env` и заполните:

     ```env
     DB_HOST=127.0.0.1
     DB_NAME=software_catalog
     DB_USER=root  # или ваш пользователь БД
     DB_PASS=       # если есть пароль, укажите его
     ```

3. **Импорт базы данных**:

   * В репозитории есть дамп `software_catalog.php`.
   * Через phpMyAdmin: загрузите файл дампа.
   * Или из консоли:

     ```bash
     mysql -u root -p software_catalog < path/to/dump.sql
     ```
   * Если нужно создать базу вручную:

     ```sql
     CREATE DATABASE software_catalog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     ```

   Миграции не обязательны.

4. **Запустите приложение**:

   * Перейдите в браузере по адресу:

     * [http://softdir](http://softdir)
     * или [http://localhost/SoftDir](http://localhost/SoftDir)

## Структура

```
SoftDir/
|   config.php
|   index.php
|   software_catalog.sql
|
+---admin
|       add_category.php
|       add_software.php
|       categories_list.php
|       delete_category.php
|       delete_user.php
|       edit_category.php
|       edit_software.php
|       edit_user.php
|       index.php
|       users_list.php
|
+---css
|       admin.css
|       categories.css
|       edit_software.css
|       reviews.css
|       software_list.css
|       styles.css
|
+---fonts
|       OCRA.ttf
|
+---includes
|       auth.php
|       db_connect.php
|       footer.php
|       header.php
|
+---js
|       app.js
|
\---pages
        index.php
        login.php
        logout.php
        profile.php
        register.php
        reviews.php
        software_detail.php
        software_list.php
```

## Использованные технологии

* PHP 5.2.1+
* MySQL
* MVC-паттерн
* Apache/Nginx (OpenServer/XAMPP)

## Лицензия

MIT © Cicafa3310

---

Если есть вопросы или идеи — открывайте issue или пишите прямо! Удачи!
