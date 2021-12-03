<?php

namespace App\Command\Fixtures;

use App\Entity\Picture;
use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fixtures:load_prod:pictures',
    description: 'Use this command to fill the db with production ready pictures',
)]
class FixturesLoadProdPicturesCommand extends AbstractFixtureCommand
{
    private const TRICK_PICTURE_DIRECTORY = 'uploads/trick_pictures/';

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Start loading Pictures into database');

        $pictures = $this->createPictures();
        $manager = $this->getEntityManager();
        $advance = \count($pictures);

        $io->progressStart($advance);

        foreach ($pictures as $picture) {
            $manager->persist($picture);
            $io->progressAdvance();
        }
        $io->progressFinish();
        $manager->flush();

        $io->success('Successfully loaded pictures');

        return Command::SUCCESS;
    }

    /**
     * @return Picture[]
     */
    private function createPictures(): array
    {
        $pictures = [];

        $picturesInfo = [
          'Japan Air' => [
              'japan-air-1.jpg',
              'japan-air-2.jpg',
              ],
            'One foot' => [
                'one-foot-1.jpg',
            ],
            'Nose slide' => [
                'nose-slide-1.jpg',
            ],
            'Corkscrew' => [
                'cork-1.jpg',
                'cork-2.jpg',
            ],
            'Backflip' => [
                'backflip-1.jpg',
                'backflip-2.jpg',
            ],
            'Stalefish' => [
                'stalefish-1.jpg',
                'stalefish-2.jpg',
            ],
            'Rodeo' => [
                'rodeo-1.jpg',
            ],
            'Backside 180 Mute' => [
                'bs-180-mute-1.jpg',
            ],
        ];

        foreach ($picturesInfo as $trick => $files) {
            foreach ($files as $file) {
                $pictures[] = $this->createPicture($file, $trick);
            }
        }

        return $pictures;
    }

    /**
     * @param string $file
     * @param string $trickName
     * @return Picture
     */
    private function createPicture(string $file, string $trickName): Picture
    {
        $trick = $this->getEntityManager()->getRepository(Trick::class)->findOneBy(['title' => $trickName]);

        $picture = new Picture();
        $picture->setPath(self::TRICK_PICTURE_DIRECTORY . $file);
        $picture->setAlt(str_replace('.jpg', '', $file));

        $trick->addPicture($picture);

        return $picture;
    }
}
