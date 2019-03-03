<?php

$a = sha1_file("./screenshots/scr1.png");
$b = sha1_file("./screenshots/scr2.png");

echo $a . "<br>" . $b;

die("!!");
// https://ericdraken.com/automated-web-testing-php-phpunit-selenium/
 
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverPoint;
 
$composer_dir = '/Users/toninichev/composer';
require_once $composer_dir . '/vendor/autoload.php';
 
// Use the remote addr to locate where javaw is running
$host = 'http://127.0.0.1:4444/wd/hub'; // this is the default port
 
echo "WebDriver: $host<br>";
 

// See all the capabilities here: https://github.com/SeleniumHQ/selenium/wiki/DesiredCapabilities
$driver = RemoteWebDriver::create($host, DesiredCapabilities::chrome());
 
// Set size
$driver->manage()->window()->setPosition(new WebDriverPoint(0,0));
$driver->manage()->window()->setSize(new WebDriverDimension(1280,800));
 
// Navigate to the page
$driver->get('https://www.toni-develops.com/');
 
// Wait at most 10s until the page is loaded
$driver->wait(10)->until(
    WebDriverExpectedCondition::titleContains('Home - Toni-Develops')
);
 
// Click the link 'About Eric'
$link = $driver->findElement(
    WebDriverBy::linkText('Webpack')
);
$link->click();
 
// Wait at most 10s until the page is loaded
$driver->wait(10)->until(
    WebDriverExpectedCondition::titleContains('Webpack, Babel, React, Redux, Apollo. From scratch to the production stack. - Toni-Develops')
);
 
// Print the title of the current page
echo "The title is " . $driver->getTitle() . "<br>";
 
// Take a screenshot
$driver->takeScreenshot(__DIR__ . "/screenshots/scr2.png");
 
// Close the Chrome browser
$driver->quit();
 
// Display the screenshot
?><img src="./screenshots/scr2.png">
