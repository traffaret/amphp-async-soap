<?php

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

/**
 * Class Wsdl
 *
 * @category amphp-async-soap
 * @package  Traff\Soap
 * @author   Oleg Tikhonov <to@toro.one>
 */
interface Wsdl
{
    /**
     * WSDL string representation for the SOAP client.
     *
     * @return \Amp\Promise<string>
     */
    public function toString(): Promise;
}
