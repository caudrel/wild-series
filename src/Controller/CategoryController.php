<?php

namespace App\Controller;

use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this-> render('category/index.html.twig', ['categories' => $categories,]);
    }

    #[Route('/{categoryName}', name: 'show')]
    public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    {
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);

        if (!$category) {
            throw $this->createNotFoundException(
                "Nous n'avons pas trouvé de séries correspondant à la catégory: '. $categoryName .'."
            );
        }

        $programs = $programRepository->findBy(['category'=> $category], ['id'=>'DESC'], 3);


        if (null === $programs) {
            throw $this->createNotFoundException(
                "No program match with the category: '. $categoryName .' was found in program\'s table."
            );
        }

        return $this-> render('category/show.html.twig', ['category' => $category, 'programs'=>$programs]);
    }
}