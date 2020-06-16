<?php declare(strict_types=1);
/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.3
 *
 * @category amphp-async-soap
 * @author   Oleg Tikhonov <to@toro.one>
 */

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
    public const SOAP_COMPRESSION_GZIP = 'gzip';
    public const SOAP_COMPRESSION_ACCEPT = 'accept';
    public const SOAP_COMPRESSION_DEFLATE = 'deflate';
    public const SOAP_STYLE_RPC = 'rpc';
    public const SOAP_STYLE_DOCUMENT = 'document';
    public const SOAP_USE_ENCODED = \SOAP_ENCODED;
    public const SOAP_USE_LITERAL = \SOAP_LITERAL;
    public const SOAP_AUTHENTICATION_BASIC = \SOAP_AUTHENTICATION_BASIC;
    public const SOAP_AUTHENTICATION_DIGEST = \SOAP_AUTHENTICATION_DIGEST;
    public const SOAP_STYLE = [
        self::SOAP_STYLE_RPC => \SOAP_RPC,
        self::SOAP_STYLE_DOCUMENT => \SOAP_DOCUMENT,
    ];
    public const SOAP_COMPRESSION = [
        self::SOAP_COMPRESSION_GZIP => \SOAP_COMPRESSION_GZIP,
        self::SOAP_COMPRESSION_ACCEPT => \SOAP_COMPRESSION_ACCEPT,
        self::SOAP_COMPRESSION_DEFLATE => \SOAP_COMPRESSION_DEFLATE,
    ];

    private $location;
    private $soap_version = self::SOAP_VERSION_1_1;
    private $login;
    private $password;
    private $local_cert;
    private $authentication = self::SOAP_AUTHENTICATION_BASIC;
    private $compression = \SOAP_COMPRESSION_GZIP;
    private $user_agent = 'soap-async';
    private $style = \SOAP_RPC;
    private $uri;
    private $classmap = [];
    private $typemap = [];
    private $exceptions = false;
    private $cache_wsdl = \WSDL_CACHE_NONE;
    private $encoding = self::SOAP_ENCODING_UTF_8;
    private $use = self::SOAP_USE_LITERAL;
    private $passphrase;

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function withLocation(string $location): self
    {
        $new = clone $this;
        $new->location = $location;

        return $new;
    }

    public function getSoapVersion(): int
    {
        return $this->soap_version;
    }

    public function withSoapVersion(int $soap_version): self
    {
        if (! \in_array($soap_version, [self::SOAP_VERSION_1_1, self::SOAP_VERSION_1_2], true)) {
            throw new \Error(sprintf('Soap version %s not allowed', $soap_version));
        }

        $new = clone $this;
        $new->soap_version = $soap_version;

        return $new;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function withLogin(string $login): self
    {
        if (empty($login)) {
            throw new \Error('Login can not be empty');
        }

        $new = clone $this;
        $new->login = $login;

        return $new;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function withPassword(string $password): self
    {
        if (empty($password)) {
            throw new \Error('Password can not be empty');
        }

        $new = clone $this;
        $new->password = $password;

        return $new;
    }

    public function getLocalCert(): ?Certificate
    {
        return $this->local_cert;
    }

    public function withCertificate(string $cert_file, string $key_file = null): self
    {
        if (empty($cert_file)) {
            throw new \Error('Cert file can not be empty');
        }
        
        $new = clone $this;
        $new->local_cert = new Certificate($cert_file, $key_file);
        
        return $new;
    }

    public function getAuthentication(): int
    {
        return $this->authentication;
    }

    public function withAuthentication(int $authentication): self
    {
        if (! \in_array($authentication, [\SOAP_AUTHENTICATION_BASIC, \SOAP_AUTHENTICATION_DIGEST], true)) {
            throw new \Error('Invalid authentication method');
        }

        $new = clone $this;
        $new->authentication = $authentication;

        return $new;
    }

    public function getCompression(): int
    {
        return $this->compression;
    }

    public function withCompression($compression): self
    {
        if (! \in_array($compression, \array_values(self::SOAP_COMPRESSION), true)
            && ! isset(self::SOAP_COMPRESSION[$compression])
        ) {
            throw new \Error(sprintf('Invalid compression %s', $compression));
        }

        $new = clone $this;
        $new->compression = self::SOAP_COMPRESSION[$compression] ?? $compression;

        return $new;
    }

    public function getUserAgent(): string
    {
        return $this->user_agent;
    }

    public function withUserAgent(string $user_agent): self
    {
        if (empty($user_agent)) {
            throw new \Error('User agent can not be empty');
        }

        $new = clone $this;
        $new->user_agent = $user_agent;

        return $new;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }
    
    public function withUri(string $uri): self 
    {
        if (empty($uri)) {
            throw new \Error('URI can not be empty');
        }
        
        $new = clone $this;
        $new->uri = $uri;
        
        return $new;
    }

    public function getStyle(): int
    {
        return $this->style;
    }

    public function withStyle($style): self
    {
        if (! \in_array($style, \array_values(self::SOAP_STYLE), true)
            && ! isset(self::SOAP_STYLE[$style])) {
            throw new \Error(sprintf('SOAP style %s not allowed', $style));
        }

        $new = clone $this;
        $this->style = self::SOAP_STYLE[$style] ?? $style;

        return $new;
    }

    public function getClassMap(): array
    {
        return $this->classmap;
    }

    public function withClassMap(array $classmap): self
    {
        $new = clone $this;
        $new->classmap = $classmap;

        return $new;
    }

    public function getTypeMap(): array
    {
        return $this->typemap;
    }

    public function withTypeMap(array $typemap): self
    {
        $new = clone $this;
        $new->typemap = $typemap;

        return $new;
    }

    public function getCacheWsdl(): int
    {
        return $this->cache_wsdl;
    }

    public function withCacheWsdl(int $cache_wsdl): self
    {
        $allowed = [\WSDL_CACHE_NONE, \WSDL_CACHE_BOTH, \WSDL_CACHE_DISK, \WSDL_CACHE_MEMORY];
        if (! \in_array($cache_wsdl, $allowed, true)) {
            throw new \Error(sprintf('Cache wsdl option %s not allowed', $cache_wsdl));
        }

        $new = clone $this;
        $new->cache_wsdl = $cache_wsdl;

        return $new;
    }

    public function getExceptions(): bool
    {
        return $this->exceptions;
    }

    public function withExceptions(bool $exceptions): self
    {
        $new = clone $this;
        $new->exceptions = $exceptions;

        return $new;
    }

    public function getEncoding(): string
    {
        return $this->encoding;
    }

    public function withEncoding(string $encoding): self
    {
        if (empty($encoding)) {
            throw new \Error('Encoding can not be empty');
        }

        $new = clone $this;
        $new->encoding = $encoding;

        return $new;
    }

    public function getUse(): int
    {
        return $this->use;
    }

    public function withUse(int $use): self
    {
        $allowed = [self::SOAP_USE_LITERAL, self::SOAP_USE_ENCODED];
        if (! \in_array($use, $allowed, true)) {
            throw new \Error(sprintf('Invalid use option %s', $use));
        }

        $new = clone $this;
        $new->use = $use;

        return $new;
    }

    public function getPassphrase(): ?string
    {
        return $this->passphrase;
    }

    public function withPassphrase(string $passphrase): self
    {
        if (empty($passphrase)) {
            throw new \Error('Passphrase can not be empty');
        }

        $new = clone $this;
        $new->passphrase = $passphrase;

        return $new;
    }

    public function isBasicAuthentication(): bool
    {
        return self::SOAP_AUTHENTICATION_BASIC === $this->authentication;
    }

    public function isDigestAuthentication(): bool
    {
        return self::SOAP_AUTHENTICATION_DIGEST === $this->authentication;
    }

    public function isSoapVersion(int $version): bool
    {
        return $version === $this->getSoapVersion();
    }

    public function toArray(): array
    {
        $context = [];
        $array_options = [
            'soap_version' => $this->getSoapVersion(),
            'cache_wsdl' => $this->getCacheWsdl(),
            'compression' => $this->getCompression(),
            'style' => $this->getStyle(),
            'encoding' => $this->getEncoding(),
            'use' => $this->getUse(),
            'exceptions' => $this->getExceptions(),
        ];

        if (null !== $this->getPassword() && null !== $this->getLogin()) {
            $array_options['login'] = $this->getLogin();
            $array_options['password'] = $this->getPassword();
            $array_options['authentication'] = $this->getAuthentication();
        }
        if (null !== $this->getLocalCert()) {
            $context['ssl'] = ['local_cert' => $this->getLocalCert()->getCertFile()];
            if ($this->getLocalCert()->getCertFile() !== $this->getLocalCert()->getKeyFile()) {
                $context['ssl']['local_pk'] = $this->getLocalCert()->getKeyFile();
            }
        }
        if (null !== $this->getLocation()) {
            $array_options['location'] = $this->getLocation();
        }
        if (null !== $this->getUri()) {
            $array_options['uri'] = $this->getUri();
        }
        if (! empty($context)) {
            $array_options['context'] = \stream_context_create($context);
        }

        return $array_options;
    }
}
