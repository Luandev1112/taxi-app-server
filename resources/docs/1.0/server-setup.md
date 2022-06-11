# Server Installation

---

- [Introduction](#section-1)
- [Installation Instructions](#section-2)
- [Create an Instance from AWS/AZURE](#section-3)
- [Add Security groups](#section-4)
- [Install Apache](#section-5)
- [Install PHP](#section-6)
- [Install mysql](#section-7)
- [Install phpmyadmin](#section-8)
- [Install composer](#section-9)
- [Install Jenkins](#section-10)
- [Install Nodejs & Pm2](#section-11)
- [Setup Laravel Supervisor](#section-12)

<a name="section-1"></a>
## Introduction

We are strongly suggested AWS or Azure VPS with ubuntu OS. Because it is quite easy to setup and maintain. For an AWS we can create EC2 T2 medium or large instance. After created an instance from AWS or AZURE. we need to install the required softwares below.

<a name="section-2"></a>
## Installation Instructions

1. Create an Instance from AWS/AZURE
2. Add Security groups
3. Install Apache
4. Install PHP7.2 & above
5. Install mysql
6. Install phpmyadmin
7. Install composer
8. Install Jenkins
9. Install Nodejs & Pm2
10. Setup Laravel Supervisor
11. Install EMQX server


<a name="section-3"></a>
## 1. Create an Instance from AWS/AZURE

<a name="section-4"></a>
## 2. Add Security groups & enable the below port numbers
    * 1883,3000,3001,443,80,8080

<a name="section-5"></a>
## 3. Install Apache

* Open the ssh terminal using pemfile of your aws instance

* Run "sudo apt install apache2" to install an apache2

* Run "sudo apt update"

* Run "sudo nano /etc/apache2/sites-available/000-default.conf" to edit the config follow next step.

*  <Directory /var/www/html>

                Options -Indexes
                AllowOverride All
                Require all granted
                ErrorDocument 403 "You Don't have a permission to access this URL"
                ErrorDocument 404 "Requesting Page not Found. Contact admin for further details"
          </Directory>
* Run "sudo service apache2 restart" to restart the apache2.

* Reference Link : https://www.digitalocean.com/community/tutorials/how-to-install-the-apache-web-server-on-ubuntu-20-04

<a name="section-6"></a>
## 4.Install PHP7.2 & above
Please follow the instrctions from the reference link below. And Install the php extensions below.
<strong>bcmath,bz2,intl,gd,mbstring,mysql,zip,fpm,curl,xml</strong>

Reference Link : https://computingforgeeks.com/how-to-install-php-on-ubuntu/

<a name="section-7"></a>
## 5.Install mysql
Run the below commands one by one

*  sudo apt-get update
* sudo apt-get install mysql-server
* mysql_secure_installation
* sudo service mysql restart

To create a new user for mysql

* sudo mysql -u root
* use mysql;
* GRANT ALL ON *.* TO 'taxi_user'@'%' IDENTIFIED BY 'TaxiUser@123' WITH GRANT OPTION;
* FLUSH PRIVILEGES;

Reference Link : https://www.digitalocean.com/community/tutorials/how-to-install-mysql-on-ubuntu-20-04

<a name="section-8"></a>
## 6. Install phpmyadmin

Open the path "var/www/html". and dowonload the phpmyadmin package using below command.

 "sudo wget https://files.phpmyadmin.net/phpMyAdmin/5.0.1/phpMyAdmin-5.0.1-all-languages.zip"

Unzip the downloaded package and rename it to "pma".

<a name="section-9"></a>
## 7. Install composer

Please follow the instructions from the reference link below.


Reference Link: https://www.digitalocean.com/community/tutorials/how-to-install-and-use-composer-on-ubuntu-20-04

<a name="section-10"> </a>
## 8.Install Jenkins (Optional)

* jenkins is used to upload the backend app code to the server via git repo.

* To install jenkins you need to install JAVA. to install java run this command "sudo apt install openjdk-8-jdk
".

Follow the instructions from the reference link below. and skip the firewal setup.

Reference Link: https://www.digitalocean.com/community/tutorials/how-to-install-jenkins-on-ubuntu-16-04

<a name="section-11"></a>
## 9.Install Nodejs & Pm2

Follow the instructions from the reference link below. install nodejs & npm as well.

Reference Link: https://www.digitalocean.com/community/tutorials/how-to-install-node-js-on-ubuntu-20-04

<a name="section-12"></a>
## 10.Laravel supervisor setup

Follow the instructions from the reference link below.

Reference Link: https://laravel.com/docs/8.x/queues#supervisor-configuration


## 11.Install EMQX server

Follow the instructions below & from the reference link.

1.  Download emqx-ubuntu20.04-5.0-beta.1-amd64.zip 

    * wget https://www.emqx.com/en/downloads/broker/5.0-beta.1/emqx-ubuntu20.04-5.0-beta.1-amd64.zip 

2. Install the file
    * unzip emqx-ubuntu20.04-5.0-beta.1-amd64.zip

3. Run the server by below command
    * ./bin/emqx start

Reference Link: https://www.emqx.com/en/downloads?product=broker


