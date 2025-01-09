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

<br/>

 #### ./application/views/*

 - **Your applications pages, templates or views, must stay here**

 > You can change default views path, in `./configs/constants.php` and update `VIEWS_PATH` constant