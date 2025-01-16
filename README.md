# Instik

**A simple project in PHP, that I create all libs code. This is to a colege actvity**

---

<br/>

## ./index.php

 - **All requests is redirected to it, this requires `./configs/init.php`, the initializer**

<br/>

## ./configs/*

 - **Contains all configure and initialize scripts**

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

 - **If you want perform some configuration os initial setup, uses `./application/init.php`**
 - **If you want perform some SQL script, uses `Database::instance()->getDefaultConnection()`. This generate a connection in `mysql` database, with this, you can create another database before try connect with `DB_NAME`**