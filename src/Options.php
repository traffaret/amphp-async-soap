<?php

/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.3
 *
 * @category amphp-async-soap
 * @author   Oleg Tikhonov <to@toro.one>
 */

/** @noinspection PhpUnused */

declare(strict_types=1);

namespace Traff\Soap;

use Amp\Socket\Certificate;

/**
 * Class Options
 *
 * @category amphp-async-soap
 * @package  Traff\Soap
 * @author   Oleg Tikhonov <to@toro.one>
 */
final class Options
{
    public const SOAP_ENCODING_UTF_8 = 'utf-8';

    public const SOAP_VERSION_1_1 = \SOAP_1_1;

    public const SOAP_VERSION_1_2 = \SOAP_1_2;

    public const SOAP_COMPRESSION_GZIP = \SOAP_COMPRESSION_GZIP;

    public const SOAP_COMPRESSION_ACCEPT = \SOAP_COMPRESSION_ACCEPT;

    public const SOAP_COMPRESSION_DEFLATE = \SOAP_COMPRESSION_DEFLATE;

    public const SOAP_STYLE_RPC = \SOAP_RPC;

    public const SOAP_STYLE_DOCUMENT = \SOAP_DOCUMENT;

    public const SOAP_USE_ENCODED = \SOAP_ENCODED;

    public const SOAP_USE_LITERAL = \SOAP_LITERAL;

    public const SOAP_AUTHENTICATION_BASIC = \SOAP_AUTHENTICATION_BASIC;

    public const SOAP_AUTHENTICATION_DIGEST = \SOAP_AUTHENTICATION_DIGEST;

    /** @var string|null */
    private $location;

    /** @var int */
    private $soap_version = self::SOAP_VERSION_1_1;

    /** @var string|null */
    private $login;

    /** @var string|null */
    private $password;

    /** @var \Amp\Socket\Certificate|null */
    private $local_cert;

    /** @var int */
    private $authentication = self::SOAP_AUTHENTICATION_BASIC;

    /** @var int */
    private $compression = self::SOAP_COMPRESSION_GZIP;

    /** @var string */
    private $user_agent = 'traff-soap-async';

    /** @var int */
    private $style = self::SOAP_STYLE_RPC;

    /** @var string|null */
    private $uri;

    /** @var bool */
    private $exceptions = false;

    /** @var int */
    private $cache_wsdl = \WSDL_CACHE_NONE;

    /** @var string */
    private $encoding = self::SOAP_ENCODING_UTF_8;

    /** @var int */
    private $use = self::SOAP_USE_LITERAL;

    /** @var string|null */
    private $passphrase;

    /** @var int */
    private $connection_timeout = 10;

    /**
     * Connection timeout.
     *
     * @return int
     */
    public function getConnectionTimeout(): int
    {
        return $this->connection_timeout;
    }

    /**
     * Service location.
     *
     * @return string|null
     */
    public function getLocation(): ?string
    {
        return $this->location;
    }

    /**
     * SOAP version.
     *
     * @return int
     */
    public function getSoapVersion(): int
    {
        return $this->soap_version;
    }

    /**
     * Login.
     *
     * @return string|null
     */
    public function getLogin(): ?string
    {
        return $this->login;
    }

    /**
     * Password.
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * SSL certificate.
     *
     * @return \Amp\Socket\Certificate|null
     */
    public function getLocalCert(): ?Certificate
    {
        return $this->local_cert;
    }

    /**
     * Authentication type.
     *
     * @return int
     */
    public function getAuthentication(): int
    {
        return $this->authentication;
    }

    /**
     * Compression type.
     *
     * @return int
     */
    public function getCompression(): int
    {
        return $this->compression;
    }

    /**
     * User agent.
     *
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->user_agent;
    }

    /**
     * URI.
     *
     * @return string|null
     */
    public function getUri(): ?string
    {
        return $this->uri;
    }

    /**
     * SOAP document style.
     *
     * @return int
     */
    public function getStyle(): int
    {
        return $this->style;
    }

    /**
     * WSDL cache type.
     *
     * @return int
     */
    public function getCacheWsdl(): int
    {
        return $this->cache_wsdl;
    }

    /**
     * Exceptions.
     *
     * @return bool
     */
    public function getExceptions(): bool
    {
        return $this->exceptions;
    }

    /**
     * Encoding.
     *
     * @return string
     */
    public function getEncoding(): string
    {
        return $this->encoding;
    }

