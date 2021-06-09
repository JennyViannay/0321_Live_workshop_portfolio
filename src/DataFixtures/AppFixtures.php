<?php

namespace App\DataFixtures;

use App\Entity\AboutMe;
use App\Entity\Illustration;
use App\Entity\Project;
use App\Entity\Techno;
use App\Entity\Timeline;
use App\Service\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $slugger;

    public function __construct(Slugify $slugify)
    {
        $this->slugger = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        //AboutMe
        $aboutMe = new AboutMe();
        $aboutMe->setTitle('Jenny Viannay')
            ->setFunction('Dévelopeuse Web')
            ->setEmail('contact@jennyviannay.com')
            ->setGithubLink('JennyViannay')
            ->setDescription('Lorem ipsum dolor sit amet consectetur adipisicing elit. Placeat fugit corrupti ea repudiandae blanditiis pariatur. Sint nihil, reiciendis voluptatem dolor, dicta quod dolorum unde ea tempore mollitia cumque beatae? Repellendus!')
            ->setAvatar('https://picsum.photos/500/300');

        $manager->persist($aboutMe);
        
        //Timeline
        $year = 2017;
        for ($i = 0; $i < 5; $i++) {
            $timeline = new Timeline();
            $timeline->setYear($year + $i)
                ->setDescription($faker->paragraph(5));

            $manager->persist($timeline);
        }

        //Technos
        $technos = ['PHP', 'Javascript', 'Symfony', 'React', 'Node', 'Bootstrap', 'WebPack Encore', 'Methode SCRUM'];
        $technosPersist = [];
        foreach ($technos as $techno) {
            $new = new Techno();
            $new->setName($techno);

            $manager->persist($new);
            $technosPersist[] = $new;
        }


        //Projects
        for ($i = 0; $i < 5; $i++) {
            $project = new Project();
            $project->setTitle($faker->sentence())
                ->setSlug($this->slugger->generate($project->getTitle()))
                ->setPitch($faker->paragraph(1))
                ->setDescription($faker->paragraph(3))
                ->addTechno($faker->randomElement($technosPersist))
                ->addTechno($faker->randomElement($technosPersist))
                ->addTechno($faker->randomElement($technosPersist))
                ->setGithubLink($faker->domainName())
                ->setWebsiteLink($faker->domainName())
                ->setCreatedAt($faker->datetime())
                ->setIllustration("https://picsum.photos/500/300");

            for ($j = 0; $j < 5; $j++) {
                $illustration = new Illustration();
                $illustration->setImage('https://picsum.photos/500/300')
                    ->setProject($project);
                $manager->persist($illustration);

                $project->addGallery($illustration);
            }

            $manager->persist($project);
        }

        $manager->flush();
    }
}
