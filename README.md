# Simulado Full-Stack

## Database Setup

Get script.sql and import in your DataBase Manager (Mysql Workbench for example)

Now, go to Terminal in the backend folder, and execute the command:

```bash
php artisan migrate
```

Do this to create the token tables

## Application Setup

### Back-End Setup
Go to .env.example, copy and rename the copy to: <b>.env</b>

Setup the AppName and AppUrl, for example:
```.env
APP_NAME=NameOfApplication (ForcaTotal)
APP_URL=UrlForApplication (http://localhost:8000)
```

Configure the DataBase data in .env, for example:
```.env
DB_CONNECTION=dbused (mysql, sqlite, postgre)
DB_HOST=dbhost (localhost)
DB_PORT=dbport (3306)
DB_DATABASE=dbname (forcatotal)
DB_USERNAME=dbusername (admin)
DB_PASSWORD=dbpassword (admin)
```

Go to terminal in Backend Folder and execute:

```bash
php artisan serve
```

