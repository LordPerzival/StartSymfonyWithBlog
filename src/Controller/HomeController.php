<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Form\articleType;
use App\Form\categoryType;
use App\Entity\Article;
use App\Entity\Category;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_show')]
    public function index(?ArticleRepository $articleRepo, ?CategoryRepository $categoryRepo): Response
    {
        return $this->render('home.html.twig', [
            'article' => $articleRepo->findAllByRecent(),
            'category' => $categoryRepo->findAll(),
        ]);
    }

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    #[Route('/ajouter_un_article', name: 'addArticle')]
    public function addArticle(Request $request, EntityManagerInterface $em, SluggerInterface $slugger)
    {
            $article = new Article();

            $form = $this->createForm(articleType::class, $article);
    
            $form->handleRequest($request, $article);

            if ($form->isSubmitted() && $form->isValid()) {

                $imageFile = $form->get('image')->getData();

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

        
    #[Route('/modifier_un_article/{id}', name: 'editArticle')]
    public function edit(Request $request, Article $article, SluggerInterface $slugger)
    {

        $form = $this->createForm(articleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();

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
            
            return $this->redirectToRoute('article_show', array('id' =>$article->getId()) );
        }

        return $this->render('widgets/editArticle.html.twig', array(
            'articleForm' => $form->createView(),
            'article' => $article,
        ));
    }

    
    #[Route('/article/{id}/supprimer', name: 'delArticle')]
    public function delete(Request $request, Article $article)
    {
        $em = $this->doctrine->getManager();
        
        $em->remove($article);
        $em->flush();

        return $this->redirectToRoute('home_show');
    }

    #[Route('/ajouter_une_categorie', name: 'addCategory')]
    public function addCategory(Request $request, EntityManagerInterface $em)
    {
            $category = new Category();

            $form = $this->createForm(categoryType::class, $category);
    
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->doctrine->getManager();
                $em->persist($category);
                $em->flush();
                dump($form);
                
               return $this->redirectToRoute('home_show');
            }
            return $this->render('widgets/addCategory.html.twig', array(
                'categoryForm' => $form->createView(),
            ));
    }
}
