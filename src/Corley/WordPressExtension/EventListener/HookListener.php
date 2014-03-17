<?php
/**
 * @license MIT
 */

namespace Corley\WordPressExtension\EventListener;

use Behat\Behat\Event\FeatureEvent;
use Behat\Behat\Event\OutlineExampleEvent;
use Behat\Behat\Event\ScenarioEvent;
use Behat\Behat\Event\SuiteEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class HookListener implements EventSubscriberInterface
{
    private $path;
    private $minkParams;

    public function __construct($path, $minkParams)
    {
        $this->path = $path;
        $this->minkParams = $minkParams;
    }

    public static function getSubscribedEvents()
    {
        $events = array(
            'beforeSuite',
        );

        return array_combine($events, $events);
    }

    public function beforeSuite(SuiteEvent $event)
    {
        $url = parse_url($this->minkParams["base_url"]);

        $_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
        $_SERVER['HTTP_HOST'] = $url["host"];
        $PHP_SELF = $GLOBALS['PHP_SELF'] = $_SERVER['PHP_SELF'] = '/index.php';

        require_once $this->path . '/wp-config.php';
    }
}
