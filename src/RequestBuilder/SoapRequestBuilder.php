<?php declare(strict_types=1);
/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.3
 *
 * @category async-soap
 * @author   Oleg Tikhonov <to@toro.one>
 */

namespace Traff\Soap\RequestBuilder;

use Amp\Http\Client\Request;
use Traff\Soap\Options;

/**
 * Class SoapRequestBuilder
 *
 * @category async-soap
 * @package  Traff\Soap
 * @author   Oleg Tikhonov <to@toro.one>
 */
final class SoapRequestBuilder extends RequestBuilder
{
    public function build(string $uri, Options $options): Request
    {
        $request = new Request($uri, 'POST');
        $request->setTcpConnectTimeout(20000); // TODO:
        $request->setTransferTimeout(20000); // TODO:
        $request->setInactivityTimeout(20000); // TODO:
        $request->setTlsHandshakeTimeout(20000); // TODO:
        return $request;
    }

    protected function getHeaders(Options $options): array
    {
        return [];
    }
}
