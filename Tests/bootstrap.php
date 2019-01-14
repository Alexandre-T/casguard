<?php
/**
 * This file is part of the CasGuardBundle.
 *
 * PHP version 7.1 | 7.2
 *
 * (c) Alexandre Tranchant <alexandre.tranchant@gmail.com>
 *
 * @category Test
 *
 * @author    Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @license   MIT
 *
 * @see https://github.com/Alexandre-T/casguard/blob/master/LICENSE
 */

/**
 * This file autoload the class and bootstrap the tests by creating a kernel for aspect mock.
 *
 * @category Test
 *
 * @author    Alexandre Tranchant <alexandre.tranchant@gmail.com>
 * @license   MIT
 */
include __DIR__.'/../vendor/autoload.php'; // composer autoload

$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
    'debug' => true,
    'appDir' => __DIR__.'/..',
    'cacheDir' => 'build/cache',
    'includePaths' => [
        __DIR__.'/../vendor/jasig/phpcas',
        __DIR__.'/../vendor/codeception',
        __DIR__.'/../vendor/codeception/aspect-mock',
        __DIR__.'/../vendor/codeception/aspect-mock/tests',
    ],
]);
