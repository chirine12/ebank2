<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserinterfaceController extends AbstractController
{
    #[Route('/userinterface', name: 'app_userinterface')]
    public function index(): Response
    {
        $email = $this->getUser()->getUsername();
        $roles = $this->getUser()->getRoles();
        $name = $this->getUser()->getname();
        if ($roles[0] === 'ROLE_USER') {
            $role = 'Compte Basic';
        } else {
            $role = 'Unknown Role';
        }

        return $this->render('userinterface/index.html.twig', [
            'email' => $email,
            'name' => $name,
            'Main_Balance' => 990.000,
           
            'role' => $role,
            'Investment' =>999
        ]);
    }
    

}
