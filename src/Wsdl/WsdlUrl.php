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

use Amp\Promise;
use Traff\Soap\RequestBuilder\RequestBuilder;

use function Amp\call;
use function Traff\Soap\plainWsdl;

/**
 * Class WsdlUrl
 *
 * @category amphp-async-soap
 * @package  Traff\Soap
 * @author   Oleg Tikhonov <to@toro.one>
 */
final class WsdlUrl implements Wsdl
{
    /** @var string  */
    private $url;

    /** @var \Traff\Soap\RequestBuilder\RequestBuilder */
    private $builder;

    /**
     * WsdlUrl constructor.
     *
     * @param string                                    $url     WSDL url location.
     * @param \Traff\Soap\RequestBuilder\RequestBuilder $builder WSDL request builder.
     *
     */
    public function __construct(string $url, RequestBuilder $builder)
    {
        $this->url = $url;
        $this->builder = $builder;
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->builder);
    }

    /** @inheritDoc */
    public function toString(): Promise
    {
        return call(
            function (): \Generator {
                return plainWsdl(yield $this->builder->request($this->url));
            }
        );
    }
}
