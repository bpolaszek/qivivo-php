bentools/qivivo-php - Unofficial PHP client for Qivivo Thermostat API
===============
 
 Installation
 -----
 
 ```
 composer require bentools/qivivo-php
 ```
 
 Usage
 -----
 
 ```php
 <?php
 $clientId = 'myClientId';
 $clientSecret = 'myClientSecret'; // To obtain those credentials, please contact Qivivo support.
 $qivivo = new \BenTools\Qivivo\Client(new \GuzzleHttp\Client(), $clientId, $clientSecret);
 
 
 // Retrieve main thermostat UUID
 $thermostatId = $qivivo->execute($qivivo->device()->getThermostatId());
 var_dump($thermostatId); // string "5dcb767f-d652-446a-a7d4-c8b22f77a7e8"
 
 
 // Retrieve current temperature
 var_dump($qivivo->execute($qivivo->thermostat()->getTemperature($thermostatId))); // float 19.7
 
 
 // Change temperature
 $qivivo->execute($qivivo->thermostat()->setTemperature($thermostatId, 21.5, 45)); // Sets the temperature to 21.5°C for 45 minutes
 ```
 
 Contribute
 ------
 This is a very early draft to wrap the Qivivo API. 
 Don't hesitate to contribute and add some tests.
 
 
 License
 ------
 MIT
