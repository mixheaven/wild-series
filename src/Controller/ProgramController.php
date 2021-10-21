<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/program", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="program")
     */
    public function index(): Response
    {
        return $this->render('program/index.html.twig', [
            'website' => 'Wild Séries',
        ]);
    }

    /**
     * @Routre("/{movie<[a-z0-9.-]*>}, name="program_show", methods="{GET}")
     */
    public function show(string $movie)
    {   
        if(!empty($slug))
        {
            $movieShow = str_replace('-', ' ', $movie);
            $movieShow = ucwords($movieShow);
        }else
        {
            $movieShow = 'Ce programme n\' exite pas, veuillez selectionner un programme existant';
        }
        return $this->render('program/show.html.twig',[
            'movieShow' => $movieShow,
        ]);
        

    }
}
