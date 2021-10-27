<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\ProgramType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/program", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * list of all program
     * 
     * @Route("/", name="index")
     * @return Responses
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
                ->getRepository(Program::class)
                ->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * Add a new program
     * @Route("/new", name="new")
     */
    public function new(Request $request) : Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);

        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            "form" => $form->createView()
        ]);
    }

    /**
     * Getting a program by id
     *
     * @Route("/show/{id<^[0-9]>}", name="show")
     * @return Response
     */
    public function show(int $id):Response
    {
        $program = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findAll();
        

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }
        return $this->render('program/show.html.twig', [
            'id' => $id,
            'program' => $program,
            'seasons' => $seasons
        ]);
    }


    /**
     * @Route("/{programId}/season/{seasonId}", name="season_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programId": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
     */
    public function showSeason(Program $program, Season $season)
    {
        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->find($program);

        $season = $this->getDoctrine()
        ->getRepository(Season::class)
        ->find($season);

        $episodes = $this->getDoctrine()
        ->getRepository(Episode::class)
        ->findby([
            'season' => $season
        ]);

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes
        ]);
    }
    
    /**
     * Getting a episode by id
     * @Route("/{programId}/seasons/{seasonId}/episodes/{episodeId}", name="episode_show")
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"programId": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"seasonId": "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episodeId": "id"}})
     * @return Response
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->find($program);

        $season = $this->getDoctrine()
        ->getRepository(Season::class)
        ->find($season);

        $episode = $this->getDoctrine()
        ->getRepository(Episode::class)
        ->find($episode);

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
          ]);
        }
    }