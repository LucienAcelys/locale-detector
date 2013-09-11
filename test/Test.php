<?php

@session_start();

/**
 * Created by JetBrains PhpStorm.
 * User: lucien
 * Date: 29/08/13
 * Time: 09:32
 * To change this template use File | Settings | File Templates.
 */

class Test extends PHPUnit_Framework_TestCase
{

    public function testHeader()
    {
        $header = new Menencia\LocaleDetector\Strategy\Header();

        $localeFr = collator_create('fr-FR');

        $this->assertNull($header->detect());

        $_SERVER["HTTP_ACCEPT_LANGUAGE"] = 'fr-FR';

        $locale = $header->detect();
        $this->assertEquals($localeFr, $locale);

        $_SERVER["HTTP_ACCEPT_LANGUAGE"] = null;

        $this->assertNull($header->detect());

        $_SERVER["HTTP_ACCEPT_LANGUAGE"] = '';

        $this->assertNull($header->detect());
    }

    public function testCookie()
    {
        $cookie = new Menencia\LocaleDetector\Strategy\Cookie();

        $localeFr = collator_create('fr-FR');

        $this->assertNull($cookie->detect());

        $_COOKIE['lang'] = 'fr-FR';

        $locale = $cookie->detect();
        $this->assertEquals($localeFr, $locale);

        $_COOKIE['lang'] = '';

        $this->assertNull($cookie->detect());

    }

    public function testTLD()
    {
        $tld = new Menencia\LocaleDetector\Strategy\TLD();

        $localeFr = collator_create('fr-FR');

        $this->assertNull($tld->detect());

        $_SERVER["SERVER_NAME"] = 'www.example.fr';
        $locale = $tld->detect();

        $this->assertEquals($localeFr, $locale);

        $_SERVER["SERVER_NAME"] = 'www.example.example';
        $this->assertNull($tld->detect());

        $_SERVER["SERVER_NAME"] = '';
        $this->assertNull($tld->detect());

    }

    public function testNSession()
    {
        $session = new Menencia\LocaleDetector\Strategy\NSession();

        $localeFr = collator_create('fr-FR');

        $this->assertNull($session->detect());

        $_SESSION['lang'] = 'fr-FR';

        $locale = $session->detect();
        $this->assertEquals($localeFr, $locale);

        $_SESSION['lang'] = '';
        $this->assertNull($session->detect());

    }

    public function testIP()
    {
        $session = new Menencia\LocaleDetector\Strategy\IP();

        $localeFr = collator_create('fr-FR');

        $this->assertNull($session->detect());

        $_SERVER['REMOTE_ADDR'] = '94.103.129.132';

        $locale = $session->detect();
        $this->assertEquals($localeFr, $locale);

        $_SERVER['REMOTE_ADDR'] = '';
        $this->assertNull($session->detect());

    }

    public function testCustom()
    {
        $localeDetector = new Menencia\LocaleDetector\LocaleDetector();

        $localeDetector->registry('OtherStrategy', function(){
           return collator_create('fr-FR');
        });

        $localeDetector->setOrder(['custom:OtherStrategy']);

        $locale = $localeDetector->detect();

        $this->assertEquals('fr_FR', $locale);

    }

}