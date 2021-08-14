<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class PostFixtures.
 */
class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 25; ++$i) {
            $post = new Post();
            $post->setTitle('Article N'.$i);
            $post->setContent('Contenu N'.$i);
            $manager->persist($post);

            for ($j = 1; $j <= rand(5, 15); ++$j) {
                $comment = new Comment();
                $comment->setAuthor('Auteur '.$i);
                $comment->setContent('Commentaire N'.$j);
                $comment->setPost($post);
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}
