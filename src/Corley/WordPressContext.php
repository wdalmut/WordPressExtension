<?php
namespace Corley;

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext;

require_once 'PHPUnit/Autoload.php';
require_once 'PHPUnit/Framework/Assert/Functions.php';

class WordPressContext extends MinkContext
{
    private $paramters;

    public function __construct($parameters)
    {
        $this->parameters = $parameters;

        (!defined('WP_INSTALLING')) ? define('WP_INSTALLING', true) : '';

        (!defined('ABSPATH')) ? define('ABSPATH', $parameters["cms"] . '/') : '';
        (!defined('WP_CONTENT_DIR')) ? define('WP_CONTENT_DIR', $parameters["cms"] . "/") : '';
        (!defined('WP_DEBUG')) ? define('WP_DEBUG', true) : '';

        (!defined('DB_NAME')) ? define('DB_NAME', $parameters["db"]["name"]) : '';
        (!defined('DB_USER')) ? define('DB_USER', $parameters["db"]["user"]) : '';
        (!defined('DB_PASSWORD')) ? define('DB_PASSWORD', $parameters["db"]["pass"]) : '';
        (!defined('DB_HOST')) ? define('DB_HOST', $parameters["db"]["host"]) : '';
        (!defined('DB_CHARSET')) ? define('DB_CHARSET', 'utf8') : '';
        (!defined('DB_COLLATE')) ? define('DB_COLLATE', '') : '';

        $table_prefix  = 'wp_';   // Only numbers, letters, and underscores please!

        (!defined('WP_TESTS_DOMAIN')) ? define('WP_TESTS_DOMAIN', 'example.org') : '';
        (!defined('WP_TESTS_EMAIL')) ? define('WP_TESTS_EMAIL', 'admin@example.org') : '';
        (!defined('WP_TESTS_TITLE')) ? define('WP_TESTS_TITLE', 'Test Blog') : '';

        (!defined('WP_PHP_BINARY')) ? define('WP_PHP_BINARY', 'php') : '';

        (!defined('WPLANG')) ? define('WPLANG', '') : '';

        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $_SERVER['HTTP_HOST'] = $parameters["host"];
        $PHP_SELF = $GLOBALS['PHP_SELF'] = $_SERVER['PHP_SELF'] = '/index.php';

        $mysqli = new \Mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $value = $mysqli->multi_query(implode("\n", array(
            "DROP DATABASE IF EXISTS " . DB_NAME . ";",
            "CREATE DATABASE " . DB_NAME . ";",
        )));
        assertTrue($value);

        ob_start();
        require_once(ABSPATH . 'wp-settings.php');
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        ob_get_clean();
    }

    /**
     * @Given /^\w+ have|has a vanilla wordpress installation$/
     */
    public function installWordPress()
    {
        wp_install( "BDD WP", 'admin', 'walter.dalmut@gmail.com', true, '', 'test' );
    }
}
