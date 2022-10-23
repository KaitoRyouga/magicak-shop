<?php

namespace App\Services;

use Exception;
use InvalidArgumentException;
use RuntimeException;
use GuzzleHttp\Client;

class DreamScapeService
{
    /**
     * HTTP methods
     */
    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';
    const HTTP_PATCH = 'PATCH';
    const HTTP_DELETE = 'DELETE';

    /**
     * HTTP codes
     */
    const HTTP_CODE_OK = 200;
    const HTTP_CODE_CREATED = 201;
    const HTTP_CODE_NO_CONTENT = 204;
    const HTTP_CODE_BAD_REQUEST = 400;
    const HTTP_CODE_UNAUTHORIZED = 401;
    const HTTP_CODE_FORBIDDEN = 403;
    const HTTP_CODE_NOT_FOUND = 404;
    const HTTP_CODE_INTERNAL_SERVER_ERROR = 500;
    const HTTP_CODE_SERVICE_UNAVAILABLE = 500;

    /**
     * Dreamscape Reseller REST API server locations
     */
    const API_LOCATION_PRODUCTION = 'https://reseller-api.ds.network';
    const API_LOCATION_SANDBOX = 'https://reseller-api.sandbox.ds.network';

    /**
     * Dreamscape Reseller REST API location
     *
     * @var string
     */
    private $apiLocation;

    /**
     * Reseller API Key
     *
     * @var string
     */
    private $apiKey;
    /**
     * @var mixed
     */

    /**
     * HTTP code of the last request.
     *
     * @var int|null
     */
    private $httpCode;

    /**
     * Parsed response body.
     *
     * @var array|null
     */
    private $responseParsedBody;

    /**
     * Response data.
     *
     * @var array|null
     */
    private $responseData;

    /**
     * Response message.
     *
     * @var string|null
     */
    private $responseErrorMessage;

    /**
     * Response validation errors.
     *
     * @var array|null
     */
    private $responseValidationErrors;

    /**
     * Response pagination.
     *
     * @var array|null
     */
    private $responsePagination;

    /**
     * @var int|null
     */
    private $requestPaginationLimit;

    /**
     * @var int|null
     */
    private $requestPaginationPage;

    /**
     * @var Client
     */
    protected $client;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $apiKey = config('dreamScape.api_key');
        // $apiLocation = strtolower(config('app.env')) === 'local' ? self::API_LOCATION_SANDBOX : self::API_LOCATION_PRODUCTION;
        // temp => fix later
        $apiLocation = self::API_LOCATION_SANDBOX;

        if (empty($apiLocation) || !is_string($apiLocation)) {
            throw new InvalidArgumentException('The argument $location must be not empty string');
        }

        if (empty($apiKey) || !is_string($apiKey)) {
            throw new InvalidArgumentException('The argument $apiKey must be not empty string');
        }

        if (!preg_match('/^http[s]?:\/\/.*$/', $apiLocation)) {
            throw new InvalidArgumentException(
                'Argument $apiLocation has wrong format. ' .
                    'It must be like: https://reseller-api.ds.network'
            );
        }

        if (!preg_match('/^[a-z0-9]{32}$/', $apiKey)) {
            throw new InvalidArgumentException('API Key has invalid format');
        }

        $this->apiLocation = $apiLocation;
        $this->apiKey = $apiKey;
        $this->client = new Client();
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (empty($name) || !is_string($name)) {
            throw new InvalidArgumentException('The argument $name must be not empty string');
        }

        switch ($name) {
            case 'httpCode':
                return $this->httpCode;

            case 'parsedBody':
                return $this->responseParsedBody;

            case 'data':
                return $this->responseData;

            case 'pagination':
                return $this->responsePagination;

            case 'errorMessage':
                return $this->responseErrorMessage;

            case 'validationErrors':
                return $this->responseValidationErrors;
        }

