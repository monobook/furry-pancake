<?php

namespace App\Entity;

use App\Repository\TickerDataRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TickerDataRepository::class)
 */
class TickerData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $symbol;

    /**
     * @ORM\Column(type="float")
     */
    private $lastPrice;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @param string $symbol
     * @param array $payload
     */
    public function __construct(string $symbol, array $payload = [])
    {
        $this->symbol = $symbol;
        $this->createdAt = new \DateTimeImmutable();

        $this->lastPrice = !empty($payload[1][6])
            ? $payload[1][6]
            : 0.0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function getLastPrice(): ?float
    {
        return $this->lastPrice;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
}
