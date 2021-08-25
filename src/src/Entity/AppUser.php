<?php
namespace App\Entity;

use App\Repository\AppUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AppUserRepository::class)
 */
class AppUser
{
    const LATITUDE = 'latitude';
    const LONGITUDE = 'longitude';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private ?string $ip;

    /**
     * @ORM\Column(type="decimal", precision=16, scale=14, nullable=true)
     */
    private ?string $latitude;

    /**
     * @ORM\Column(type="decimal", precision=18, scale=15, nullable=true)
     */
    private ?string $longitude;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     *
     * @return $this
     */
    public function setIp(string $ip): self
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    /**
     * @param string|null $latitude
     *
     * @return $this
     */
    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    /**
     * @param string|null $longitude
     *
     * @return $this
     */
    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return array
     */
    public function getCoords(): array {
        return [
            self::LATITUDE => $this->getLatitude(),
            self::LONGITUDE => $this->getLongitude()
        ];
    }
}
