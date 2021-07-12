<?php

namespace App\Command;

use App\Service\TickerService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TickerDataCommand extends Command
{
    private const T_BTCUSD = 'tBTCUSD';

    protected static $defaultName = 'app:ticker-data';

    protected $tickerService;

    public function __construct(string $name = null, TickerService $tickerService)
    {
        $this->tickerService = $tickerService;

        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->addArgument('symbol', InputArgument::REQUIRED, 'Symbol for ticker channel.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symbol = $input->getArgument('symbol');
        if ($symbol !== self::T_BTCUSD) {
            throw new \LogicException(sprintf('%s symbol not implemented.', $symbol));
        }

        $this->tickerService->update($symbol);

        return Command::SUCCESS;
    }
}