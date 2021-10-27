<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use PHPUnit\Framework\MockObject\ClassAlreadyExistsException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Responses
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
       
        return $this->render('category/index.html.twig', [
            'categories' => $categories
        ]);
    }
    /**
     * Create a new Category
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        $category = new Category();

    
            
        $form = $this->createForm(CategoryType::class, $category);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig',[
            "form" => $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/{categoryName}", name="show")
     * @return Response
     */
    public function show(string $categoryName): Response
    {
        $category = $this->getDoctrine()
                    ->getRepository(Category::class)
                    ->findOneBy(['name' => $categoryName]);

        if(!$category)
        {
            throw $this->createNotFoundException('
            Cette categorie n\'existe pas');
        }

        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category' => $category], ['id' => 'DESC'], 3);

        return $this->render('category/show.html.twig',[
            'category' => $category,
            'programs' => $programs
        ]);
    }

    /**
     * @Route("/{categoryName}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, $categoryName): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryName->getName(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categoryName);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index', [], Response::HTTP_SEE_OTHER);
    }
    
}