        throw new InvalidArgumentException('The property with name \'' . $name . '\' does not exist');
    }

    /**
     * Resets the internal state.
     */
    private function reset()
    {
        $this->httpCode = null;
        $this->responseParsedBody = null;
        $this->responseData = null;
        $this->responsePagination = null;
        $this->responseErrorMessage = null;
        $this->responseValidationErrors = null;
    }

    /**
     * Executes the request to the REST API.
     *
     * @param $apiMethod
     * @param $httpMethod
     * @param array $parameters
     * @param array $data
     *
     * @return array|bool
     *
     * @throws Exception
     */
    public function request($apiMethod, $httpMethod = self::HTTP_GET, $parameters = [], $data = [])
    {
        if (empty($apiMethod) || !is_string($apiMethod)) {
            throw new InvalidArgumentException('The argument $apiMethod must be not empty string');
        }

        if (empty($apiMethod) || !is_string($httpMethod)) {
            throw new InvalidArgumentException('The argument $apiMethod must be not empty string');
        }

        if (!is_array($parameters)) {
            throw new InvalidArgumentException('The argument $parameters must be an array');
        }

        if (!is_array($data)) {
            throw new InvalidArgumentException('The argument $data must be an array');
        }

        if (!in_array($httpMethod, [self::HTTP_GET, self::HTTP_POST, self::HTTP_PATCH, self::HTTP_DELETE])) {
            throw new InvalidArgumentException('The argument $method has not allowed value');
        }

        $this->reset();

        $url = $this->apiLocation . '/' . $apiMethod;

        if ($httpMethod === self::HTTP_GET) {
            if ($this->requestPaginationPage !== null) {
                $parameters['page'] = $this->requestPaginationPage;
            }

            if ($this->requestPaginationLimit !== null) {
                $parameters['limit'] = $this->requestPaginationLimit;
            }
        }

        if (!empty($parameters)) {
            $url .= '?' . http_build_query($parameters);
        }

        $apiRequestId = md5(uniqid() . microtime());
        $apiSignature = md5($apiRequestId . $this->apiKey);

        $httpHeaders = [
            'Accept' => 'application/json',
            'Api-Request-Id' => $apiRequestId,
            'Api-Signature' => $apiSignature,
        ];

        if (in_array($httpMethod, [self::HTTP_POST, self::HTTP_PATCH, self::HTTP_DELETE])) {
            $httpHeaders[] = 'Content-Type: application/json';
            $result = $this->client->request($httpMethod, $url, [
                'headers' => $httpHeaders,
                'json' => $data,
                'verify' => false
            ]);
        } else {
            $result = $this->client->request($httpMethod, $url, [
                'headers' => $httpHeaders,
                'params' => $parameters,
                'verify' => false
            ]);
        }

        $contentType = null;

        if ($result) {
            $this->httpCode = $result->getStatusCode();
            $contentType = $result->getHeader('Content-Type')[0];

            if (strpos($contentType, ';') !== false) {
                $contentType = trim(explode(';', $contentType)[0]);
            }
        } else {
            throw new RuntimeException('Failed to execute the request to API');
        }

        if (
            $this->httpCode === 204 // in case of successful DELETE request
            || $contentType !== 'application/json' // if we've received not JSON
        ) {
            return true;
        }

        $this->responseParsedBody = json_decode($result->getBody(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('Failed to decode received JSON');
        }

        if (!$this->responseParsedBody['status']) {
            $this->responseErrorMessage = $this->responseParsedBody['error_message'];

            if (isset($this->responseParsedBody['validation_errors'])) {
                $this->responseValidationErrors = $this->responseParsedBody['validation_errors'];
            }

            return false;
        }

        $this->responseData = $this->responseParsedBody['data'];

        if (isset($this->responseParsedBody['pagination'])) {
            $this->responsePagination = $this->responseParsedBody['pagination'];
        }

        return $this->responseData;
    }

    /**
     * Sets the request pagination limit.
     *
     * @param int $limit
     *
     * @return self
     */
    public function limit(int $limit): self
    {
        if (!is_integer($limit) || $limit < 1) {
            throw new InvalidArgumentException('Argument $limit must positive integer');
        }

        $this->requestPaginationLimit = $limit;

        return $this;
    }

    /**
     * Sets the request pagination page.
     *
     * @param int $page
     *
     * @return self
     */
    public function page(int $page): self
    {
        if (!is_integer($page) || $page < 1) {
            throw new InvalidArgumentException('Argument $page must positive integer');
        }

        $this->requestPaginationPage = $page;

        return $this;
    }
}
