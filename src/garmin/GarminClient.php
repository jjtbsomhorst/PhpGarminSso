<?php

namespace garmin\sso;

use garmin\sso\requests\AccessTokenRequest;
use garmin\sso\requests\CSRFTokenRequest;
use garmin\sso\requests\LoginRequest;
use garmin\sso\requests\RetrieveActivitiesRequest;
use garmin\sso\requests\ServiceTicketRequest;
use garmin\sso\requests\SetCookieRequest;
use garmin\sso\responses\AccessTokenResponse;
use garmin\sso\responses\CsrfToken;
use garmin\sso\responses\LoginResponse;
use garmin\sso\responses\ServiceTicketResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\FileCookieJar;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Client\ClientExceptionInterface;

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
     */
    public function login(): self
    {
        $this->initClient();
        $response = $this->client->send(new SetCookieRequest());

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getReasonPhrase());
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
            $this->client->send(
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
    public function getActivities(): array
    {
        $request = new RetrieveActivitiesRequest($this->accessToken->accessToken);
        $response = $this->client->send($request);

        return json_decode($response->getBody()->getContents(), false);
    }

    public function getActivity(string $activityId): ?\stdClass
    {
        return null;
    }

    public function downloadActivity(string $activityId, string $path): bool
    {
        return false;
    }
}
