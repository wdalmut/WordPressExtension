<?php
namespace Corley;

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext;

use Behat\Behat\Event\SuiteEvent,
    Behat\Behat\Event\ScenarioEvent;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

class WordPressContext extends MinkContext
{
    private $paramters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;

    }

    /**
     * @BeforeSuite
     */
    public static function useWordPressConfiguration(SuiteEvent $event)
    {
        $parameters = $event->getContextParameters();

        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $_SERVER['HTTP_HOST'] = $parameters["wp"]["host"];
        $PHP_SELF = $GLOBALS['PHP_SELF'] = $_SERVER['PHP_SELF'] = '/index.php';

        require_once $parameters["wp"]["cms"] . '/wp-config.php';
    }

    /**
     * @Given /^\w+ have|has a vanilla wordpress installation$/
     */
    public function installWordPress()
    {
        $mysqli = new \Mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $value = $mysqli->multi_query(implode("\n", array(
            "DROP DATABASE IF EXISTS " . DB_NAME . ";",
            "CREATE DATABASE " . DB_NAME . ";",
        )));
        assertTrue($value);
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        wp_install( "BDD WP", 'admin', 'walter.dalmut@gmail.com', true, '', 'test' );
    }
}
