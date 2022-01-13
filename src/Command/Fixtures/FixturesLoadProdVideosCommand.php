<?php

declare(strict_types=1);

namespace App\Command\Fixtures;

use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fixtures:load_prod:videos',
    description: 'Use this command to fill the db with production ready videos',
)]
class FixturesLoadProdVideosCommand extends AbstractFixtureCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Start loading Videos into database');

        $videos = $this->createVideos();
        $manager = $this->getEntityManager();
        $advance = \count($videos);

        $io->progressStart($advance);

        foreach ($videos as $video) {
            $manager->persist($video);
            $io->progressAdvance();
        }
        $io->progressFinish();
        $manager->flush();

        $io->success('Successfully loaded videos');

        return Command::SUCCESS;
    }

    /**
     * @return array
     */
    private function createVideos(): array
    {
        $videos = [];

        $videosLinks = [
            'Japan Air' => 'https://www.youtube.com/embed/X_WhGuIY9Ak',
            'One foot' => 'https://www.youtube.com/embed/4IVdWdvsrVA',
            'Nose slide' => 'https://www.youtube.com/embed/oAK9mK7wWvw',
            'Corkscrew' => 'https://www.youtube.com/embed/FMHiSF0rHF8',
            'Backflip' => 'https://www.youtube.com/embed/SlhGVnFPTDE',
            'Stalefish' => 'https://www.youtube.com/embed/f9FjhCt_w2U',
            'Rodeo' => 'https://www.youtube.com/embed/pI-iykKk_z4',
            'Backside 180 Mute' => 'https://www.youtube.com/embed/lB6p4B8o-ho',
        ];

        foreach ($videosLinks as $trick => $link) {
            $videos[] = $this->createVideo($trick, $link);
        }
        return $videos;
    }

    /**
     * @param string $trick
     * @param string $link
     * @return Video
     */
    private function createVideo(string $trick, string $link): Video
    {
        $trick = $this->getEntityManager()->getRepository(Trick::class)->findOneBy(['title' => $trick]);

        $video = new Video();
        $video->setLink($link);

        $trick->addVideo($video);
        return $video;
    }
}