    /**
     * Use type.
     *
     * @return int
     */
    public function getUse(): int
    {
        return $this->use;
    }

    /**
     * Certificate passphrase.
     *
     * @return string|null
     */
    public function getPassphrase(): ?string
    {
        return $this->passphrase;
    }

    /**
     * Check basic authentication type.
     *
     * @return bool
     */
    public function isBasicAuthentication(): bool
    {
        return self::SOAP_AUTHENTICATION_BASIC === $this->authentication;
    }

    /**
     * Check digest authentication type.
     *
     * @return bool
     */
    public function isDigestAuthentication(): bool
    {
        return self::SOAP_AUTHENTICATION_DIGEST === $this->authentication;
    }

    /**
     * Check SOAP version.
     *
     * @param int $version SOAP version to check.
     *
     * @return bool
     */
    public function isSoapVersion(int $version): bool
    {
        return $version === $this->getSoapVersion();
    }

    /**
     * Return instance with specified connection timeout.
     *
     * @param int $timeout Connection timeout.
     *
     * @return self
     */
    public function withConnectionTimeout(int $timeout): self
    {
        $new = clone $this;
        $new->connection_timeout = $timeout;

        return $new;
    }

    /**
     * Return instance with specified soap version.
     *
     * @param int $soap_version SOAP version.
     *
     * @return self
     */
    public function withSoapVersion(int $soap_version): self
    {
        if (! \in_array($soap_version, [self::SOAP_VERSION_1_1, self::SOAP_VERSION_1_2], true)) {
            throw new \InvalidArgumentException(sprintf('Soap version %s not allowed', $soap_version));
        }

        $new = clone $this;
        $new->soap_version = $soap_version;

        return $new;
    }

    /**
     * Return instance with specified login.
     *
     * @param string $login Login.
     *
     * @return self
     */
    public function withLogin(string $login): self
    {
        if (empty($login)) {
            throw new \InvalidArgumentException('Login can not be empty');
        }

        $new = clone $this;
        $new->login = $login;

        return $new;
    }

    /**
     * Return instance with specified password.
     *
     * @param string $password Password.
     *
     * @return self
     */
    public function withPassword(string $password): self
    {
        if (empty($password)) {
            throw new \InvalidArgumentException('Password can not be empty');
        }

        $new = clone $this;
        $new->password = $password;

        return $new;
    }

    /**
     * Return instance with specified certificate.
     *
     * @param string      $cert_file Certificate file location.
     * @param string|null $key_file  Certificate file with key location.
     *
     * @return $this
     */
    public function withCertificate(string $cert_file, string $key_file = null): self
    {
        if (empty($cert_file)) {
            throw new \InvalidArgumentException('Cert file can not be empty');
        }

        $new = clone $this;
        $new->local_cert = new Certificate($cert_file, $key_file);

        return $new;
    }

    /**
     * Return instance with specified authentication type.
     *
     * @param int $authentication Authentication type.
     *
     * @return self
     */
    public function withAuthentication(int $authentication): self
    {
        if (! \in_array($authentication, [\SOAP_AUTHENTICATION_BASIC, \SOAP_AUTHENTICATION_DIGEST], true)) {
            throw new \InvalidArgumentException('Invalid authentication method');
        }

        $new = clone $this;
        $new->authentication = $authentication;

        return $new;
    }

    /**
     * Return instance with specified compression method.
     *
     * @param int $compression Compression method.
     *
     * @return self
     */
    public function withCompression(int $compression): self
    {
        $new = clone $this;
        $new->compression = $compression;

        return $new;
    }

    /**
     * Return instance with specified user agent.
     *
     * @param string $user_agent User agent.
     *
     * @return self
     */
    public function withUserAgent(string $user_agent): self
    {
        if (empty($user_agent)) {
            throw new \InvalidArgumentException('User agent can not be empty');
        }

        $new = clone $this;
        $new->user_agent = $user_agent;

        return $new;
    }

    /**
     * Return instance with specified URI.
     *
     * @param string $uri URI.
     *
     * @return self
     */
    public function withUri(string $uri): self
    {
        if (empty($uri)) {
            throw new \InvalidArgumentException('URI can not be empty');
        }

        $new = clone $this;
        $new->uri = $uri;

        return $new;
    }

