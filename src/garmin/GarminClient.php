<?php

namespace jjtbsomhorst\garmin\sso;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Exception\GuzzleException;
use jjtbsomhorst\garmin\sso\requests\AccessTokenRequest;
use jjtbsomhorst\garmin\sso\requests\CSRFTokenRequest;
use jjtbsomhorst\garmin\sso\requests\DownloadActivityRequest;
use jjtbsomhorst\garmin\sso\requests\DownloadActivityTcxRequest;
use jjtbsomhorst\garmin\sso\requests\LoginRequest;
use jjtbsomhorst\garmin\sso\requests\RetrieveActivitiesRequest;
use jjtbsomhorst\garmin\sso\requests\RetrieveCourseRequest;
use jjtbsomhorst\garmin\sso\requests\RetrieveCoursesRequest;
use jjtbsomhorst\garmin\sso\requests\RetrieveGpxRequest;
use jjtbsomhorst\garmin\sso\requests\ServiceTicketRequest;
use jjtbsomhorst\garmin\sso\requests\SetCookieRequest;
use jjtbsomhorst\garmin\sso\responses\AccessTokenResponse;
use jjtbsomhorst\garmin\sso\responses\CsrfToken;
use jjtbsomhorst\garmin\sso\responses\DownloadActivityResponse;
use jjtbsomhorst\garmin\sso\responses\LoginResponse;
use jjtbsomhorst\garmin\sso\responses\ServiceTicketResponse;
use jjtbsomhorst\garmin\sso\support\ActivityDownload;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use stdClass;

class GarminClient
{
    private Client $client;

    private string $username;

    private string $password;

    private string $cookieDir = '';

    private string $cookieFile = 'cookie_jar.txt';

    private http\AccessToken $accessToken;

    public function __construct() {}

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

    public function cookieFile(string $filename): self
    {
        $this->cookieFile = $filename;

        return $this;
    }

    public function cookieJarLocation(string $path): self
    {
        $this->cookieDir = $path;

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
        $response = $this->client->send(new SetCookieRequest);

        if ($response->getStatusCode() !== 200) {
            throw new Exception($response->getReasonPhrase());
        }

        $response = $this->client->send(new CSRFTokenRequest);
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
                new ServiceTicketRequest($serviceTicket)
            )
        );

        $response->validate();

        $response = new AccessTokenResponse(
            $this->client->send(new AccessTokenRequest)
        );

        $this->accessToken = $response->token();

        return $this;
    }

    private function initClient(): void
    {
        $cookiePath = $this->cookieFile;

        if ($this->cookieDir !== '') {
            $cookiePath = $this->cookieDir.'/'.$this->cookieFile;
        }

        $cookieJar = new FileCookieJar($cookiePath, true);
        if (! isset($this->client)) {
            $this->client = new Client(['cookies' => $cookieJar,  'verify' => false]);
        }
    }

    /**
     * @throws GuzzleException
     * @throws ClientExceptionInterface
     */
    public function getCourses(): ?array
    {
        $request = new RetrieveCoursesRequest($this->accessToken->accessToken);
        $response = $this->send($request);
        $data = json_decode($response->getBody()->getContents(), false);

        return $data->coursesForUser ?? null;
    }

    public function getCourse(string $courseId): stdClass
    {
        $request = new RetrieveCourseRequest($this->accessToken->accessToken, $courseId);
        $response = $this->send($request);

        return json_decode($response->getBody()->getContents(), false);
    }

    public function getCourseGpx(string $courseId): string
    {
        $request = new RetrieveGpxRequest($this->accessToken->accessToken, $courseId);
        $response = $this->send($request);

        return $response->getBody()->getContents();
    }

    /**
     * @return stdClass[]
     *
     * @throws ClientExceptionInterface
     */
    public function getActivities(int $start = 20, int $limit = 20, string $sortBy = 'startLocal', string $sortOrder = 'asc'): array
    {
        $request = new RetrieveActivitiesRequest(token: $this->accessToken->accessToken, start: $start, limit: $limit, sortby: $sortBy, sortOrder: $sortOrder);
        $response = $this->send($request);

        return json_decode($response->getBody()->getContents(), false);
    }

    /**
     * @throws GuzzleException
     * @throws ClientExceptionInterface
     */
    public function getActivitiesBetweenDates(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate, int $start = 0, int $limit = 20, string $sortBy = 'startLocal', string $sortOrder = 'asc'): array
    {
        $request = new RetrieveActivitiesRequest(token: $this->accessToken->accessToken, start: $start, limit: $limit, startDate: $startDate, endDate: $endDate, sortby: $sortBy, sortOrder: $sortOrder);
        $response = $this->send($request);

        return json_decode($response->getBody()->getContents(), false);
    }

    /**
     * @throws GuzzleException
     * @throws ClientExceptionInterface
     *
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
     * @throws Exception
     */
    public function downloadActivityTcx(string $activityId, string $path): bool
    {
        $response = $this->client->send(new DownloadActivityTcxRequest($this->accessToken->accessToken, $activityId));
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
