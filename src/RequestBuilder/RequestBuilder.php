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
use Amp\Http\Client\DelegateHttpClient;
use Amp\Http\Client\HttpClientBuilder;
use Amp\Http\Client\Request;
use Amp\Promise;
use Traff\Soap\Options;

use function Amp\call;

/**
 * Abstract class to build requests.
 *
 * @category amphp-async-soap
 * @package  Traff\Soap
 * @author   Oleg Tikhonov <to@toro.one>
 */
abstract class RequestBuilder
{
    /** @var \Amp\Http\Client\DelegateHttpClient */
    private $http_client;

    /** @var \Traff\Soap\Options */
    private $options;

    /** @var \Amp\Http\Client\Request|null */
    private $request;

    /** @var \Amp\Http\Client\EventListener[] */
    private $event_listeners = [];

    /**
     * Request cancellation token.
     *
     * @return \Amp\CancellationToken
     */
    abstract public function createRequestCancellationToken(): CancellationToken;

    /**
     * Request headers.
     *
     * @param \Traff\Soap\Options $options Request options.
     *
     * @return array
     */
    abstract protected function getHeaders(Options $options): array;

    /**
     * Build HTTP request.
     *
     * @param string              $uri     URI string.
     * @param \Traff\Soap\Options $options Request options.
     *
     * @return \Amp\Http\Client\Request
     */
    abstract public function build(string $uri, Options $options): Request;

    /**
     * RequestBuilder constructor.
     *
     * @param \Traff\Soap\Options|null                 $options     Options.
     * @param \Amp\Http\Client\DelegateHttpClient|null $http_client HTTP-client.
     */
    public function __construct(?Options $options = null, ?DelegateHttpClient $http_client = null)
    {
        $this->http_client = $http_client ?? HttpClientBuilder::buildDefault();
        $this->options = $options ?? new Options();
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset(
            $this->http_client,
            $this->options,
            $this->request
        );
    }

    /**
     * Get request object.
     *
     * @return \Amp\Http\Client\Request
     */
    public function getRequest(): Request
    {
        if (null === $this->request) {
            throw new \Error('Request was not performed');
        }

        return $this->request;
    }

    /**
     * Get options.
     *
     * @return \Traff\Soap\Options
     */
    public function getOptions(): Options
    {
        return $this->options;
    }

    /**
     * Perform request.
     *
     * @param string      $uri           URI string.
     * @param string|null $body          Request body.
     * @param array|null  $input_headers Additional request headers.
     *
     * @return \Amp\Promise<string>
     */
    public function request(string $uri, ?string $body = null, array $input_headers = null): Promise
    {
        return call(
            function () use ($uri, $body, $input_headers): \Generator {
                $request = $this->build($uri, $this->options);

                $this->setRequestTimeout($request);

                $headers = [$input_headers ?? []];
                $headers[] = $this->getAuthenticationHeaders(
                    $this->options->getLogin(),
                    $this->options->getPassword(),
                    $this->options->getAuthentication()
                );

                $headers[] = $this->getHeaders($this->options);
                $headers = \array_filter($headers);
                $headers = \array_merge(...$headers);

                foreach ($headers as [$header, $value]) {
                    $request->addHeader($header, $value);
                }

                if (! empty($this->event_listeners)) {
                    foreach ($this->event_listeners as $event_listener) {
                        $request->addEventListener($event_listener);
                    }
                }

                if (null !== $body) {
                    $request->setBody($body);
                }

                $response = yield $this->http_client->request($request, $this->createRequestCancellationToken());
                $this->request = $response->getRequest();

                return yield $response->getBody()->buffer();
            }
        );
    }

    /**
     * Use http client.
     *
     * @param \Amp\Http\Client\DelegateHttpClient $http_client HTTP client.
     *
     * @return self
     */
    public function withHttpClient(DelegateHttpClient $http_client): self
    {
        $new = clone $this;
        $new->http_client = $http_client;

        return $new;
    }

    /**
     * Add event listeners when request is performed.
     *
     * @param \Amp\Http\Client\EventListener[] $event_listeners Event listeners.
     *
     * @return self
     */
    public function withEventListeners(...$event_listeners): self
    {
        $new = clone $this;
        $new->event_listeners = $event_listeners;

        return $new;
    }

    /**
     * Builds request with options.
     *
     * @param \Traff\Soap\Options $options New options.
     *
     * @return self
     */
    public function withOptions(Options $options): self
    {
        $new = clone $this;
        $new->options = $options;

        return $new;
    }

    /**
     * Set request timeouts.
     *
     * It can be passed through Options::withConnectionTimeout
     *
     * @param \Amp\Http\Client\Request $request Request object.
     *
     * @return void
     */
    private function setRequestTimeout(Request $request): void
    {
        $timeout = $this->options->getConnectionTimeout() * 1000;

        $request->setTcpConnectTimeout($timeout);
        $request->setTransferTimeout($timeout);
        $request->setInactivityTimeout($timeout);
        $request->setTlsHandshakeTimeout($timeout);
    }

    /**
     * Prepare authentication headers to request.
     *
     * @param string|null $login          Login string.
     * @param string|null $password       Password string.
     * @param int         $authentication Authentication method.
     *
     * @return array
     */
    private function getAuthenticationHeaders(?string $login, ?string $password, int $authentication): array
    {
        $headers = [];

        if (! empty($login) && ! empty($password)) {
            if (Options::SOAP_AUTHENTICATION_BASIC === $authentication) {
                $auth_string = \base64_encode(sprintf('%s:%s', $login, $password));
                $headers[] = ['Authorization', \sprintf('Basic %s', $auth_string)];
            } else {
                throw new \Error(\sprintf('Authentication type "%s" is not allowed', $authentication));
            }
        }

        return $headers;
    }
}
