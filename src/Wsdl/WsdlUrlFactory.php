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

use Amp\Http\Client\DelegateHttpClient;
use Traff\Soap\Options;
use Traff\Soap\RequestBuilder\WsdlRequestBuilder;

/**
 * Factory to create wsdl with url.
 *
 * @package Traff\Soap\Wsdl
 */
final class WsdlUrlFactory implements WsdlUrlFactoryInterface
{
    /** @inheritDoc */
    public function createWsdl(string $url, ?Options $options = null, ?DelegateHttpClient $http_client = null): Wsdl
    {
        return new WsdlUrl($url, new WsdlRequestBuilder($options, $http_client));
    }
}
