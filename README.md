# Payment Integration Using Razorpay Example in Codeigniter 3, jQuery and MySQL
Sample for how to use Payment Integartion using Razorpay in CodeIgniter 3

## Dependencies 
Setup CodeIgniter 3 Framework . Download the latest release from [downlaod codeigniter 3](https://codeigniter.com/userguide3/installation/downloads.html)

Install Razorpay through composer ``` composer require razorpay/razorpay:2.* ``` or downlaod from [here](https://github.com/razorpay/razorpay-php/releases)  
# Instructions 
1. Create your Database  and table  
``` 
CREATE DATABASE payment_integration 
```
    
``` 
CREATE TABLE payment_integration.payment ( Payment_id INT(11) NOT NULL AUTO_INCREMENT ,  order_id VARCHAR(50) NULL DEFAULT NULL ,  name VARCHAR(50) NULL DEFAULT NULL ,  email VARCHAR(255) NULL DEFAULT NULL ,  contact VARCHAR(20) NULL DEFAULT NULL , amount VARCHAR(50) NULL DEFAULT NULL ,  razorpay_id VARCHAR(50) NULL DEFAULT NULL ,  created_date DATETIME NULL DEFAULT NULL ,    PRIMARY KEY  (`Payment_id`)) ENGINE = InnoDB;
```

2. Setup your database file inside config folder  
  ```
  $db['local'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => '****',
	'password' => '****',
	'database' => '****',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
```
3. Set **RAZOR_KEY_ID** and **RAZOR_KEY_SECRET** on constants.php file inside config folder
  ``` 
  define('RAZOR_KEY_ID', 'xxx-xxx-xxxxx-xxxx');
  define('RAZOR_KEY_SECRET', 'xxxxxxxxxxxxxxx');
  ``` 
4. Enter basic details and amount and click on pay from view page(views/payment.php).
5. Request sent to pay function inside Payment_integration controllers and Instantiate the razorpay php instance ``` $api = new Api(RAZOR_KEY_ID, RAZOR_KEY_SECRET);``` Once the $api object or instance is set we can access the resources uisng ``$api``.
