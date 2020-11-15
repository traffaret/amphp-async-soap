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

namespace Traff\Soap\Test\Unit;

use Amp\Socket\Certificate;
use Traff\Soap\Options;

/**
 * Class OptionsTest.
 *
 * @package Traff\Soap\Test\Unit
 */
class OptionsTest extends \Mockery\Adapter\Phpunit\MockeryTestCase
{
    /**
     * withUse.
     *
     * @covers \Traff\Soap\Options::withUse
     * @covers \Traff\Soap\Options::getUse
     *
     * @return void
     */
    public function testWithUse(): void
    {
        $options = new Options();

        $options_literal = $options->withUse(Options::SOAP_USE_LITERAL);
        $options_encoded = $options->withUse(Options::SOAP_USE_ENCODED);

        self::assertNotSame($options_encoded, $options_literal);
        self::assertSame(Options::SOAP_USE_LITERAL, $options_literal->getUse());
        self::assertSame(Options::SOAP_USE_ENCODED, $options_encoded->getUse());

        $this->expectException(\InvalidArgumentException::class);
        $options->withUse(1000);
    }

    /**
     * withUri.
     *
     * @covers \Traff\Soap\Options::withUri
     * @covers \Traff\Soap\Options::getUri
     *
     * @return void
     */
    public function testWithUri(): void
    {
        $options = new Options();

        $options_uri = $options->withUri('uri');

        self::assertNotSame($options, $options_uri);
        self::assertSame('uri', $options_uri->getUri());

        $this->expectException(\InvalidArgumentException::class);
        $options->withUri('');
    }

    /**
     * withSoapVersion.
     *
     * @covers \Traff\Soap\Options::withSoapVersion
     * @covers \Traff\Soap\Options::getSoapVersion
     *
     * @return void
     */
    public function testWithSoapVersion(): void
    {
        $options = new Options();

        $options_new = $options->withSoapVersion(\SOAP_1_2);

        self::assertNotSame($options, $options_new);
        self::assertSame(\SOAP_1_2, $options_new->getSoapVersion());

        $this->expectException(\InvalidArgumentException::class);
        $options->withSoapVersion(10000);
    }

    /**
     * isBasicAuthentication.
     *
     * @covers \Traff\Soap\Options::isBasicAuthentication
     * @covers \Traff\Soap\Options::withAuthentication
     *
     * @return void
     */
    public function testIsBasicAuthentication(): void
    {
        self::assertTrue((new Options())->withAuthentication(\SOAP_AUTHENTICATION_BASIC)->isBasicAuthentication());
    }

    /**
     * withExceptions.
     *
     * @covers \Traff\Soap\Options::withExceptions
     * @covers \Traff\Soap\Options::getExceptions
     *
     * @return void
     */
    public function testWithExceptions(): void
    {
        $options = new Options();

        $options_new = $options->withExceptions();

        self::assertNotSame($options, $options_new);
        self::assertTrue($options_new->getExceptions());
    }

    /**
     * withCompression.
     *
     * @covers \Traff\Soap\Options::withCompression
     * @covers \Traff\Soap\Options::getCompression
     *
     * @return void
     */
    public function testWithCompression(): void
    {
        $options = new Options();

        $options_new = $options->withCompression(\SOAP_COMPRESSION_ACCEPT | \SOAP_COMPRESSION_DEFLATE);

        self::assertNotSame($options, $options_new);
        self::assertSame(\SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_DEFLATE, $options_new->getCompression());
    }

    /**
     * withPassphrase.
     *
     * @covers \Traff\Soap\Options::withPassphrase
     * @covers \Traff\Soap\Options::getPassphrase
     *
     * @return void
     */
    public function testWithPassphrase(): void
    {
        $options = new Options();

        $options_new = $options->withPassphrase('pass');

        self::assertNotSame($options, $options_new);
        self::assertSame('pass', $options_new->getPassphrase());

        $this->expectException(\InvalidArgumentException::class);
        $options_new->withPassphrase('');
    }

    /**
     * withCacheWsdl.
     *
     * @covers \Traff\Soap\Options::withCacheWsdl
     * @covers \Traff\Soap\Options::getCacheWsdl
     *
     * @return void
     */
    public function testWithCacheWsdl(): void
    {
        $options = new Options();

        $options_new = $options->withCacheWsdl(\WSDL_CACHE_BOTH);

        self::assertNotSame($options, $options_new);
        self::assertSame(\WSDL_CACHE_BOTH, $options_new->getCacheWsdl());

        $this->expectException(\InvalidArgumentException::class);
        $options_new->withCacheWsdl(10000);
    }

    /**
     * withPassword.
     *
     * @covers \Traff\Soap\Options::withPassword
     * @covers \Traff\Soap\Options::getPassword
     *
     * @return void
     */
    public function testWithPassword(): void
    {
        $options = new Options();

        $options_new = $options->withPassword('pass');

        self::assertNotSame($options, $options_new);
        self::assertSame('pass', $options_new->getPassword());

        $this->expectException(\InvalidArgumentException::class);
        $options_new->withPassword('');
    }

    /**
     * withLogin.
     *
     * @covers \Traff\Soap\Options::withLogin
     * @covers \Traff\Soap\Options::getLogin
     *
     * @return void
     */
    public function testWithLogin(): void
    {
        $options = new Options();

        $options_new = $options->withLogin('login');

        self::assertNotSame($options, $options_new);
        self::assertSame('login', $options_new->getLogin());

        $this->expectException(\InvalidArgumentException::class);
        $options_new->withLogin('');
    }

