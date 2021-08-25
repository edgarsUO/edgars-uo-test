<?php

namespace App\Exception;

use Symfony\Component\HttpClient\Exception\TransportException;

/**
 * Class WeatherAppException
 * Extended to catch user level issues and redirect to app errors page
 *
 * @package App\Exception
 */
class WeatherAppException extends TransportException
{

}
