<?php declare(strict_types=1);
/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.3
 *
 * @category amphp-async-soap
 * @author   Oleg Tikhonov <to@toro.one>
 */

namespace Traff\Soap\Message;

/**
 * Interface SoapMessageInterface
 *
 * @package Traff\Soap
 */
interface SoapMessageInterface
{
    public function request(string $func_name, array $arguments): self;

    public function response(string $response, string $func_name);

    public function getLocation(): ?string;

    public function getRequest(): ?string;

    public function getAction(): ?string;

    public function getVersion(): ?int;
}
