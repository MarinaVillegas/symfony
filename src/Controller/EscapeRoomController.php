<?php

namespace App\Controller;

use App\Entity\Level;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class EscapeRoomController extends AbstractController
{
    #[Route('/home', name: 'escape_home')]
    public function index(SessionInterface $session): Response
    {
        if (!$session->has('startTime')) {
        $tries = $session->get('tries', 0) + 1;
        $session->set('tries', $tries);
        $session->set('score', 0);
        $session->set('lives', 3);
        $session->set('startTime', time());
    }
        return $this->render('escape/home.html.twig');
    }

    #[Route('/escape-room/level/{id}', name: 'escape_level')]
    public function level(Level $level): Response
    {
        return $this->render('escape/level.html.twig', [
            'level' => $level,
        ]);
    }

    #[Route('/escape-room/solve/{id}', name: 'escape_solve', methods: ['POST'])]
public function solve(Request $request, Level $level, EntityManagerInterface $em, SessionInterface $session): Response
{
    $answer = trim(strtolower($request->request->get('answer')));

    // Inicializar sesión de juego si no existe
    if (!$session->has('score')) {
        $session->set('score', 0);
        $session->set('lives', 3);
        $session->set('tries', 1);  // Empieza con 1 intento
        $session->set('startTime', time());
        $session->set('currentLevel', $level->getOrderNumber());
    }

    // Guardar en qué nivel está ahora
    $session->set('currentLevel', $level->getOrderNumber());

    // Si respuesta correcta → sumar puntos
    if ($answer === strtolower($level->getSolution())) {

        $lives = $session->get('lives');
        $points = ($lives === 3) ? 100 : intval(100 * ($lives / 3));
        $session->set('score', $session->get('score') + $points);

        // Siguiente nivel
        $next = $em->getRepository(Level::class)->findOneBy([
            'orderNumber' => $level->getOrderNumber() + 1
        ]);

        if ($next) {
            return $this->redirectToRoute('escape_level', ['id' => $next->getId()]);
        }

        // Ganó el juego
        return $this->redirectToRoute('escape_finish');
    }

    // ❌ RESPUESTA INCORRECTA
    $lives = $session->get('lives') - 1;
    $session->set('lives', $lives);

    // Si perdió todas las vidas → sumar intento + reiniciar vidas
    if ($lives <= 0) {

        // SUMAR INTENTO SOLO AQUÍ
        $session->set('tries', $session->get('tries') + 1);

        // Reiniciar vidas
        $session->set('lives', 3);
        
        return $this->redirectToRoute('game_over');
    }

    // Aún tiene vidas → ir a pantalla de error (pero mantener el nivel actual)
    return $this->redirectToRoute('escape_error');
}

    #[Route('/escape-room/finish', name: 'escape_finish')]
public function finish(SessionInterface $session, EntityManagerInterface $em): Response
{
    $user = $this->getUser();
    if (!$user) {
        return $this->redirectToRoute('app_login');
    }

    $score = $session->get('score', 0);
    $lives = $session->get('lives', 0);

    $tries = $session->get('tries', 1);

    $time = time() - $session->get('startTime', time());
    $levelReached = $em->getRepository(Level::class)->count([]);
    $wonGame = $lives > 0;

    $p = new \App\Entity\Puntuacion();
    $p->setUser($user);
    $p->setScore($score);
    $p->setLives($lives);
    $p->setLevelReached($levelReached);
    $p->setSucess($wonGame);
    $p->setTime($time);
    $p->setTries($tries);
    $p->setEndAt(new \DateTime());

    $em->persist($p);
    $em->flush();

    $session->remove('score');
    $session->remove('lives');
    $session->remove('tries');
    $session->remove('startTime');

    return $this->render('escape/finish.html.twig', [
        'score' => $score,
        'lives' => $lives,
        'tries' => $tries,
        'time' => $time,
        'level' => $levelReached,
        'wonGame' => $wonGame,
    ]);
}
    #[Route('/escape-room/error', name: 'escape_error')]
    public function error(SessionInterface $session): Response
{
    $lives = $session->get('lives', 0);

    if ($lives <= 0) {
        $session->set('score', 0);
        $session->set('lives', 3);

        $session->set('tries', $session->get('tries', 1) + 1);

        return $this->redirectToRoute('escape_level', ['id' => 1]);
    }

    return $this->render('escape/error.html.twig');
}
    #[Route('/escape-room/return', name: 'escape_return')]
public function returnToLevel(SessionInterface $session): Response
{
    $levelId = $session->get('currentLevel', null);

    if (!$levelId) {
        return $this->redirectToRoute('escape_home');
    }

    return $this->redirectToRoute('escape_level', ['id' => $levelId]);
}

#[Route('/gameover', name: 'game_over')]
public function gameover(SessionInterface $session): Response
{
    return $this->render('escape/gameover.html.twig');
}

#[Route('/about', name: 'about')]
public function about(): Response
{
    return $this->render('about.html.twig');
}
}