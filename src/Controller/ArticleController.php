<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\articleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Article;

class ArticleController extends AbstractController
{

    #[Route('/article/{id}', name: 'article_show')]
    public function index(Article $article, ?CategoryRepository $categoryRepo): Response
    {
        return $this->render('article.html.twig', [
            'article' => $article,
            'category' => $categoryRepo->findAll(),
        ]);
    }

}
