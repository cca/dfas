# dfas
dfas.cca.edu PHP website
PHP website written by Chad Wood for CCA
Authenticates with LDAP

* When deploying make sure it runs on https:// only as LDAP credentials are used for authentication
* To configure the database edit this line in config.php:
    > define('DSN','mysql://user1:somepassword@some_host.cca.edu/DB_NAME');
