<?php

declare(strict_types=1);

namespace Rector\Website\Blog\Controller;

use DateTimeInterface;
use Rector\Website\Blog\Repository\PostRepository;
use Rector\Website\Blog\ValueObject\Post;

use Rector\Website\ValueObject\Routing\RouteName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RssController extends AbstractController
{
    public function __construct(
        private readonly PostRepository $postRepository,
    ) {
    }

    #[Route(path: 'rss.xml', name: RouteName::RSS)]
    public function __invoke(): Response
    {
        $posts = $this->postRepository->getPosts();

        return $this->render('blog/rss.twig', [
            'posts' => $posts,
            'most_recent_post_date_time' => $this->getMostRecentPostDateTime($posts),
        ]);
    }

    /**
     * @param Post[] $posts
     */
    private function getMostRecentPostDateTime(array $posts): DateTimeInterface
    {
        $firstPostKey = array_key_first($posts);
        $firstPost = $posts[$firstPostKey];

        return $firstPost->getDateTime();
    }
}
