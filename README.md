# Locale-Detector package

Using this module, you can then get the locale detected for the user, based on what you want first.

To call this module :

```php
$localeDetector = new Menencia\LocaleDetector\LocaleDetector();
```

This is the actual strategy order, but you can reorder this array or remove some items :

```php
$localeDetector->setOrder(['TLD', 'Cookie', 'Header', 'NSession']); // optional
```

* TLD (Top-level domain): determining locale from `$_SERVER['SERVER_NAME']`
* Cookie: determining locale from `$_COOKIE[$field]`
* Header: determining locale from `$_SERVER['HTTP_ACCEPT_LANGUAGE']`
* NSession (Session): determining locale from `$_SESSION[$field]`
* IP (IP Address): determining locale from `$_SERVER['REMOTE_ADDR']`
* By default, determining locale from `Locale::getDefault()`

By default, `$field = 'lang';`. This is how you can change that :

```php
Cookie::$fieldName = 'newFieldName';
NSession::$fieldName = 'newFieldName';
```

Then, you just have to call the detect method and retrieve the locale :

```php
$locale = $localeDetector->detect();
```

## Advanced strategies

You have the possibility to custom your strategy like this :

```php
$localeDetector->addCallback('MyCallback', function($a){
    return collator_create($a);
}, ['fr-FR']);

$localeDetector->setOrder(['MyCallback']);
```

Maybe you want to extends the Strategy interface :

```php
<?php

class MyStrategy implements IStrategy
{

    public function getName() {
        return 'MyStrategy';
    }

    public function detect() {
        return collator_create('fr-FR');
    }

}
```

Then, you add to register :

```php
$localeDetector->registerStrategy(new MyStrategy);

$localeDetector->setOrder(['MyStrategy']);
```