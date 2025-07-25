# Instik

**This is a simple framework created by myself. It has the most used framework features, like:**
- **Routes**
- **Dependency Injection**
- **Session Manager**
- **ORM** - Only works with MySQL Driver
- **External Libs Integration**

**`application/` path is like used for development, so all files in this directory, is for a college activity that I developed with framework**

Below has some specifications about how to use framework. **!It can has errors, the framework is unfinished!**

---

<br/>

## ./index.php

 - **All requests is redirected to it, this requires `./configs/init.php`, the initializer**

<br/>

## ./configs/*

 - **Contains all configure and initialize scripts**


<br/>

 #### ./configs/contants.php

 - **Contais all the system contants, some constants can be changed**
 
 > - `VIEW_PATH` - To default views path
 > - `ENV_PATH` - To load environment variables
 > - `LOG_PATH` - To save logs in a file
 > - `DB_HOST` - Must be updated
 > - `DB_PORT` - Must be updated if you use a different port
 > - `DB_USER` - Must be updated
 > - `DB_PASSWORD` - Must be updated
 > - `DB_NAME` - Must be updated

<br/>

## ./system/*

 - **Contains libs classes, I maked all them**

<br/>

## ./assets/*

 - **Must contain auxiliary Front-End files**
 > - `*.css`
 > - `*.js`
 > - `*.jpg`
 > - `*.png`
 > - `*.icon`

<br/>

## ./application/*

 - **Must contain all views, logic, business rules, configs... from YOUR application**
 - **You need to create constructors in your class for Dependence Injector works**

<br/>

 #### ./application/views/*

 - **Your applications pages, templates or views, must stay here**
 - **To load templates in your views files, use this code `<?php $this->load("view_path") ?>`**

 > You can change default views path, in `./configs/constants.php` and update `VIEWS_PATH` constant

<br/>

## ./application/init.php

 - **If you want perform some configuration on initial setup, use this file**
 - **If you want perform some SQL script, use `Database::instance()->getDefaultConnection()`, this create a connection in `mysql` database with PDO class, so see _[PDO documentation](https://www.php.net/manual/pt_BR/class.pdo.php)_. With this, you can create another database before try to connect with `DB_NAME`**

 <br/>

 ## TODO

  - Implements Requests Attributes
  - Refactor request management to work with layers, each step just is a layer.
