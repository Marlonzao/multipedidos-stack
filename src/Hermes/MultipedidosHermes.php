<?php
    namespace Multipedidos;

    abstract class Hermes
    {
        public $client, $baseURL;

        public function __construct($config = [])
        {
            if(!isset($this->baseURL))
                throw new \Error('baseURL is required');

            $clientOptions = [
                'base_uri' => $this->baseURL,
                'headers' => array_merge(['Content-Type' => 'application/json'], $config['headers'] ?? [])
            ];

            if(isset($config['auth']))
                $clientOptions['auth'] = $config['auth'];

            $this->client = new \GuzzleHttp\Client($clientOptions);
        }

        public function __get($name)
        {
            if($name == 'hermes') return $this->hermes();

            throw new \Exception ("Property $name is not defined");
        }

        public function hermes()
        {
            return new class ($this->client) {
                public $requestBody     = []; 
                public $requestHeaders  = [];
                public $requestBodyType = 'json';

                public $client, $url, $method, $responseBodyTypeClass; 
                protected $errorHandling, $errorCode, $errorMessage, $errorCallback;
            
                public function __construct($client)
                {
                    $this->client = $client;
                    $this->setErrorMessageAndCode('An error ocurred while trying to communicate with an external API', 500);
                }

                public function request($url, $method = 'GET')
                {
                    $this->url           = $url;
                    $this->method        = $method;

                    return $this;
                }

                public function setRequestHeaders($headers)
                {
                    $this->requestHeaders = $headers;

                    return $this;
                }

                public function setBody($body, $bodyType = 'json')
                {
                    $this->requestBody = $body;
                    $this->requestBodyType = $bodyType;

                    return $this;
                }

                public function setErrorMessageAndCode($message, $code)
                {
                    $this->errorCode    = $code;
                    $this->errorMessage = $message;

                    return $this;
                }

                public function setErrorCallback(\Closure $callback)
                {
                    $this->errorCallback = $callback;

                    return $this;
                }

                public function typeResponseBody($typeClass)
                {
                    $this->responseBodyTypeClass = $typeClass;

                    return $this;
                }

                public function run()
                {
                    try {
                        $options = [
                            $this->requestBodyType => $this->requestBody,
                            'headers' => $this->requestHeaders
                        ];

                        $response = $this->client->request(...[$this->method, $this->url, $options]);
                    } catch (\Exception $e) {

                        if(isset($this->errorCallback)){
                            $this->errorCallback->call($this, $e);
                            return;
                        }

                        throw $e;
                    }

                    $responseBody = json_decode($response->getBody());

                    if($this->responseBodyTypeClass) $responseBody = new $this->responseBodyTypeClass($responseBody);
                    return $responseBody;
                }
            };
        }
    }
