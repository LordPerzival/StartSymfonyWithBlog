<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    
    public function index(?ArticleRepository $articleRepo, Category $category, ?CategoryRepository $categoryRepo): Response
    {
        return $this->render('category.html.twig', [
            'article' => $articleRepo->findByCategory($category->getId()),
            'category' => $categoryRepo->findAll(),
        ]);
}
}
