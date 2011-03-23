README
======

This project provides a simple framework to serve RESTful functions using zend framework

Key Features
------------
* Authorization with 3scale based on app_id & app_key
* XML & JSON friendly formatted responses


The project requires:

* Zend Framework 1.11 (http://framework.zend.com/)
* 3Scale Account (http://www.3scale.net/admin)



Setting Up Your VHOST
---------------------

The following is a sample VHOST you might want to consider for your project.

    <VirtualHost *:80>
       DocumentRoot "/var/www/zf-rest-example/public"
       ServerName api.yourdomain.com
    
       # This should be omitted in the production environment
       SetEnv APPLICATION_ENV development
    
       <Directory "/var/www/zf-rest-example/public">
           Options Indexes MultiViews FollowSymLinks
           AllowOverride All
           Order allow,deny
           Allow from all
       </Directory>
    
    </VirtualHost>
