<?php

declare(strict_types=1);

/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.3
 *
 * @category amphp-async-soap
 * @author   Oleg Tikhonov <to@toro.one>
 */

namespace Traff\Soap\Wsdl;

use Amp\Promise;
use Amp\Success;

/**
 * Class WsdlPath
 *
 * @category amphp-async-soap
 * @package  Traff\Soap
 * @author   Oleg Tikhonov <to@toro.one>
 */
class WsdlPath implements Wsdl
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function toString(): Promise
    {
        return new Success($this->path);
    }
}
