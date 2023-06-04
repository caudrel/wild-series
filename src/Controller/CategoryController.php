<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    /**
     * @param CategoryRepository $categoryRepository
     * list all categories
     * @return Response*
     */
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this-> render('category/index.html.twig', ['categories' => $categories,]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        // Create de new Category Object
        $category = new Category();

        // Create the form, linked with $category
        $form = $this->createForm(CategoryType::class, $category);

        // Get data from HTTP request
        $form->handleRequest($request);

        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);

            $this->addFlash('success', 'La nouvelle catégorie a bien été ajoutée.');
            // Redirect to categories list
            return $this->redirectToRoute('category_index');
        }

        // Render the form
        return $this->render('category/new.html.twig', [
            'form' => $form,
        ]);
        }

    /**
     * @param string $categoryName
     * @param CategoryRepository $categoryRepository
     * List all programs from 1 category order by DESC and limit 3
     * @param ProgramRepository $programRepository
     * @return Response
     */
    #[Route('/{categoryName}', name: 'show')]
    public function show(
        string $categoryName,
        CategoryRepository $categoryRepository,
        ProgramRepository $programRepository): Response
    {
        $category = $categoryRepository->findOneByName($categoryName);

        if (null ===$category) {
            throw $this->createNotFoundException(
                "Nous n'avons pas trouvé de séries correspondant à la catégory: '. $categoryName .'."
            );
        }

        $programs = $programRepository->findByCategory($category, ['id'=>'DESC'], 3);

        if (null === $programs) {
            throw $this->createNotFoundException(
                "No program match with the category: '. $categoryName .' was found in program\'s table."
            );
        }

        return $this-> render('category/show.html.twig', [
            'category' => $category,
            'programs'=>$programs]);
    }
}