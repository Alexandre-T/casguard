<?php
/**
 * This file is part of the casguard.
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
include __DIR__.'/../vendor/autoload.php'; // composer autoload

$kernel = \AspectMock\Kernel::getInstance();
$kernel->init([
    'debug' => true,
    'includePaths' => [
        __DIR__.'/../vendor/jasig/phpcas',
    ],
]);
