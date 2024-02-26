<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Category;
use App\Entity\Article;
use App\Repository\CategoryRepository;
use App\Repository\ArticleRepository;

class CategoryController extends AbstractController
{
    #[Route('/category/{id}', name: 'category_show')]
    public function index(?ArticleRepository $articleRepo, Category $category, ?CategoryRepository $categoryRepo): Response
    {
        return $this->render('category.html.twig', [
            'article' => $articleRepo->findByCategory($category->getId()),
            'category' => $categoryRepo->findAll(),
        ]);
    }
}
