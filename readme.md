### Garmin SSO Client ####

Based on the work of the *[Garth library](https://github.com/matin/garth)*. Basic Garmin Connect / Auth client library

## Usage ##

``composer require jjtbsomhorst/garminsso``

```
<?php
 use garmin\sso\GarminClient;
 
 $client = new GarminClient();
 $client
    ->username('username')
    ->password('password')
    ->cookieJarLocation('path')
    ->login();
    
 $activities $client->getActivities();    

```



