<?php
namespace App\Controller;

use App\Exception\WeatherAppException;
use App\Services\Api\OpenWeatherMap\WeatherDataProvider;
use App\Services\DataManager\AppUserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class WeatherPageController
 *
 * @package App\Controller
 */
class WeatherPageController extends AbstractController
{
    /**
     * @var WeatherDataProvider
     */
    private WeatherDataProvider $weatherDataProvider;

    /**
     * @var AppUserManager
     */
    private AppUserManager $appUserManager;

    /**
     * WeatherPageController constructor.
     *
     * @param WeatherDataProvider $weatherDataProvider
     * @param AppUserManager $appUserManager
     */
    public function __construct(
        WeatherDataProvider $weatherDataProvider,
        AppUserManager $appUserManager
    ) {
        $this->weatherDataProvider = $weatherDataProvider;
        $this->appUserManager = $appUserManager;
    }

    /**
     * @Route("/weather", name="weather")
     */
    public function index(Request $request): Response
    {
        $userIp = $request->getClientIp();

        try {
            $user = $this->appUserManager->manageCoords($userIp);
            $weatherData = $this->weatherDataProvider->fetchWeatherData($user);
            if (!$weatherData) {
                throw new WeatherAppException(sprintf(
                    'Weather data not retrieved correctly. Used latitude: %s, longitude: %s',
                    $user->getLatitude(),
                    $user->getLongitude()
                ));
            }

            $form = $this->createFormBuilder()
                ->add('send', SubmitType::class, ['label' => 'Get New Weather Data'])
                ->getForm();
            $response = $this->render(
                'weather.html.twig',
                [
                    'user_ip' => $userIp,
                    'user_coords' => $user->getCoords(),
                    'weather_data' => $weatherData,
                    'form' => $form->createView()
                ]
            );

            // Cache issue present. Content gets shared across multiple users, thus displaying
            // incorrect and private data. Update in progress.
            $response->setSharedMaxAge(3600);

        } catch (WeatherAppException $e) {
            return $this->redirectToRoute('weather-app-error', ['error_message' => $e->getMessage()]);
        }

        return $response;
    }

    /**
     * @Route("/weather-app-error", name="weather-app-error")
     */
    public function errorIndex(Request $request): Response
    {
        return $this->render(
            'error.html.twig',
            ['error_message' => $request->get('error_message')]
        );
    }
}
