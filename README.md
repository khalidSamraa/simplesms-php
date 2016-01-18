# simplesms-php
SimpleSMS API Client for PHP5
https://simplesms.id/sdk

## Getting Started

Installation using Composer

`
composer.phar install simplesms
`


## Usage

Initialization

`
$simplesms = new SimpleSms\SimpleSms('api_access_key', 'api_secret_key');
`

Sandbox

`
$simplesms->development = TRUE; //default FALSE
`

Check Balance

`
$balance = simplesms->getBalance();
`

Send Single SMS

`
$msisdn = '085862011111';
$text = 'Hello, World';
simplesms->send($msisdn, $text);
`

Send Multiple SMS

`
$recipients = array('msisdn1', 'msisdn2', 'msisdn3', 'msisdn4');
$text = 'This is multi sms text';
$simplesms->sendArray($recipients, $text);
`

Resources

* APIv1 Documentation
* Ask