    /**
     * Return instance with specified style type.
     *
     * @param int $style Style.
     *
     * @return self
     */
    public function withStyle(int $style): self
    {
        if (! \in_array($style, [self::SOAP_STYLE_RPC, self::SOAP_STYLE_DOCUMENT], true)) {
            throw new \InvalidArgumentException(\sprintf('Invalid style value "%s"', $style));
        }

        $new = clone $this;
        $this->style = $style;

        return $new;
    }

    /**
     * Return instance with specified wsdl cache method.
     *
     * @param int $cache_wsdl WSDL cache method.
     *
     * @return self
     */
    public function withCacheWsdl(int $cache_wsdl): self
    {
        $allowed = [\WSDL_CACHE_NONE, \WSDL_CACHE_BOTH, \WSDL_CACHE_DISK, \WSDL_CACHE_MEMORY];
        if (! \in_array($cache_wsdl, $allowed, true)) {
            throw new \InvalidArgumentException(sprintf('Cache wsdl option %s not allowed', $cache_wsdl));
        }

        $new = clone $this;
        $new->cache_wsdl = $cache_wsdl;

        return $new;
    }

    /**
     * Return instance with exceptions.
     *
     * @return self
     */
    public function withExceptions(): self
    {
        $new = clone $this;
        $new->exceptions = true;

        return $new;
    }

    /**
     * Return instance without specified exceptions.
     *
     * @return self
     */
    public function withoutExceptions(): self
    {
        $new = clone $this;
        $new->exceptions = false;

        return $new;
    }

    /**
     * Return instance with specified encoding.
     *
     * @param string $encoding Encoding.
     *
     * @return self
     */
    public function withEncoding(string $encoding): self
    {
        if (empty($encoding)) {
            throw new \InvalidArgumentException('Encoding can not be empty');
        }

        $new = clone $this;
        $new->encoding = $encoding;

        return $new;
    }

    /**
     * Return instance with specified use type.
     *
     * @param int $use
     *
     * @return self
     */
    public function withUse(int $use): self
    {
        $allowed = [self::SOAP_USE_LITERAL, self::SOAP_USE_ENCODED];
        if (! \in_array($use, $allowed, true)) {
            throw new \InvalidArgumentException(sprintf('Invalid use option %s', $use));
        }

        $new = clone $this;
        $new->use = $use;

        return $new;
    }

    /**
     * Return instance with specified passphrase for the certificate file.
     *
     * @param string $passphrase Passphrase.
     *
     * @return self
     */
    public function withPassphrase(string $passphrase): self
    {
        if (empty($passphrase)) {
            throw new \InvalidArgumentException('Passphrase can not be empty');
        }

        $new = clone $this;
        $new->passphrase = $passphrase;

        return $new;
    }

    /**
     * Return instance with specified location.
     *
     * @param string $location Location.
     *
     * @return self
     */
    public function withLocation(string $location): self
    {
        $new = clone $this;
        $new->location = $location;

        return $new;
    }

    /**
     * Return array for the SOAP client options representation.
     *
     * @return array
     */
    public function toArray(): array
    {
        $stream_context = [];
        $array_options = [
            'soap_version' => $this->getSoapVersion(),
            'cache_wsdl' => $this->getCacheWsdl(),
            'compression' => $this->getCompression(),
            'style' => $this->getStyle(),
            'encoding' => $this->getEncoding(),
            'use' => $this->getUse(),
            'exceptions' => $this->getExceptions(),
            'connection_timeout' => $this->getConnectionTimeout(),
        ];

        if (null !== $this->getPassword() && null !== $this->getLogin()) {
            $array_options['login'] = $this->getLogin();
            $array_options['password'] = $this->getPassword();
            $array_options['authentication'] = $this->getAuthentication();
        }

        if (null !== $this->getLocalCert()) {
            $stream_context['ssl'] = ['local_cert' => $this->getLocalCert()->getCertFile()];

            if ($this->getLocalCert()->getCertFile() !== $this->getLocalCert()->getKeyFile()) {
                $stream_context['ssl']['local_pk'] = $this->getLocalCert()->getKeyFile();
            }
        }

        if (null !== $this->getPassphrase()) {
            $array_options['passphrase'] = $this->getPassphrase();
        }

        if (null !== $this->getLocation()) {
            $array_options['location'] = $this->getLocation();
        }

        if (null !== $this->getUri()) {
            $array_options['uri'] = $this->getUri();
        }

        if (! empty($stream_context)) {
            $array_options['context'] = \stream_context_create($stream_context);
        }

        return $array_options;
    }
}
