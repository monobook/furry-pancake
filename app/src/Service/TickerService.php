<?php

namespace App\Service;

use Amp;
use Amp\Delayed;
use Amp\Websocket;
use Amp\Websocket\Client;

use App\Entity\TickerData;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class TickerService
{
    protected const URL = 'wss://api-pub.bitfinex.com/ws/2';

    protected $em;
    protected $logger;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function update(string $symbol)
    {
        Amp\Loop::run(function () use ($symbol){
            /** @var Client\Connection $connection */
            $connection = yield Client\connect(self::URL);

            yield $connection->send(json_encode([
                'event' => 'subscribe',
                'channel' => 'ticker',
                'symbol' => $symbol,
            ]));

            /** @var Websocket\Message $message */
            while ($message = yield $connection->receive()) {
                try {
                    $payload = yield $message->buffer();

                    if (!empty(json_decode($payload, true)[1][6])) {
                        $entity = new TickerData($symbol, json_decode($payload, true));

                        $this->em->persist($entity);
                        $this->em->flush();

                        $this->logger->debug(sprintf('Added payload: %s', $payload));
                    }

                    yield new Delayed(100);
                } catch (\Exception $exception) {
                    $this->logger->error($exception->getMessage());
                }
            }
        });
    }

    public function isValidPeriod(int $period): bool
    {
        return $this->em->getRepository(TickerData::class)->isPeriod($period);
    }

    public function calculateSmaForPeriod(int $period):? float
    {
        return $this->em->getRepository(TickerData::class)->getSmaByPeriod($period);
    }
}