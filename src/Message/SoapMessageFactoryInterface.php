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

namespace Traff\Soap\Message;

use Traff\Soap\Options;

/**
 * SOAP message wrapper factory interface.
 *
 * @package Traff\Soap\Message
 */
interface SoapMessageFactoryInterface
{
    /**
     * Creates SOAP message wrapper around SOAP client.
     *
     * @param string|null         $wsdl    WSDL string or null.
     * @param \Traff\Soap\Options $options SOAP client options.
     *
     * @return \Traff\Soap\Message\SoapMessageInterface
     */
    public function createMessage(?string $wsdl, Options $options): SoapMessageInterface;
}
