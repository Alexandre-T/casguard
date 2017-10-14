Php CAS Bundle 
==============

PhpCas Bundle provide CAS Authentification using guard for symfony 2.8+, 3.x and 4.

This bundle **DO NOT** provide a CAS server. By using this bundle, your application will be able to use your Cas Server
to authenticate your users.  

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/cb0f5515-dc7a-4295-9faa-83e81fc1e23b/mini.png)](https://insight.sensiolabs.com/projects/cb0f5515-dc7a-4295-9faa-83e81fc1e23b)
[![Coverage Status](https://coveralls.io/repos/github/Alexandre-T/casguard/badge.svg?branch=master)](https://coveralls.io/github/Alexandre-T/casguard?branch=master)
[![Build Status](https://travis-ci.org/Alexandre-T/casguard.svg?branch=master)](https://travis-ci.org/Alexandre-T/casguard)

As you can see, unit test are covering 100% of code. 
Travis is helping me to confirm that this bundle can be used with PHP 5.6, 7.0 and 7.1 and 
Symfony 2.8, 3.0, 3.1, 3.2, 3.3 and 3.4. A flex component will be developed to help installation on latest symfony version.

I used it in a Symfony 3.3 application and another Symfony 3.4 application. If you experimented it with older versions,
do not hesitate to forward me your feedback. 
   

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
    # ...

```

Since Symfony 2.8, Guard is providing a new and excellent way to authenticate. I recommend you to have a look 
on [this excellent tutorial](https://knpuniversity.com/screencast/symfony-security) to understand guard features.
This tutorial explain how to generate a standard authentication using **guard** and a **login form**. When you 
understand it, you only have to disabled the call to the login form authenticator implemented in 
[the chapter 5](https://knpuniversity.com/screencast/symfony-security/login-form-authenticator) and replace it by 
the `phpcasguard.cas_authenticator` declared inside this bundle. 

Step 4: Configure the Bundle
----------------------------

For **Symfony 3.3** or less, add to your `config/parameters.yml` file the line above:

For **Symfony 3.4+**, create a `config\packages\cas_guard.yaml` file:

```yaml
cas_guard:
    hostname: '%env(CAS_HOSTNAME)%'
    # ...
```

Have a look on the [complete configuration](./Resources/doc/configuration.md) file to complete and 
connect it with your CAS server. 
