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

namespace Traff\Soap;

/**
 * Represent WSDL string for the SOAP client.
 *
 * @param string $wsdl WSDL content.
 *
 * @return string
 */
function plainWsdl(string $wsdl): string
{
    $prefix = 'data://text/plain;base64';

    if (! \str_contains($prefix, $wsdl)) {
        return \sprintf('%s,%s', $prefix, base64_encode($wsdl));
    }

    return $wsdl;
}
