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

namespace Traff\Soap\RequestBuilder;

use Amp\Http\Client\Request;
use Traff\Soap\Options;

/**
 * Class WsdlRequestBuilder
 *
 * @category amphp-async-soap
 * @package  Traff\Soap
 * @author   Oleg Tikhonov <to@toro.one>
 */
class WsdlRequestBuilder extends RequestBuilder
{
    public function build(string $uri, Options $options): Request
    {
        return new Request($uri, 'GET');
    }

    protected function getHeaders(Options $options): array
    {
        return [
            ['Content-Type', 'text/xml; charset=utf-8;'],
        ];
    }
}
