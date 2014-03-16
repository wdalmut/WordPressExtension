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

        require_once $parameters["wp"]["path"] . '/wp-config.php';
    }

    /**
     * @Given /^\w+ have|has a vanilla wordpress installation$/
     */
    public function installWordPress(TableNode $table = null)
    {
        $name = "admin";
        $email = "an@example.com";
        $password = "test";
        $username = "admin";

        if ($table) {
            $row = $table->getHash()[0];
            $name = $row["name"];
            $username = $row["username"];
            $email = $row["email"];
            $password = $row["password"];
        }

        $mysqli = new \Mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $value = $mysqli->multi_query(implode("\n", array(
            "DROP DATABASE IF EXISTS " . DB_NAME . ";",
            "CREATE DATABASE " . DB_NAME . ";",
        )));
        assertTrue($value);
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        wp_install($name, $username, $email, true, '', $password);
    }

    /**
     * @Given /^there are users$/
     */
    public function thereAreUsers(TableNode $table)
    {
        foreach ($table->getHash() as $userData) {
            if (!is_int(wp_insert_user($userData))) {
                throw new \InvalidArgumentException("Invalid user data");
            }
        }
    }

    /**
     * @Given /^I am logged in as "([^"]*)" with password "([^"]*)"$/
     */
    public function login($username, $password)
    {
        $this->visit("wp-login.php");
        $currentPage = $this->getSession()->getPage();

        $currentPage->fillField('user_login', $username);
        $currentPage->fillField('user_pass', $password);
        $currentPage->findButton('wp-submit')->click();

        assertTrue($this->getSession()->getPage()->hasContent('Dashboard'));
    }

}
