<?php declare(strict_types=1);
/**
 * Created by IntelliJ IDEA.
 *
 * PHP version 7.3
 *
 * @category async-soap
 * @author   Oleg Tikhonov <to@toro.one>
 */

namespace Traff\Soap\Wsdl;

use Amp\Promise;
use Traff\Soap\RequestBuilder\RequestBuilder;

/**
 * Class Wsdl
 *
 * @category async-soap
 * @package  Traff\Soap
 * @author   Oleg Tikhonov <to@toro.one>
 */
interface Wsdl
{
    public function __construct(string $wsdl, RequestBuilder $builder);

    public function toString(): Promise;
}
