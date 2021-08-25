<?php
namespace App\Services\Api\IpStack;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Contracts\HttpClient\Exception;

/**
 * Class IpDataProvider
 *
 * @package App\Services\Api\IpStack
 */
class IpDataProvider
{
    private const IPSTACK_URL = 'http://api.ipstack.com/';
    private const ACCESS_KEY_PARAM = '?access_key=';
    private const FIELDS_PARAM = '&fields=type,latitude,longitude';

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $client;

    /**
     * @var ContainerBagInterface
     */
    private ContainerBagInterface $params;

    /**
     * IpDataProvider constructor.
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
     * @param $ip
     *
     * @throws Exception\ClientExceptionInterface
     * @throws Exception\RedirectionExceptionInterface
     * @throws Exception\ServerExceptionInterface
     * @throws Exception\TransportExceptionInterface
     *
     * @return array|null
     */
    public function fetchCoords($ip): ?array {
        $key = $this->params->get('app.ipstack_key');
        $response = $this->client->request(
            'GET',
            self::IPSTACK_URL . $ip .
                self::ACCESS_KEY_PARAM . $key .
                self::FIELDS_PARAM
        );

        $content = json_decode(
            // Format floats to strings so trailing decimals would be preserved
            preg_replace(
                '/:\s*(\-?\d+(\.\d+)?([e|E][\-|\+]\d+)?)/',
                ': "$1"',
                $response->getContent()
            ),
            true
        );

        // Response always 200, check 'type' instead
        if ($content['type']) {
            return $content;
        }
        return null;
    }
}
