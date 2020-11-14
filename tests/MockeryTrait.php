<?php

/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.4
 *
 * @category amphp-async-soap
 * @author   Oleg Tikhonov <o.tikhonov@nexta.pro>
 */

declare(strict_types=1);

namespace Traff\Soap\Test;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

/**
 * Trait MockeryTrait.
 *
 */
trait MockeryTrait
{
    use MockeryPHPUnitIntegration;

    protected function tearDown(): void
    {
        \Mockery::close();
    }

    protected function tearDownAsync(): void
    {
        \Mockery::close();
    }
}
