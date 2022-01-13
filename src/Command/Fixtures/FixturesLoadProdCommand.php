<?php

namespace App\Command\Fixtures;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fixtures:load_prod',
    description: 'Use this command to fill the db with production ready data',
)]
class FixturesLoadProdCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Start loading production data');

        $result = $this->runCommand('app:fixtures:load_prod:categories', $output);
        $result = $result && $this->runCommand('app:fixtures:load_prod:users', $output);
        $result = $result && $this->runCommand('app:fixtures:load_prod:tricks', $output);
        $result = $result && $this->runCommand('app:fixtures:load_prod:pictures', $output);
        $result = $result && $this->runCommand('app:fixtures:load_prod:videos', $output);

        return $result ? Command::SUCCESS : Command::FAILURE;
    }

    private function runCommand(string $commandName, OutputInterface $output): int
    {
        return Command::SUCCESS === $this->getApplication()->find($commandName)->run(new ArrayInput([]), $output);
    }
}
