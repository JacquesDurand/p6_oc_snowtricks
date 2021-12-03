<?php

namespace App\Command\Fixtures;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fixtures:load_prod:categories',
    description: 'Use this command to fill the db with production ready categories',
)]
class FixturesLoadProdCategoriesCommand extends AbstractFixtureCommand
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Start loading categories into database');

        $categories = $this->createCategories();
        $manager = $this->getEntityManager();
        $advance = \count($categories);

        $io->progressStart($advance);

        foreach ($categories as $category) {
            $manager->persist($category);
            $io->progressAdvance();
        }
        $manager->flush();
        $io->progressFinish();
        $io->success('Categories loaded successfully');

        return Command::SUCCESS;
    }

    /**
     * @return Category[]
     */
    private function createCategories(): array
    {
        $categories = [];
        $categoryNames = [
          'Grabs',
          'Rotations',
          'Flips',
          'Desaxed rotations',
            'Slides',
            'One foot',
            'Old school',
        ];

        foreach ($categoryNames as $categoryName) {
            $categories[] = $this->createCategory($categoryName);
        }

        return $categories;
    }

    /**
     * @param string $categoryName
     * @return Category
     */
    private function createCategory(string $categoryName): Category
    {
        $category = new Category();
        $category->setName($categoryName);

        return $category;
    }
}
