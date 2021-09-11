[![GitHub Actions](https://github.com/traffaret/amphp-async-soap/actions/workflows/php.yml/badge.svg)](https://github.com/traffaret/amphp-async-soap/actions)
[![Test Coverage](https://api.codeclimate.com/v1/badges/e092df69e93f1ea3b0ac/test_coverage)](https://codeclimate.com/github/traffaret/amphp-async-soap/test_coverage)
[![Maintainability](https://api.codeclimate.com/v1/badges/e092df69e93f1ea3b0ac/maintainability)](https://codeclimate.com/github/traffaret/amphp-async-soap/maintainability)
![GitHub release (latest SemVer including pre-releases)](https://img.shields.io/github/v/release/traffaret/amphp-async-soap?include_prereleases)
# async-soap
Amphp soap async

```php
use Amp\Http\Client\HttpClientBuilder;
use Traff\Soap\Options;
use Traff\Soap\SoapTransportBuilder;
use Traff\Soap\Wsdl\WsdlUrlFactory;

$http_client = HttpClientBuilder::buildDefault();
$options = (new Options())
    ->withSoapVersion(\SOAP_1_1)
    ->withConnectionTimeout(20);

$wsdl = yield (new WsdlUrlFactory())
    ->createWsdl('https://cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL', null, $http_client)
    ->toString();

$soap_transport = (new SoapTransportBuilder())
    ->withHttpClient($http_client)
    ->withWsdl($wsdl)
    ->withOptions($options)
    ->build();

$result = yield $soap_transport->callAsync('GetCursOnDate', [['On_date' => (new \DateTime('now'))->format('Y-m-d')]]);

// Or

$result = yield $soap_transport->GetCursOnDate(['On_date' => (new \DateTime('now'))->format('Y-m-d')]);
```
