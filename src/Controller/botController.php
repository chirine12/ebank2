<?php

namespace App\Controller;

use Sun\Contract\Country;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/bot')]
class botController extends AbstractController
{
    #[Route('/Assurance/bot', name: 'app_bot')]
    public function index(Request $request): Response
    {
       $a = "fdg";
     
    
         
    $qa = [
        'Bonjour' => 'Bonjour ! Comment puis-je vous aider ?',
        'la resolution de N=NP' => 'Je suis désolé, je ne suis pas capable de répondre à cette question pour le moment.',
        'fb'=>$a,
       
        'Quelles sont les types ?' => 'tu peux consulter ce site https://www.routard.com/guide/tunisie/85/traditions.htm',
    ];
    $message = $request->request->get('message');
    if (array_key_exists($message, $qa)) {
        $response = $qa[$message];
    } else {
        $response = 'bienvenue chez chatbot de E-bank';
    }
    return $this->render('bot/index.html.twig', [
        'response' => $response
    ]);

}}