    /**
     * withStyle.
     *
     * @covers \Traff\Soap\Options::withStyle
     * @covers \Traff\Soap\Options::getStyle
     *
     * @return void
     */
    public function testWithStyle(): void
    {
        $options = new Options();

        $options_new = $options->withStyle(\SOAP_RPC);

        self::assertNotSame($options, $options_new);
        self::assertSame(Options::SOAP_STYLE_RPC, $options_new->getStyle());

        $this->expectException(\InvalidArgumentException::class);
        $options_new->withStyle(10000);
    }

    /**
     * withoutExceptions.
     *
     * @covers \Traff\Soap\Options::withoutExceptions
     * @covers \Traff\Soap\Options::getExceptions
     *
     * @return void
     */
    public function testWithoutExceptions(): void
    {
        $options = new Options();

        $options_new = $options->withoutExceptions();

        self::assertNotSame($options, $options_new);
        self::assertFalse($options_new->getExceptions());
    }

    /**
     * withEncoding.
     *
     * @covers \Traff\Soap\Options::withEncoding
     * @covers \Traff\Soap\Options::getEncoding
     *
     * @return void
     */
    public function testWithEncoding(): void
    {
        $options = new Options();

        $options_new = $options->withEncoding('windows-1251');

        self::assertNotSame($options, $options_new);
        self::assertSame('windows-1251', $options_new->getEncoding());

        $this->expectException(\InvalidArgumentException::class);
        $options->withEncoding('');
    }

    /**
     * withAuthentication.
     *
     * @covers \Traff\Soap\Options::withAuthentication
     * @covers \Traff\Soap\Options::getAuthentication
     *
     * @return void
     */
    public function testWithAuthentication(): void
    {
        $options = new Options();

        $options_new = $options->withAuthentication(\SOAP_AUTHENTICATION_DIGEST);

        self::assertNotSame($options, $options_new);
        self::assertSame(Options::SOAP_AUTHENTICATION_DIGEST, $options_new->getAuthentication());

        $this->expectException(\InvalidArgumentException::class);
        $options_new->withAuthentication(10000);
    }

    /**
     * withLocation.
     *
     * @covers \Traff\Soap\Options::withLocation
     * @covers \Traff\Soap\Options::getLocation
     *
     * @return void
     */
    public function testWithLocation(): void
    {
        $options = new Options();

        $options_new = $options->withLocation('localhost');

        self::assertNotSame($options, $options_new);
        self::assertSame('localhost', $options_new->getLocation());
    }

    /**
     * withCertificate.
     *
     * @covers \Traff\Soap\Options::withCertificate
     * @covers \Traff\Soap\Options::getLocalCert
     *
     * @return void
     */
    public function testWithCertificate(): void
    {
        $options = new Options();

        $options_new = $options->withCertificate('cert_file');

        self::assertNotSame($options, $options_new);
        self::assertInstanceOf(Certificate::class, $options_new->getLocalCert());

        $this->expectException(\InvalidArgumentException::class);
        $options->withCertificate('');
    }

    /**
     * isDigestAuthentication.
     *
     * @covers \Traff\Soap\Options::isDigestAuthentication
     * @covers \Traff\Soap\Options::withAuthentication
     *
     * @return void
     */
    public function testIsDigestAuthentication(): void
    {
        self::assertTrue((new Options())->withAuthentication(\SOAP_AUTHENTICATION_DIGEST)->isDigestAuthentication());
    }

    /**
     * isSoapVersion.
     *
     * @covers \Traff\Soap\Options::isSoapVersion
     * @covers \Traff\Soap\Options::withSoapVersion
     *
     * @return void
     */
    public function testIsSoapVersion(): void
    {
        self::assertTrue((new Options())->withSoapVersion(\SOAP_1_2)->isSoapVersion(\SOAP_1_2));
    }

    /**
     * withConnectionTimeout.
     *
     * @covers \Traff\Soap\Options::withConnectionTimeout
     * @covers \Traff\Soap\Options::getConnectionTimeout
     *
     * @return void
     */
    public function testWithConnectionTimeout(): void
    {
        $options = new Options();

        $options_new = $options->withConnectionTimeout(20);

        self::assertNotSame($options, $options_new);
        self::assertSame(20, $options_new->getConnectionTimeout());
    }

    /**
     * withUserAgent.
     *
     * @covers \Traff\Soap\Options::withUserAgent
     * @covers \Traff\Soap\Options::getUserAgent
     *
     * @return void
     */
    public function testWithUserAgent(): void
    {
        $options = new Options();

        $options_new = $options->withUserAgent('unittest-agent');

        self::assertNotSame($options, $options_new);
        self::assertSame('unittest-agent', $options_new->getUserAgent());

        $this->expectException(\InvalidArgumentException::class);
        $options_new->withUserAgent('');
    }

    /**
     * testToArray.
     *
     * @covers \Traff\Soap\Options::toArray
     *
     * @return void
     */
    public function testToArray(): void
    {
        $options = (new Options())
            ->withUserAgent('unittest-agent')
            ->withSoapVersion(\SOAP_1_2)
            ->withCertificate('cert_file', 'cert_key')
            ->withPassphrase('passphrase')
            ->withUri('uri')
            ->withLocation('localhost')
            ->withLogin('login')
            ->withPassword('password')
            ->withEncoding('koi8-r')
            ->withExceptions()
            ->withConnectionTimeout(20);

        $actual = $options->toArray();

        self::assertArrayHasKey('context', $actual);

        unset($actual['context']);

        self::assertSame(
            [
                'soap_version' => 2,
                'cache_wsdl' => 0,
                'compression' => 0,
                'style' => 1,
                'encoding' => 'koi8-r',
                'use' => 2,
                'exceptions' => true,
                'connection_timeout' => 20,
                'login' => 'login',
                'password' => 'password',
                'authentication' => 0,
                'passphrase' => 'passphrase',
                'location' => 'localhost',
                'uri' => 'uri',
            ],
            $actual
        );
    }
}
