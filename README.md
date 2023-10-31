<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


# Company Management
#### Skill Assessment

The challenge will contain a few core features most applications have. That includes basic MVC, exposing an API, CRM like features, and finally tests.

### The application should have the following features:
* Frontend should be done with Vue.js and optionally Inertia.js
* Basic Laravel Auth: ability to log in as administrator
* Use database seeds to create first user with email admin@admin.com and password “password”
* CRUD functionality (Create / Read / Update / Delete) for two menu items: Companies and Employees.
* Companies DB table consists of these fields: Name (required), email, logo (minimum 100×100), website
* Employees DB table consists of these fields: First name (required), last name (required), Company (foreign key to Companies), email, phone
* Use database migrations to create those schemas above
* Store companies logos in storage/app/public folder and make them accessible from public
* Use basic Laravel resource controllers with default methods – index, create, store etc.
* Use Laravel’s validation function, using Request classes
* Use Laravel’s pagination for showing Companies/Employees list, 10 entries per page
* Expose an API to list all companies and employees
* Let your creativity run wild and add a feature not mentioned above that you think would be useful for the application

## Developer
Name: `Francisco Cahe` <br/>
Email: `franciscocahe@gmail.com`<br/>

## Instructions (Docker required)
### Cloning the repository
1. Create a bare clone of the repository. (This is temporary and will be removed so just do it wherever.)
    ```bash
    git clone --bare https://github.com/FmTod/skill-assessment-company-management.git
    ```

2. Create a new repository on GitHub.

3. Mirror-push your bare clone to your new repository.<br/>_Replace &lt;username&gt; with your actual GitHub username in the url below._<br/>_Replace &lt;repository&gt; with the name of your new repository._
    ```shell
    cd skill-assessment-company-management.git
    git push --mirror https://github.com/<username>/<repository>.git
    ```
4. Delete the bare clone created in step 1.
    ```shell
    cd ..
    rm -rf skill-assessment-company-management.git
    ```
   
5. You can now clone your repository, where you are going to be working, on your machine (in my case in the code folder).
    ```shell
    cd ~/code
    git clone https://github.com/<username>/<repository>.git
    ```

### Getting Started
1. Create a copy of the `.env.example` file as `.env`
    ```bash
    cp .env.example .env
    ```

2. Install dependencies:
    ```shell
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/var/www/html \
        -w /var/www/html \
        laravelsail/php81-composer:latest \
        composer install --ignore-platform-reqs
    ```

3. Start the container (Sail):
    ```shell
    ./vendor/bin/sail up -d
    ```
   
4. Generate a new secret key:
    ```shell
    ./vendor/bin/sail key:generate
    ```
   
5. (IMPORTANT) Edit the README.md file and add your name and email.
    ```diff
    - Name: `<your name>` <br/>
    - Email: `<your email>` <br/>
    + Name: Jhon Doe <br/>
    + Email: jhondoe@exmaple.com <br/>
    ```
   
6. (IMPORTANT) Submit your first commit with just the changes to the README.md file. Must be done before starting the assignment.
    ```shell
    git add README.md
    git commit -m "Initial commit"
    git push
    ```

### Executing Commands
#### PHP Commands
```shell
./vendor/bin/sail php --version
 
./vendor/bin/sail php script.php
```

#### Composer Commands
```shell
./vendor/bin/sail composer require laravel/sanctum
```

#### Artisan Commands
```shell
./vendor/bin/sail artisan queue:work
```

#### Node / NPM Commands
```shell
./vendor/bin/sail node --version
 
./vendor/bin/sail npm run dev
```

If you wish, you may use Yarn instead of NPM:
```shell
./vendor/bin/sail yarn
```

#### Running Tests
```shell
./vendor/bin/sail test

./vendor/bin/sail test --group orders
```
### How To (Updated)

In order to make this work in a Windows OS, you must not only have installed Docker and WSL (Windows Subsystem for Linux), but you must use a WSL distribution (usually Ubuntu) to store the project within the Linux file system, being able to execute the commands, and have a better performance. You can use Windows file system, but the application will work very slow.

Before starting the steps, you must check that you have WSL and Docker installed, then you might want to open CMD (or Windows Terminal from Microsoft store) and prompt:
```shell
wsl
cd $user
```
From there you can check where you want to place your project...


1. Git clone this repository:
```shell
git clone https://github.com/Sencahe/Company-Managment.git
```

2. Move into the project's folder in the same path you did the clone of the repo:
```shell
cd Company-Managment/
```

3. Create a copy of the `.env.example` file as `.env`:
```shell
cp .env.example .env
```

4. Build the image running the following command:
```shell
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

5. After that you will get the Image in your docker respository as well as the Laravel dependencies. Before starting the container, we have to modify the Dockerfile that comes by default in "vendor\laravel\sail\runtimes\8.1\Dockerfile" as it has set the Node version in 16, when it has to be at least 18. Without this, you might face versioning issues when runnin the container for the first time using sail.
```shell
vim vendor/laravel/sail/runtimes/8.1/Dockerfile
```
Go to line 6, column 18 to modify the node version to 18 or higher

6. Start the container (Sail):
```shell
./vendor/bin/sail up -d
```

7. Generate a new secret key and the storage link:
```shell
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan storage:link
```

8. Install additional Laravel dependencies:
```shell
./vendor/bin/sail composer install
```

9. Create table schemas and populate them:
```shell
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan db:seed --class=DatabaseSeeder
```

10. install Node dependencies and run Frontend:
```shell
./vendor/bin/sail npm install
./vendor/bin/sail npm run dev
```

11. Check the website at localhost:80 in your browser, login with:
<br/>email: admin@admin.com
<br/>password: password

12. You can also run tests:
```shell
./vendor/bin/sail artisan test
```

12. Check swagger API docs:
<br/>localhost:80/api/documentation