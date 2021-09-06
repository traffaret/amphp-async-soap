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

use Amp\Http\Client\DelegateHttpClient;
use Traff\Soap\Message\DefaultSoapMessageFactory;
use Traff\Soap\Message\SoapMessageFactoryInterface;
use Traff\Soap\RequestBuilder\RequestBuilder;
use Traff\Soap\RequestBuilder\SoapRequestBuilder;

/**
 * Class SoapTransportBuilder.
 *
 * @package Traff\Soap
 */
final class SoapTransportBuilder
{
    /** @var string|null */
    private $wsdl;

    /** @var \Amp\Http\Client\EventListener[] */
    private $event_listeners;

    /** @var \Amp\Http\Client\DelegateHttpClient */
    private $http_client;

    /** @var \Traff\Soap\Options */
    private $options;

    public function __construct(
        private ?RequestBuilder $request_builder = null,
        private ?SoapMessageFactoryInterface $message_factory = null
    ) {
    }

    public function __destruct()
    {
        unset(
            $this->message_factory,
            $this->request_builder
        );
    }

    public function withWsdl(string $wsdl): self
    {
        if (empty($wsdl)) {
            throw new \InvalidArgumentException('WSDL can not be empty');
        }

        $new = clone $this;
        $new->wsdl = $wsdl;

        return $new;
    }

    public function withoutWsdl(): self
    {
        $new = clone $this;
        $new->wsdl = null;

        return $new;
    }

    public function withEventListeners(...$event_listeners): self
    {
        $new = clone $this;
        $new->event_listeners = $event_listeners;

        return $new;
    }

    public function withHttpClient(DelegateHttpClient $http_client): self
    {
        $new = clone $this;
        $new->http_client = $http_client;

        return $new;
    }

    public function withOptions(Options $options): self
    {
        $new = clone $this;
        $new->options = $options;

        return $new;
    }

    public function build(): SoapTransport
    {
        $request_builder = $this->request_builder;

        if (null !== $this->http_client) {
            $request_builder = $request_builder->withHttpClient($this->http_client);
        }

        if (null !== $this->event_listeners) {
            $request_builder = $request_builder->withEventListeners(...$this->event_listeners);
        }

        if (null !== $this->options) {
            $request_builder = $request_builder->withOptions($this->options);
        }

        return new SoapTransport(
            $this->message_factory->createMessage($this->wsdl, $request_builder->getOptions()),
            $request_builder
        );
    }
}
