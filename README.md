## Run project locally
To look at files that I was working with https://github.com/orlovaOlga/testTask/commit/c8063a6e20eec4b4c788153fe65db53bc55f3bef

1) Be sure that your php version >= 8.1, and that Mysql is 8
2) Clone the repository
```shell
git clone https://github.com/orlovaOlga/testTask.git ./olgaTestTask
```
3) get into the project folder
```shell
cd olgaTestTask
```

4) Run composer install 
```shell
composer install
```

5) Create a database.
```SQL
CREATE DATABASE mobile_brain CHARACTER SET utf8mb4 collate utf8mb4_bin
```
5) Open ".env" file and in 27 line replace part of DATABASE_URL, insdead of "olga:1111" use your login and password for database connection
```
DATABASE_URL="mysql://olga:1111@127.0.0.1:3306/mobile_brain?serverVersion=8&charset=utf8mb4"
```

7) Run command to apply migrations that creates "users" table with indexs
```shell
php bin/console doctrine:migrations:migrate
```

8) Start the server, then open http://127.0.0.1:8000/
```shell
php bin/console server:start
```