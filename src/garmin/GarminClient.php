<?php

namespace jjtbsomhorst\garmin\sso;

use Exception;
use jjtbsomhorst\garmin\sso\requests\AccessTokenRequest;
use jjtbsomhorst\garmin\sso\requests\CSRFTokenRequest;
use jjtbsomhorst\garmin\sso\requests\DownloadActivityRequest;
use jjtbsomhorst\garmin\sso\requests\LoginRequest;
use jjtbsomhorst\garmin\sso\requests\RetrieveActivitiesRequest;
use jjtbsomhorst\garmin\sso\requests\ServiceTicketRequest;
use jjtbsomhorst\garmin\sso\requests\SetCookieRequest;
use jjtbsomhorst\garmin\sso\responses\AccessTokenResponse;
use jjtbsomhorst\garmin\sso\responses\CsrfToken;
use jjtbsomhorst\garmin\sso\responses\DownloadActivityResponse;
use jjtbsomhorst\garmin\sso\responses\LoginResponse;
use jjtbsomhorst\garmin\sso\responses\ServiceTicketResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Exception\GuzzleException;
use jjtbsomhorst\garmin\sso\support\ActivityDownload;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class GarminClient
{
    private Client $client;
    private string $username;
    private string $password;
    private string $cookiedir = "";
    private http\AccessToken $accessToken;

    public function __construct()
    {
    }


    public function username(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function password(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function cookieJarLocation(string $path): self
    {
        $this->cookiedir = $path;
        return $this;
    }

    /**
     * @throws ClientExceptionInterface
     * @throws GuzzleException
     * @throws Exception
     */
    public function login(): self
    {
        $this->initClient();
        $response = $this->client->send(new SetCookieRequest());

        if ($response->getStatusCode() !== 200) {
            throw new Exception($response->getReasonPhrase());
        }

        $response = $this->client->send(new CSRFTokenRequest());
        $csrfToken = CsrfToken::fromResponse($response);

        $response = new LoginResponse(
            $this->client
                ->sendRequest(
                    new LoginRequest(
                        $this->username,
                        $this->password,
                        $csrfToken
                    )
                )
        );

        $serviceTicket = $response->getServiceTicket();

        $response = new ServiceTicketResponse(
            $this->send(
                new ServiceTicketRequest("")
            )
        );

        $response->validate();

        $response = new AccessTokenResponse(
            $this->client->send(new AccessTokenRequest())
        );

        $this->accessToken = $response->token();
        return $this;
    }

    private function initClient(): void
    {
        $cookieFile = 'cookie_jar.txt';

        if ($this->cookiedir !== "") {
            $cookieFile = $this->cookiedir . "/" . $cookieFile;
        }

        $cookieJar = new FileCookieJar($cookieFile, true);
        if (!isset($this->client)) {
            $this->client = new Client(['cookies' => $cookieJar,  'verify' => false]);
        }
    }

    /**
     * @throws GuzzleException
     * @return \stdClass[]
     */
    public function getActivities(int $start = 20, int $limit = 20, string $sortBy = 'startLocal', string $sortOrder = 'asc'): array
    {
        $request = new RetrieveActivitiesRequest($this->accessToken->accessToken, $start, $limit, $sortBy, $sortOrder);
        $response = $this->send($request);

        return json_decode($response->getBody()->getContents(), false);
    }

    /**
     * @throws GuzzleException
     * @throws ClientExceptionInterface
     * @see Client::send()
     */
    private function send(RequestInterface $request, array $options = [], bool $retryOnFail = true): ResponseInterface
    {
        try {
            return $this->client->send($request, $options);
        } catch (GuzzleException $exception) {
            if ($retryOnFail) {
                if ($exception->getCode() === 401) {
                    $this->login();
                }

                return $this->send($request, $options, false);
            }
            throw $exception;
        }
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function downloadActivity(string $activityId, string $path): bool
    {
        $response = $this->client->send(new DownloadActivityRequest($this->accessToken->accessToken, $activityId));
        $downloadResponse = new DownloadActivityResponse($response);
        $downloadResponse->download($path);
        return true;
    }

    /**
     * @throws GuzzleException
     */
    public function downloadActivityAsStreamObject(string $activityId): ActivityDownload
    {
        $response = $this->client->send(new DownloadActivityRequest($this->accessToken->accessToken, $activityId));
        $downloadResponse = new DownloadActivityResponse($response);
        return new ActivityDownload($downloadResponse->getFileName(), $downloadResponse->getBody());
    }
}
