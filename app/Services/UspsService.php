<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Cache;

class UspsService
{
    protected $client;
    protected $clientId;
    protected $clientSecret;
    protected $baseUri = 'https://apis.usps.com/';

    public function __construct()
    {
        $this->clientId = config('usps.customer_key');
        $this->clientSecret = config('usps.secret_key');

        $this->client = new Client([
            'base_uri' => $this->baseUri,
        ]);
    }

    /**
     * Get OAuth access token (cached for performance)
     */
    protected function getAccessToken()
    {
        return Cache::remember('usps_access_token', 3500, function () {
            $response = $this->client->post('oauth2/v3/token', [
                'form_params' => [
                    'grant_type'    => 'client_credentials',
                    'client_id'     => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return $data['access_token'];
        });
    }

    /**
     * Look up city and state by ZIP code
     *
     * @param string $zipCode
     * @return array|null ['city' => 'BEVERLY HILLS', 'state' => 'CA', 'zipCode' => '90210']
     */
    public function getCityState($zipCode)
    {
        try {
            $accessToken = $this->getAccessToken();

            $response = $this->client->get('addresses/v3/city-state', [
                'query' => [
                    'ZIPCode' => $zipCode,
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept'        => 'application/json',
                ],
            ]);

            $result = json_decode($response->getBody(), true);

            return [
                'city'    => $result['city'] ?? null,
                'state'   => $result['state'] ?? null,
                'zipCode' => $result['ZIPCode'] ?? $zipCode,
            ];

        } catch (RequestException $e) {
            \Log::error('USPS API Error: ' . $e->getMessage());
            return null;
        }
    }
}