<?php
namespace App\Services\DataManager;

use App\Exception\WeatherAppException;
use App\Entity\AppUser;
use App\Services\Api\IpStack\IpDataProvider;
use App\Repository\AppUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception as HttpException;

/**
 * Class AppUserManager
 *
 * @package App\Services\DataManager
 */
class AppUserManager {
    /**
     * @var IpDataProvider
     */
    private IpDataProvider $ipDataProvider;

    /**
     * @var AppUserRepository
     */
    private AppUserRepository $appUserRepo;

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * WeatherPageController constructor.
     *
     * @param IpDataProvider $ipDataProvider
     * @param AppUserRepository $appUserRepo
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        IpDataProvider $ipDataProvider,
        AppUserRepository $appUserRepo,
        EntityManagerInterface $entityManager
    ) {
        $this->ipDataProvider = $ipDataProvider;
        $this->appUserRepo = $appUserRepo;
        $this->entityManager = $entityManager;
    }

    /**
     * @param String $ip
     *
     * @throws HttpException\ClientExceptionInterface
     * @throws HttpException\RedirectionExceptionInterface
     * @throws HttpException\ServerExceptionInterface
     * @throws HttpException\TransportExceptionInterface
     *
     * @return AppUser
     */
    public function manageCoords(string $ip): AppUser
    {
        $user = $this->appUserRepo->findOneBy(['ip' => $ip]);

        if ($user) {
            if ($user->getLatitude() && $user->getLongitude()) {
                return $user;
            } else {
                return $this->manageUserData($ip, $user);
            }
        } else {
            $user = new AppUser();
            return $this->manageUserData($ip, $user);
        }
    }

    /**
     * @param String $ip
     * @param AppUser $user
     *
     * @throws WeatherAppException
     * @throws HttpException\TransportExceptionInterface
     * @throws HttpException\ClientExceptionInterface
     * @throws HttpException\RedirectionExceptionInterface
     * @throws HttpException\ServerExceptionInterface
     *
     * @return AppUser
     */
    private function manageUserData(string $ip, AppUser $user): AppUser
    {
        $userCoords = $this->ipDataProvider->fetchCoords($ip);
        if ($userCoords && $userCoords[AppUser::LATITUDE] && $userCoords[AppUser::LONGITUDE]) {
            $user->setIp($ip)
                ->setLatitude($userCoords[AppUser::LATITUDE])
                ->setLongitude($userCoords[AppUser::LONGITUDE]);
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return $user;
        }
        throw new WeatherAppException(sprintf('Coords not retrieved correctly, IP used: %s', $ip));
    }
}
