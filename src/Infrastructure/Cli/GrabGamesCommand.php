<?php

namespace App\Infrastructure\Cli;

use App\Application\DTO\DistributorDTO;
use App\Application\UseCase\Game\InsertOrUpdateGames\Command as  InsertOrUpdateGames;
use App\Infrastructure\Service\DataGrabber\AlawarDataGraber;
use App\Infrastructure\Service\DataGrabber\IGraber;
use App\Infrastructure\Service\DataGrabber\SteamPayDataGraber;
use JsonException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GrabGamesCommand extends Command
{
    private MessageBusInterface $bus;
    private IGraber $alawar;
    private IGraber $steamPay;

    public function __construct(MessageBusInterface $bus, AlawarDataGraber $alawar, SteamPayDataGraber $steamPay)
    {
        parent::__construct();
        $this->bus = $bus;
        $this->alawar = $alawar;
        $this->steamPay = $steamPay;
    }

    protected function configure(): void
    {
        $this
            ->setName('grab:games')
            ->setDescription('Grab games data');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws JsonException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ini_set('memory_limit', '1024M');
        $games = $this->alawar->grabGames();
        $this->bus->dispatch(new InsertOrUpdateGames(new DistributorDTO('alawar'), $games));
        $games = $this->steamPay->grabGames();
        $this->bus->dispatch(new InsertOrUpdateGames(new DistributorDTO('steam_pay'), $games));
        return 0;
    }
}
