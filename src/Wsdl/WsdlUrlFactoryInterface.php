<?php

/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.3
 *
 * @category amphp-async-soap
 * @author   Oleg Tikhonov <to@toro.one>
 */

declare(strict_types=1);

namespace Traff\Soap\Wsdl;

use Traff\Soap\Options;

/**
 * Factory to create wsdl with url location.
 *
 * @package Traff\Soap\Wsdl
 */
interface WsdlUrlFactoryInterface
{
    /**
     * Creates wsdl object.
     *
     * @param string                   $url
     * @param \Traff\Soap\Options|null $options
     *
     * @return \Traff\Soap\Wsdl\Wsdl
     */
    public function createWsdl(string $url, ?Options $options = null): Wsdl;
}
