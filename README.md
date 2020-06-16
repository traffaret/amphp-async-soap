# async-soap
Amphp soap async

```php
$soap_options = (new Options())
    ->withSoapVersion(Options::SOAP_VERSION_1_1);

$http_pool = ConnectionLimitingPool::byAuthority(5);
$http_client = (new HttpClientBuilder)
    ->usingPool($http_pool)
    ->followRedirects(0)
    ->build();

$wsdl = yield (new WsdlUrl(
    'https://cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL',
    new WsdlRequestBuilder($http_client, $soap_options)
))->toString();

$soap_transport = new SoapTransport(
    new SoapMessage($wsdl, $soap_options->toArray()),
    new SoapRequestBuilder($http_client, $soap_options)
);

$result = yield $this->soap_transport->callAsync('GetCursOnDate', [['On_date' => (new \DateTime('now'))->format('Y-m-d')]]);
```
