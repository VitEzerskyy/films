Архитектура приложения
========================

При разработке приложения использовалась архитектура **_Model-View-Controller(MVC)._**

`Структура папок:`
1. _config_ - файлы конфигурации.
2. _controllers_ - контроллеры.
3. _lib_ - базовые классы, отвечающие за логику и взаимодействие компонентов приложения.
4. _models_ - модели
5. _views_ - представления
6. _webroot_ - корневой каталог с точкой входа в приложение. Здесь также содержатся общедоступные файлы (напр. стили, js-скрипты).

`Роутинг:`

Реализован по следующей схеме:

URL:
/controller/action/param1/param2

`Структура БД:`

1. Таблица format(поля id, format). Отношение один ко многим к таблице film (Один формат может быть во многих фильмах)
2. Таблица film(поля id, title, release_year, format_id). Отношение многие ко многим к таблице actor (В одном фильме может играть много актеров, равно как один актер может сниматься во многих фильмах)
3. Таблица actor(поля id, name). Отношение многие ко многим к таблице film.

 
Инструкция по запуску
========================

1. **Должен быть установлен mySQL**.


**`Пошаговая инструкция для Linux:`**


1. _git clone https://github.com/VitEzerskyy/films.git_
или
скачать архив.
2. После клона или распаковки архива зайти в /config/config.php и указать ваши параметры для соединения с БД:
 
 _db.host (хост), db.user (имя юзера), db.password (пасс), db.db_name (имя базы)_

3. Запустить mySQL:

sudo service mysql start (может отличаться в зав-ти от системы)

4. Создать БД:

mysql -u USER -pPASSWORD -e "create database filmsDB"  
(имя для базы может быть любое, важно чтобы оно было такое же, как в config.php)

5. Загрузить дамп (он лежит в корне папки с приложением):

mysql -u USER -pPASSWORD filmsDB < path-to-app-folder/filmsDB.sql

Например:

mysql -u USER -pPASSWORD filmsDB < /home/linux-man/PhpstormProjects/films-project/filmsDB.sql

6. Запустить PHP local server:

Можно сначала перейти в папку с приложением:

cd /path-to-application

Затем

php -S localhost:8000 -t webroot/

**Готово**. Теперь приложение доступно по http://localhost:8000/

**`Пошаговая инструкция для Windows:`**

**_Проще всего установить XAMPP и работать из-под его консоли (или использовать phpmyadmin)_**. Будет меньше мороки :)

1. _git clone https://github.com/VitEzerskyy/films.git_
или
скачать архив.
2. После клона или распаковки архива зайти в /config/config.php и указать ваши параметры для соединения с БД:
 
     _db.host (хост), db.user (имя юзера), db.password (пасс), db.db_name (имя базы)_
     
3. Если вы не используете XAMPP или подобное, то нужно запустить mySQL:

    C:\> "C:\path-to-mysql\mysql\bin\mysqld"

4. Создать БД:

    Если без XAMPP:
    
    C:\path-to-mysql\mysql\bin>mysql.exe -u USER -pPASSWORD -e "create database filmsDB"
    
    (имя для базы может быть любое, важно чтобы оно было такое же, как в config.php) 

    Если с консолью XAMPP:
    
    mysql -u USER -pPASSWORD -e "create database filmsDB"
    
5. Загрузить дамп (он лежит в корне папки с приложением):
    
    без XAMPP:
    
    C:\path-to-mysql\mysql\bin>mysql.exe -u USER -pPASSWORD filmsDB < path-to-app-folder/filmsDB.sql
    
    C  консолью XAMPP:
    
    mysql -u USER -pPASSWORD filmsDB < path-to-app-folder/filmsDB.sql
    
6. Запустить PHP local server:
    
   Без XAMPP:
   
   C:\path-to-php\php.exe -S localhost:8000 -t /path-to-app-folder/webroot/ 
   
   С консолью XAMPP:
   
   php -S localhost:8000 -t /path-to-app-folder/webroot/
   
**Готово**. Теперь приложение доступно по http://localhost:8000/