<?php

namespace App\Command\Fixtures;

use App\Entity\Category;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fixtures:load_prod:tricks',
    description: 'Use this command to fill the db with production ready tricks',
)]
class FixturesLoadProdTricksCommand extends AbstractFixtureCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Start loading Tricks into database');

        $tricks = $this->createTricks();
        $manager = $this->getEntityManager();
        $advance = \count($tricks);

        $io->progressStart($advance);

        foreach ($tricks as $trick) {
            $manager->persist($trick);
            $io->progressAdvance();
        }
        $io->progressFinish();
        $manager->flush();

        $io->success('Successfully loaded tricks');

        return Command::SUCCESS;
    }

    /**
     * @return Trick[]
     */
    private function createTricks(): array
    {
        $tricks = [];
        $author = $this->getEntityManager()->getRepository(User::class)->findOneBy(['email' => 'admin@snowtricks.com']);

        $tricksTitles = [
            'Japan Air',
            'One foot',
            'Nose slide',
            'Corkscrew',
            'Backflip',
            'Stalefish',
            'Rodeo',
            'Backside 180 Mute',
        ];

        $tricksCategoryNames = [
            'Grabs',
            'One foot',
            'Slides',
            'Desaxed rotations',
            'Flips',
            'Grabs',
            'Desaxed rotations',
            'Grabs',
        ];

        $tricksDescriptions = [
            'A Japan is Essentially a mute grab that you tweak behind your back! 
            A good tweaked out Japan is super entertaining to watch and even more amusing to stomp.
             Adding Japan grabs to your tricks is a sure fire way to get props from your friends.',
            'One footed riding, or more commonly known to snowboarders as "skating", is a technique essential to becoming a well rounded snowboarder.
             This is often one of the first things instructors teach, because you need the skills of skating one footed to ride a lift or try and navigate flat terrain. 
             No one wants "scootch leg" from trying to jump across flat ground with both feet strapped in!',
            'It is pretty easy trick. Ride up to the rail leaning on to your edge and keeping knees slightly bent.
             Once you are on the kicker do an Ollie and jump almost like you are doing a Backside 50-50.
              The difference is that once you push off the kicker you need to turn the board perpendicular to the rail while maintaining the position of your shoulders.
               This is the key to the trick! Now catch your balance by shifting your centre of weight onto your front leg, ride to the end of rail and return the board to its original position.',
            'A very tight aerial rotation of a large jump or in the halfpipe.',
            'There are many tricks you can do on a snowboard, but few of them are more sought after than the backflip.
            Many riders dream of being able to land the exciting flourish. 
            Not only is it impressive, but there’s something fun and magical about flipping through the air and landing safely back on the ground.
            However, it’s an extremely advanced maneuver that many riders will never even attempt.',
            'Another classic – hold onto your heel edge between your bindings using your trailing hand.
             Tip: because it’s a stretch to reach behind your rear binding, push your back foot across at the same time.
              It’s extra stylee that way too.',
            'Doing a rodeo on a snowboard is a trick made to impress. You have to combine all the other tricks to perform a rodeo successfully.
             Though you might master these tricks, combining all of them won’t be easy, much less recommended for beginners.',
            'This is an absolute classic trick that gives you an amazing weightless feeling and provides the basis for various bigger spins.
             Again, you’ll need to be comfortable riding switch (backwards) first so that you can ride away.',
        ];

        foreach ($tricksTitles as $index => $title) {
            $tricks[] = $this->createTrick($title, $tricksCategoryNames[$index], $tricksDescriptions[$index], $author);
        }

        return $tricks;
    }

    private function createTrick(string $title, string $category, string $description, User $author): Trick
    {
        $trick = new Trick();
        $trick->setTitle($title);
        $trick->setAuthor($author);
        $trick->setDescription($description);
        $trick->setState(Trick::TRICK_STATE_AVAILABLE);
        $trick->addCategory($this->getEntityManager()->getRepository(Category::class)->findOneBy(['name' => $category]));

        return $trick;
    }
}
