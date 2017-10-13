Php CAS Bundle 
==============

PhpCas Bundle provide CAS Authentification using guard for symfony 2.8+, 3.x and 4

Current version under development. Version 1.0.0 should be available for december 2017. 

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/cb0f5515-dc7a-4295-9faa-83e81fc1e23b/mini.png)](https://insight.sensiolabs.com/projects/cb0f5515-dc7a-4295-9faa-83e81fc1e23b)
[![Coverage Status](https://coveralls.io/repos/github/Alexandre-T/casguard/badge.svg?branch=master)](https://coveralls.io/github/Alexandre-T/casguard?branch=master)
[![Build Status](https://travis-ci.org/Alexandre-T/casguard.svg?branch=master)](https://travis-ci.org/Alexandre-T/casguard)

Code tested with PHP 5.6, 7.0 and 7.1 and for Symfony 2.8, 3.0, 3.1, 3.2, 3.3 and 3.4  

Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require "alexandret/phpcas-guard-bundle" "~1"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

For **Symfony 3.3 or less**, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new AlexandreT\Bundle\CasGuardBundle\CasGuardBundle(),
        );

        // ...
    }

    // ...
}
```

For **Symfony 3.4+**, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.php` file of your project:

```php
<?php

return [
    //...
    AlexandreT\Bundle\CasGuardBundle\CasGuardBundle::class => ['all' => true],
];
```

Step 3: Enable the Security
----------------------------

For **Symfony 3.3 or less**, update your `security.yml` file:

For **Symfony 3.4+**, update your `config\packages\security.yaml` file:

```yaml
#http://symfony.com/doc/current/reference/configuration/security.html#full-default-configuration
security:
    # ...
    firewalls:
        #Main firewall
        main:
            # We use Guard !
            guard:
                authenticators:
                    # ADD the cas authenticator declared in this bundle
                    - phpcasguard.cas_authenticator
            # The logout path
            logout:
                # This route will be never called because of listener. It will catch it and redirect user.                
                path: /logout
                # ADD the same cas authenticator declared in this bundle to activate logout function
                success_handler: phpcasguard.cas_authenticator  

```

Step 4: Configure the Bundle
----------------------------

For **Symfony 3.3** or less, add to your `services.yml file` the line above:

For **Symfony 3.4+**, create a `config\packages\cas_guard.yaml` file:

```yaml
cas_guard:
    hostname: '%env(CAS_HOSTNAME)%'
    uri_login: '%env(CAS_URL)%'
    version: "3.0"
    # ...

```

Have a look on the [complete configuration](./Resources/doc/configuration.md) file to 
customize configuration to use your CAS server. 