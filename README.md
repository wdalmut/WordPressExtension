# WordPress Extension for Behat

Just a WordPress extension for Behat

## Install

Prepare your composer

```json
{
    "require": {
        "wdalmut/wordpress-extension": "*"
    }
}
```

## Configuration

```yml
# behat.yml
default:
    extensions:
        Behat\MinkExtension\Extension:
            base_url:    'http://wp.127.0.0.1.xip.io'
            goutte:      ~
            show_cmd: "firefox %s"
        Corley\WordPressExtension\Extension:
            path: "../wordpress"

```

## Add a base WordPress context

Just add the WordPress context

```php
use Corley\WordPressExtension\Context\WordPressContext;

class FeatureContext extends BehatContext
{
    public function __construct(array $parameters)
    {
        //...
        $this->useContext('wordpress', new WordPressContext);
    }
}
```

## Tips

```
disable_functions=mail
```

Disable `mail()` function

