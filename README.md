# easyblueio/rules-engine

Provides tools for rules engine pattern

## Installation

Install this package as a dependency using [Composer](https://getcomposer.org).

``` bash
composer require easyblue/rules-engine
```


## Usage

### In PHP app

You can create an instance of rule engine with passing processor.

``` php
use Easyblueio\RulesEngine\Core\RulesEngine;
use Acme\RulesEngine\Profile;

$profilesRuleEngine = new RulesEngine(
    'profiles',
    ['chained' => true],
    [
        new Profile\FreemiumProcessor(),
        new Profile\PremiumProcessor(),
        new Profile\GoldProcessor(),
        new Profile\AdminProcessor(),
    ]
);

$profilesRuleEngine->process($user);
```

### In Symfony app

You can configure engines in `config/packages/rules_engine.{yaml|php|...}`

```yaml
rules_engine:
    engines:
        profile:
            chained: true
````

The key `profile` is the name of the engine that will process on each `rules_engine.profile.processor` tagged service.
You simply can extend `Easyblueio\RulesEngine\Core\ProcessorInterface` like this :

``` php
use Easyblueio\RulesEngine\Core\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('rules_engine.profile.processor')]
interface ProfileProcessorInterface extends ProcessorInterface {
}
```

An instance of `Easyblueio\RulesEngine\Core\RulesEngine` is available in the container, so you can inject it in your services.

``` php
use Easyblueio\RulesEngine\Core\RulesEngine;

final class ProfileController {
    public function __construct(private readonly RulesEngine $profileRulesEngine)
    {
    }
}
```

List all engines configured with `bin/console debug:autowiring rules_engine`


## Contributing

Contributions are welcome! To contribute, please familiarize yourself with
[CONTRIBUTING.md](CONTRIBUTING.md).


## Copyright and License

The easyblueio/rules-engine library is copyright © [Stello](mailto:dev@stello.eu)
and licensed for use under the terms of the
MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

