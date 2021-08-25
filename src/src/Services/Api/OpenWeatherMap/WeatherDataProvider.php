<?php
namespace App\Services\Api\OpenWeatherMap;

use App\Entity\AppUser;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\Exception;

/**
 * Class WeatherDataProvider
 *
 * @package App\Services\Api\OpenWeatherMap
 */
class WeatherDataProvider
{
    private const OPEN_WEATHER_MAP_URL = 'https://api.openweathermap.org/data/2.5/weather';
    private const ACCESS_KEY_PARAM = '?appid=';
    private const LATITUDE_KEY_PARAM = '&lat=';
    private const LONGITUDE_KEY_PARAM = '&lon=';

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

    /**
     * @var ContainerBagInterface
     */
    private ContainerBagInterface $params;

    /**
     * WeatherDataProvider constructor.
     *
     * @param HttpClientInterface $client
     * @param ContainerBagInterface $params
     */
    public function __construct(
        HttpClientInterface $client,
        ContainerBagInterface $params
    ) {
        $this->client = $client;
        $this->params = $params;
    }

    /**
     * @param AppUser $user
     *
     * @throws Exception\RedirectionExceptionInterface
     * @throws Exception\ServerExceptionInterface
     * @throws Exception\TransportExceptionInterface
     * @throws Exception\ClientExceptionInterface
     *
     * @return string|null
     */
    public function fetchWeatherData(AppUser $user): ?string {
        $key = $this->params->get('app.open_weather_map_key');
        $response = $this->client->request(
            'GET',
            self::OPEN_WEATHER_MAP_URL .
                self::ACCESS_KEY_PARAM . $key .
                self::LATITUDE_KEY_PARAM . $user->getLatitude() .
                self::LONGITUDE_KEY_PARAM . $user->getLongitude()
        );

        if ($response->getstatusCode() === 200) {
            return $response->getContent();
        }
        return null;
    }
}
