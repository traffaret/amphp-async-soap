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

namespace Traff\Soap\RequestBuilder;

use Amp\CancellationToken;
use Amp\Http\Client\Request;
use Amp\NullCancellationToken;
use Traff\Soap\Options;

/**
 * SOAP request builder.
 *
 * @category amphp-async-soap
 * @package  Traff\Soap
 * @author   Oleg Tikhonov <to@toro.one>
 */
final class SoapRequestBuilder extends RequestBuilder
{
    /** @inheritDoc */
    public function build(string $uri, Options $options): Request
    {
        return new Request($uri, 'POST');
    }

    /** @inheritDoc */
    public function createRequestCancellationToken(): CancellationToken
    {
        return new NullCancellationToken();
    }

    /** @inheritDoc */
    protected function getHeaders(Options $options): array
    {
        return [];
    }
}
