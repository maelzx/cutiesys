# CUTIeSys

A simple Leave Application And Approval web based system code using PHP CodeIgniter, JQuery &amp; AdminLTE Bootstrap template.

The name CUTIeSYS have a simple meaning, *CUTI* means leave in my language, *e* just want to make it knows as an electronic *Sys*tem, so bascially an Electronic System for Leave Management.

## Installation

1. Download all the file/or clone it
2. Create database and import the cutiesys.sql
3. Edit file application/config/database.php
```php
'username' => '<put your database username>',
'password' => '<put your database password>',
'database' => '<put your database name>',
```
4. Edit file application/config/config.php
```php
$config['base_url'] = '<replace with your url or ip address dont for the forward slash>/';
```
5. Done. Fire up browser and browse to the new url address as per what your config previously
6. Login by using **username**: admin **password**: password 

## What we use

1. PHP Framework : CodeIgniter 3.0.6
2. AdminLTE Bootstrap Template 2.3.5
3. All the js scripts/plugins from AdminLTE 2.3.5

## What we intend to do

One day we want to view who is not available during the Eid Holiday (Public Holiday in my country), and because we dont have any system to do it, I code this instead.

## What it can do now ?

1. Login & Logout
2. View everybody leave
3. Apply for leave
4. View the pending/active leave
5. View the history leave
6. Approve the leave (user with is_approver = 1)
7. Add new user (user with is_approver = 1)
8. Reset the user password (user with is_approver = 1)

## What we plan todo ?

1. ALOT! make it more user friendly.
2. Write a unit test ?? We dont have that expertise yet.

## Preview
1. Go [here](https://cutiesys.isha.my)
2. Login using **username**: admin **password**: password

## Quickly Deploy to Codeanywhere
1. Follow the guide [here](https://ishait.wordpress.com/2016/07/20/start-developing-github-project-from-codeanywhere)

## Bugs & Issues

1. Please report it.
