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

use Amp\Http\Client\HttpClient;
use Amp\Http\Client\Request;
use Amp\Promise;
use Traff\Soap\Options;
use function Amp\call;

/**
 * Class RequestBuilder
 *
 * @category async-soap
 * @package  Traff\Soap
 * @author   Oleg Tikhonov <to@toro.one>
 */
abstract class RequestBuilder
{
    private $http_method;
    private $options;
    private $request;

    abstract public function build(string $uri, Options $options): Request;

    abstract protected function getHeaders(Options $options): array;

    private function getAuthenticationHeaders(?string $login, ?string $password, int $authentication): array
    {
        $headers = [];
        if (! empty($login) && ! empty($password)) {
            if (Options::SOAP_AUTHENTICATION_BASIC === $authentication) {
                $auth_string = \base64_encode(sprintf('%s:%s', $login, $password));
                $headers[] = ['Authorization', \sprintf('Basic %s', $auth_string)];
            }
        }

        return $headers;
    }

    public function __construct(HttpClient $http_client, Options $options)
    {
        $this->http_method = $http_client;
        $this->options = $options;
    }

    public function getRequest(): Request
    {
        if (null === $this->request) {
            throw new \Error('Request was not performed');
        }
        return $this->request;
    }

    public function request(string $uri, ?string $body = null, array $input_headers = null): Promise
    {
        $request = $this->build($uri, $this->options);

        $headers = [$input_headers ?? []];
        $headers[] = $this->getAuthenticationHeaders(
            $this->options->getLogin(), $this->options->getPassword(), $this->options->getAuthentication()
        );
        $headers[] = $this->getHeaders($this->options);
        $headers = \array_filter($headers);

        $headers = \array_merge(...$headers);
        foreach ($headers as [$header, $value]) {
            $request->addHeader($header, $value);
        }

        if (null !== $body) {
            $request->setBody($body);
        }

        $this->request = $request;

        return call(function () use ($request): \Generator {
            $response = yield $this->http_method->request($request);
            return yield $response->getBody()->buffer();
        });
    }
}
