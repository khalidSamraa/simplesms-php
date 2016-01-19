# SimpleSMS API Client for PHP

https://simplesms.id

## Getting Started

Installation using Composer

`
composer.phar install simplesms
`


## Usage

### Initialization

```php
$simplesms = new SimpleSMS\SimpleSMS('api_access_key', 'api_secret_key');
```

Note: `api_access_key` & `api_secret_key` can be obtained by creating an account.

### Sandbox/Development Mode

```php
$simplesms->development = TRUE; //default FALSE
```

### Check Balance

```php
$balance = $simplesms->getBalance();
```

### Send Single SMS

Using random number as sender

```php
$msisdn = '085862011111';
$text = 'Hello, World';
$simplesms->send($msisdn, $text);
```

Using fix dedicated number as sender

```php
$msisdn = '085862011111';
$text = 'Hello, World';
$sender_id = '081212345678';
$simplesms->send($msisdn, $text, $sender_id);
```

Using Masking ID (Alphanumeric) as sender

```php
$msisdn = '085862011111';
$text = 'Hello, World';
$sender_id = 'Kominfo';
$simplesms->send($msisdn, $text, $sender_id);
```

### Send Broadcast SMS

Using random number as sender

```php
$recipients = array('msisdn1', 'msisdn2', 'msisdn3', 'msisdn4');
$text = 'This is broadcast message';
$simplesms->broadcast($recipients, $text);
```

Using fix dedicated number as sender

```php
$recipients = array('msisdn1', 'msisdn2', 'msisdn3', 'msisdn4');
$text = 'This is broadcast message';
$sender_id = '081212345678';
$simplesms->broadcast($recipients, $text, $sender_id);
```

Using Masking ID (Alphanumeric) as sender

```php
$recipients = array('msisdn1', 'msisdn2', 'msisdn3', 'msisdn4');
$text = 'This is broadcast message';
$sender_id = 'Sandiloka';
$simplesms->broadcast($recipients, $text, $sender_id);
```

## Resources

* [API v1 Documentation](https://docs.simplesms.id)
* [Questions & Answers](https://ask.simplesms.id)








