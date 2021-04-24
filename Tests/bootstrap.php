<?php
/**
 * This file is part of the CasGuardBundle.
 *
 * PHP version 7.3 | 7.4 | 8.0
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

use AspectMock\Kernel;

include __DIR__.'/../vendor/autoload.php'; // composer autoload

$kernel = Kernel::getInstance();
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
