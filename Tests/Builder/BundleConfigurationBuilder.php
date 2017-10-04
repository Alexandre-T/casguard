<?php
/**
 * This file is part of the PhpCAS Guard Bundle.
 *
 * PHP version 5.6 | 7.0 | 7.1
 *
 * (c) Alexandre Tranchant <alexandre.tranchant@gmail.com>
 *
 * @category Entity
 *
 * @author    Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @license   MIT
 *
 * @see https://github.com/Alexandre-T/casguard/blob/master/LICENSE
 */

namespace AlexandreT\Bundle\CasGuardBundle\Tests\Builder;

/**
 * Bundle Configuration Builder.
 *
 * @category AlexandreT\Bundle\CasGuardBundle\Tests\Builder
 *
 * @author  Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @license MIT
 */
class BundleConfigurationBuilder
{
    /**
     * Array configuration.
     *
     * @var array
     */
    private $configuration;

    /**
     * Create a minimaliste Configuration array.
     *
     * @return BundleConfigurationBuilder
     */
    public static function createBuilderWithDefaultValues()
    {
        return new self();
    }

    /**
     * Create a configuration which could works.
     *
     * @return BundleConfigurationBuilder
     */
    public static function createBuilderWithBaseValues()
    {
        $builder = new self();
        $builder->addDefaultValues();

        return $builder;
    }

    public function addBaseValues()
    {
        $this->addDefaultValues();
        $this->configuration['hostname'] = 'example.org';
        $this->configuration['uri_login'] = 'cas/login';

        return $this;
    }

    public function addDefaultValues()
    {
        $this->configuration['debug'] = false;
        $this->configuration['hostname'] = null;
        $this->configuration['repository'] = 'App:User';
        $this->configuration['port'] = 443;
        $this->configuration['property'] = 'username';
        $this->configuration['route_homepage'] = 'homepage';
        $this->configuration['route_login'] = 'security_login';
        $this->configuration['uri_login'] = null;
        $this->configuration['url'] = null;
        $this->configuration['version'] = CAS_VERSION_3_0;

        return $this;
    }

    public function build()
    {
        return $this->configuration;
    }
}
