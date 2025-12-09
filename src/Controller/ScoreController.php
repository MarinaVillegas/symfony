<?php

namespace App\Controller;

use App\Entity\Puntuacion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ScoreController extends AbstractController
{
    #[Route('/score/save', name: 'app_score_save', methods: ['POST'])]
    public function saveScore(Request $request, EntityManagerInterface $em): Response
    { 
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $prevWins = $em->getRepository(Puntuacion::class)
        ->count(['user' => $user, 'sucess' => true]);
        $score = $request->request->get('score');
        $vidas = $request->request->get('vidas');
        $nivel = $request->request->get('nivel');
        $gano = filter_var($request->request->get('gano'), FILTER_VALIDATE_BOOLEAN);
        $time  = $request->request->get('time', 0);
        
        $ultimoIntento = $em->getRepository(Puntuacion::class)
            ->findOneBy(['user' => $user, 'levelReached' => $nivel], ['id' => 'DESC']);

        $tries = 1;
        if ($ultimoIntento) {
            $tries = $ultimoIntento->getTries() + 1;
        }

        $p = new Puntuacion();
        $p->setUser($user);
        $p->setScore($score);
        $p->setLives($vidas);
        $p->setLevelReached($nivel);
        $p->setSucess($gano);
        $p->setEndAt(new \DateTime());
        $p->setTime($time);         
        $p->setTries($prevWins + 1);

        $em->persist($p);
        $em->flush();

        return $this->redirectToRoute('app_ranking');
    }
    #[Route('/score/user', name: 'app_score_user')]
    public function userScores(): Response
{
    /** @var \App\Entity\User $user */
    $user = $this->getUser();

    if (!$user) {
        return $this->redirectToRoute('app_login');
    }
    $puntuaciones = $user->getPuntuacions();
    return $this->render('score/index.html.twig', [
        'puntuaciones' => $puntuaciones,
    ]);
}
    #[Route('/score/ranking', name: 'app_ranking')]
    public function ranking(EntityManagerInterface $em): Response
{
    $puntuaciones = $em->getRepository(Puntuacion::class)
                        ->findBy([], ['score' => 'DESC']);

    return $this->render('score/ranking.html.twig', [
        'puntuaciones' => $puntuaciones,
    ]);
}
}
