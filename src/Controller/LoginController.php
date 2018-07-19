<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function index()
    {
        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function delete(SessionInterface $session)
    {
      $session->remove('user'); // suppresion de la utilisateur session
      return $this->redirectToRoute('home');
    }
    /**
     * @Route("/login-process", name="login-process")
     */
    public function process(Request $request, SessionInterface $session)
    {
      $email =$request->request->get('email');
      $password =$request->request->get('password');
      $userRepo=$this->getDoctrine()->getRepository(User::class);
      // nous devons récupére l'utilisateur le mot de passe ET l'email coorespond aux valeur postée
      // les méthodes générique  ->findAll() et finBy.. ne sont pas inadaptés=> création  et utilisatsion d'une méthode de recherche personnalisée (ds UserRepository)
      $users=$userRepo->findByEmailAndPassword($email,$password);
      //var_dump($users);
      if (count($users)==0) {
          return new Response ('Utilisateur Inconnu ou Mot de passe erroné !');
      }else {
        // Utilisateur Trouvé
        // Enregistrement ds la Session
        $session->set('user',$users[0]);
      //  return new Response('Admin '.$session->get('user')->getFirstname().' est Connecté :)');
      // redurection a la page home
          return $this->redirectToRoute('home');
      }
    }


}
