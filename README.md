## The Test Task is Fully done with all Bonuses.

![img.png](documentation/img.png)

![img_1.png](documentation/img_1.png)

## Solution review
Look at code in this commit To look at files that I was working with https://github.com/orlovaOlga/testTask/commit/c8063a6e20eec4b4c788153fe65db53bc55f3bef

#### Back-end Related files
1) ".env" file it's a file with Environment Variables. there is setting like DATABASE_URL connection on 27 line
2) "config/packages/framework.yaml/" this is configurations file with enabled validation package.
3) "migrations/Version20230911183426.php" simple migrations that creates table in the database
4) "src/Command/ParseUsersCommand.php" this is a CLI it makes 10 requests to https://randomuser.me/api/, take response, validate that response format was not changed, and create a new users.
5) "src/Controller/IndexController.php" just a Controller for the main page.
6) "src/Controller/UserController.php" there is described end-point to take Users data. it use "normalazer" to convert object to a flexible json format.
7) "src/Entity/User.php" This is a User entity, there annotation needs to describe relation between Users properties and fields "users" table
8) "src/Repository/UserRepository.php" make requests to the database and convert hydrate rows to Users objects.

#### Front-end related files
1) "templates/index/index.html.twig" basic page with mark up and imported js code.
2) "public/js/UsersTableDataGridComponent.js" here we have few functions:
   "renderUserTableContent" is working with promise that take data from our back-end and handle rejection if request was failed
   "addTableRow" add users info as a row in the table
   "clearTableRows" remove rows in the table
   event Listener on "Filter" button that call renderUserTableContent(name) where Name argument is a value from the Input
3) "public/js/utils/GetCountryFlagUrl.js" there is code that provide us Countries Flags


## Run project locally

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

8) Then run a console command to parse 10 new users
```shell
php bin/console app:parse-users
```

9) Start the server, then open http://localhost:8008/
```shell
php -S localhost:8008 -t public/
```