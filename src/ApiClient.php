<?php

namespace Jorjika\BogPayment;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class ApiClient
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private ?string $accessToken = null;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('bog-payment.base_url'), '/');
        $this->clientId = config('bog-payment.client_id');
        $this->clientSecret = config('bog-payment.secret');
    }

    /**
     * Authenticate and retrieve the access token.
     *
     * @throws RequestException|\Illuminate\Http\Client\ConnectionException
     */
    public function authenticate(): void
    {
        try {
            // This URL is unique for authentication, not using baseUrl
            $authUrl = 'https://oauth2.bog.ge/auth/realms/bog/protocol/openid-connect/token';

            $response = Http::asForm()->withBasicAuth($this->clientId, $this->clientSecret)
                ->post($authUrl, [
                    'grant_type' => 'client_credentials',
                ]);
            if ($response->successful()) {
                $this->accessToken = $response->json()['access_token'];
            } else {
                // Log error for debugging
                Log::error('BOG Authentication Failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw new \Exception('Authentication failed with Bank of Georgia.');
            }
        } catch (\Exception $e) {
            Log::error('Authentication Exception: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Ensure the service is authenticated before making requests.
     */
    private function ensureServiceIsAuthenticated(): void
    {
        if (empty($this->accessToken)) {
            $this->authenticate();
        }
    }

    /**
     * Perform a POST request to the given endpoint.
     *
     * @param  string  $endpoint
     * @param  array  $payload
     *
     * @return mixed
     * @throws RequestException|\Illuminate\Http\Client\ConnectionException
     */
    public function post(string $endpoint, array $payload): mixed
    {
        $this->ensureServiceIsAuthenticated();

        try {
            $url = $this->baseUrl.$endpoint;  // Using base URL for all post requests

            $response = Http::withToken($this->accessToken)
                ->post($url, $payload);

            return $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error('POST Request Exception: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Perform a GET request to the given endpoint.
     *
     * @param  string  $endpoint
     * @param  array  $query
     *
     * @return mixed
     * @throws RequestException|\Illuminate\Http\Client\ConnectionException
     */
    public function get(string $endpoint, array $query = []): mixed
    {
        $this->ensureServiceIsAuthenticated();

        try {
            $url = $this->baseUrl.$endpoint;

            $response = Http::withToken($this->accessToken)
                ->get($url, $query);

            return $this->handleResponse($response);
        } catch (\Exception $e) {
            Log::error('GET Request Exception: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle the response from the HTTP client.
     *
     * @param  \Illuminate\Http\Client\Response  $response
     *
     * @return mixed
     * @throws RequestException|\Exception
     */
    private function handleResponse($response): mixed
    {
        if ($response->successful()) {
            return $response->json();
        }

        // Log error details
        Log::error('API Request Failed', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        throw new \Exception('API request failed with Bank of Georgia.'. $response->body());
    }
}
