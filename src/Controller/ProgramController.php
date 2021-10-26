<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * Getting a program by id
     *
     * @Route("/show/{id<^[0-9]+$>}", name="show")
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
     */
    public function showSeason(int $programId, int $seasonId)
    {
        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->find($programId);

        $season = $this->getDoctrine()
        ->getRepository(Season::class)
        ->find($seasonId);

        $episodes = $this->getDoctrine()
        ->getRepository(Episode::class)
        ->findby([
            'season' => $seasonId
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
     * @return Response
     */
    public function showEpisode(Program $programId, Season $seasonId, Episode $episodeId): Response
    {
        $program = $this->getDoctrine()
        ->getRepository(Program::class)
        ->find($programId);

        $season = $this->getDoctrine()
        ->getRepository(Season::class)
        ->find($seasonId);

        $episode = $this->getDoctrine()
        ->getRepository(Episode::class)
        ->find($episodeId);

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
          ]);
    }
    
}
