<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use App\Service\ProgramDuration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Mime\Address;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this-> render('program/index.html.twig', ['programs' => $programs,]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProgramRepository $programRepository, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        // Create de new Program Object
        $program = new Program();

        // Create the form, linked with $program
        $form = $this->createForm(ProgramType::class, $program);

        // Get data from HTTP request
        $form->handleRequest($request);

        // Was the form submitted ?
        if ($form->isSubmitted() && $form->isValid()) {

            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);

            $programRepository->save($program, true);

            // Once the form is submitted n Valid and data inserted into database, define success flash message
            $this->addFlash('success', 'La nouvelle série a bien été ajoutée.');

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('lozach@gmail.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('Program/newProgramEmail.html.twig', ['program' => $program]));

            $mailer ->send($email);

            // Redirect to programs list
            return $this->redirectToRoute('program_index');
        }


        // Render the form
        return $this->render('program/new.html.twig', [
            'form' => $form,
        ]);
    }



    #[Route('/{slug}', name: 'show')]
    public function show(Program $program, ProgramDuration $programDuration): Response
    {
        $timeInMinutes = $programDuration->calculate($program);
        $timeInDayHoursMinutes = $programDuration->convertisseurTime($timeInMinutes);

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'programDuration' => $timeInMinutes,
            'convertisseurTime' => $timeInDayHoursMinutes,
        ]);
    }

    #[Route('/{program}/', name: 'program_show')]
    public function showSeason(Program $program, Season $season, Actor $actor): Response
    {
        return $this->render('program/season_show.html.twig', [
            'season' => $season,
            'program' => $program,
            'actor' => $actor
        ]);
    }

    #[Route('/{program}/season/{season}/episode/{episode}', name: 'episode_show')]
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig', [
            'season' => $season,
            'program' => $program,
            'episode' => $episode
         ]);
    }

}