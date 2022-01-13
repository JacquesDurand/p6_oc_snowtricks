**Codacy analysis**  
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/31b6b917b6254357a15becba938c66c6)](https://www.codacy.com/gh/JacquesDurand/p6_oc_snowtricks/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=JacquesDurand/p6_oc_snowtricks&amp;utm_campaign=Badge_Grade)  
**SonarCloud analysis**  
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=JacquesDurand_p6_oc_snowtricks&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=JacquesDurand_p6_oc_snowtricks)  
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=JacquesDurand_p6_oc_snowtricks&metric=bugs)](https://sonarcloud.io/summary/new_code?id=JacquesDurand_p6_oc_snowtricks)  
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=JacquesDurand_p6_oc_snowtricks&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=JacquesDurand_p6_oc_snowtricks)  
[![Vulnerabilities](https://sonarcloud.io/api/project_badges/measure?project=JacquesDurand_p6_oc_snowtricks&metric=vulnerabilities)](https://sonarcloud.io/summary/new_code?id=JacquesDurand_p6_oc_snowtricks)
# Parcours OpenClassrooms: Développeur d'application PHP/Symfony

## Projet 6: Développez de A à Z le site communautaire SnowTricks
-----------------------------------------------

## Description

The goal of this project was to create a communitarian website about snowboards tricks.  
It had to contain:

- A minimum of the following entities:
    - Some **Users** and the possibility to register/login
    - Some snowboard **Tricks** and their associated information
    - The possibility to leave a **Comment** on a trick
- A front office with:
    - An index with all the tricks
    - A navigation menu
    - Pages for a single trick
    - The possibility to add or edit a trick if registered and connected
    - Forms to:
        - leave a comment on a trick
        - contact the Admin (myself)
        - register/login as a **User**


**NOTA BENE :**  
The back end has been realised in **PHP 8.0** and **Symfony 5.3.8**.  
The front end has been realised through the use of [**Bootstrap CSS**](https://getbootstrap.com/) and a tiny bit of
**Jquery**

## Table of contents

- [Installation](#Installation)
    - [Prerequisites](#Prerequisites)
        - [Git](#Git)
        - [Docker](#Docker)
        - [Docker-compose](#Docker-Compose)
    - [Clone](#clone)

- [Configuration](#configuration)
- [Getting started](#getting-started)

## Installation

### Prerequisites

#### Git

To be able to locally use this project, you will need to install [Git](https://git-scm.com/) on your machine.  
Follow the installation instructions [here](https://git-scm.com/downloads) depending on your Operating system.

#### Docker

This project runs 3 separate applications each in their own containers:

1. The PostgreSql DataBase
2. The Nginx Server
3. The PHP/Symfony application itself

Each is based upon its own Docker Image.  
To have them run locally, you will need to install [Docker](https://www.docker.com/) on your machine.  
Follow the installation instructions [here](https://docs.docker.com/get-docker/) for most OS
or [here](https://wiki.archlinux.org/title/Docker) for Archlinux.

#### Docker Compose

As defined on the documentation:
> Compose is a tool for defining and running multi-container Docker applications.

Since it is our case in this project, we also chose to use compose to build the complete project.  
You can see how to install it [here](https://docs.docker.com/compose/install/)

### Clone

Move to the parent directory where you wish to clone the project.

```shell
git clone https://github.com/JacquesDurand/p6_oc_snowtricks.git
```

Then move into the newly cloned directory

```shell
cd p6_oc_snowtricks
```

## Configuration

This project relies on the use of environment variables, which act as *secrets*. These are for instance the database
connection information.  
To prevent sharing my personal information, I didn't commit the **.env** file where they are contained.  
I did commit a **.env.dist** where you can find the variables needed to launch this project.  
Once inside **oc-blog** directory:

```shell
cp .env.dist .env
```

Then open your newly created **.env** with [your favorite text editor](https://neovim.io/) and replace the different *"
CHANGEME"* values by your own.  
You might want to keep

```dotenv
DB_PORT= 5432
```

since the postgres image will run on this port.  
Here is an example of **.env** for this project:

```dotenv
DB_HOST= 'db'
DB_PORT= 5432
DB_NAME= 'mydb'
DB_USERNAME= 'myUser'
DB_PASSWORD= 'myPassword'
MAIL_TO= 'my-email@gmail.com'
MAILER_DSN=yourSendGridDSN
```
Where:
```dotenv
MAIL_TO
```
is your mail for receiving messages through the site's contact form and for sending other mails ( password resetting for instance)  
Be aware though that for these forms to really send email, you have to set up an account with a mail provider such as Sendgrid

## Getting Started

### Launching the containers

Now that everything has been configured, let us get into it !  
Still at the root of **p6_oc_snowtricks**, run :

```shell
docker-compose up -d
```

### Creating the Database

Some migrations files are already created for you that will create the database structure.  
Run the following to enter your app container

```shell
docker-compose exec app sh
```
You can then do: 
```shell
php bin/console doctrine:migrations:migrate
```
to execute the migrations.  
If you want to fill you db with production-ready data, you can also execute the following command:  
```shell
php bin/console app:fixtures:load_prod
```

If everything went fine, you should be able to navigate to [localhost](http://localhost:80) and start looking around my
blog.  
If not, please do not hesitate to [submit an issue](https://github.com/JacquesDurand/oc-blog/issues/new) and I'll get
back to you *ASAP*.