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
use Traff\Soap\RequestBuilder\RequestBuilder;
use function Amp\call;

/**
 * Class WsdlUrl
 *
 * @category amphp-async-soap
 * @package  Traff\Soap
 * @author   Oleg Tikhonov <to@toro.one>
 */
final class WsdlUrl implements Wsdl
{
    private $url;
    private $builder;

    public function __construct(string $url, RequestBuilder $builder)
    {
        $this->url = $url;
        $this->builder = $builder;
    }

    public function __destruct()
    {
        unset($this->builder, $this->url);
    }

    public function toString(): Promise
    {
        return call(function (): \Generator {
            $response = yield $this->builder->request($this->url);
            // TODO: import externals
            return sprintf('data://text/plain;base64,%s', base64_encode($response));
        });
    }
}
