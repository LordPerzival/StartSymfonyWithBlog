<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ArticleController extends AbstractController
{
    
public function index(ArticleRepository $articleRepo, Article $article, ?CategoryRepository $categoryRepo): Response
    {
        return $this->render('article.html.twig', [
            'article' => $article,
            'category' => $categoryRepo->findAll(),
        ]);
    }
}
