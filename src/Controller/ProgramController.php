<?php

namespace App\Controller;

use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this-> render('program/index.html.twig', ['programs' => $programs,]);
    }

    #[Route('/show/{id<^[0-9]+$>}', name: 'show')]
    public function show(int $id, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);
        // same as $program = $programRepository->find($id);

        if (!$program) {
            throw $this->createNotFoundException(
                "No program with id: '.$id.' found in program\'s table."
            );
        }

        $program = $programRepository->find($id);
        $seasons = $seasonRepository->find($id);

        return $this-> render('program/show.html.twig', ['program' => $program, 'seasons' => $seasons]);
    }

    #[Route('/{programId}/season/{seasonId}', name: 'season_show')]
    public function showSeason(
        int $programId,
        int $seasonId,
        ProgramRepository $programRepository,
        SeasonRepository $seasonRepository)
    {
        $seasons = $seasonRepository->find($seasonId);
        $program = $programRepository->find($programId);
        return $this->render('program/season_show.html.twig', [
            'seasons' => $seasons,
            'program' => $program]);

    }

}