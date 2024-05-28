<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\ArticleType;
use App\Form\CategoryType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class HomeController extends AbstractController
{
    public function index(?ArticleRepository $articleRepo, ?CategoryRepository $categoryRepo): Response
    {
        return $this->render('home.html.twig', [
            'article' => $articleRepo->findByActive(),
            'category' => $categoryRepo->findAll(),
        ]);
    }

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function addArticle(Request $request, SluggerInterface $slugger){

        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();

            $slug = $slugger->slug($form->getData()->getTitle()).'-'.uniqid();
            $article->setSlug($slug);
            $article->setIsActive(true);

                if ($imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                    try {
                        $imageFile->move(
                            $this->getParameter('medias_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                    
                    }
                    $article->setImage($newFilename);
                }

            $em = $this->doctrine->getManager();
            $em->persist($article);
            $em->flush();
            
        return $this->redirectToRoute('home_show');
        }
        return $this->render('widgets/addArticle.html.twig', array(
            'articleForm' => $form->createView(),
        ));
    }


    public function edit(Request $request, Article $article, SluggerInterface $slugger)
    {

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();
            $slug = $slugger->slug($form->getData()->getTitle()).'-'.uniqid();
            $article->setSlug($slug);

                    if ($imageFile) {
                        $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                        $safeFilename = $slugger->slug($originalFilename);
                        $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                        try {
                            $imageFile->move(
                                $this->getParameter('medias_directory'),
                                $newFilename
                            );
                        } catch (FileException $e) {
                        
                        }
                        $article->setImage($newFilename);
                    }

        
            $this->doctrine->getManager()->flush();
            
            return $this->redirectToRoute('article_show', array('slug' =>$article->getSlug()) );
        }

        return $this->render('widgets/editArticle.html.twig', array(
            'articleForm' => $form->createView(),
            'article' => $article,
        ));
    }

    public function delete(Article $article)
    {
        $article->setIsActive(false);

        /*$images = $article->getImage();

        //delete image from directory
        if($images){
            $imageName = $this->getParameter('medias_directory').'/'.$images;
            if(file_exists($imageName)){
                unlink($imageName);
            }
        }*/

        //delete image from database
        $em = $this->doctrine->getManager();
        
        //$em->remove($article);
        $em->flush();

        return $this->redirectToRoute('home_show');
    }

    public function deleteCategory(Category $category)
    {
        $category->setIsActive(false);
        $em = $this->doctrine->getManager();
        
       // $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('home_show');
    }


    public function addCategory(Request $request, EntityManagerInterface $em, SluggerInterface $slugger)
    {
            $category = new Category();

            $form = $this->createForm(CategoryType::class, $category);
    
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $category->setSlug($slugger->slug($form->getData()->getTitle()).'-'.uniqid());
                $category->setIsActive(true);
                $em = $this->doctrine->getManager();
                $em->persist($category);
                $em->flush();
                
               return $this->redirectToRoute('home_show');
            }
            return $this->render('widgets/addCategory.html.twig', array(
                'categoryForm' => $form->createView(),
            ));
    }
}
