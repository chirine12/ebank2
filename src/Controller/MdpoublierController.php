<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\ForgotPasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MdpoublierController extends AbstractController
{
    #[Route('/mdpoublier', name: 'app_mdpoublier')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        // Create the form for password reset
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        // Check if the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Get the email from the form
            $email = $form->get('email')->getData();

            // Find the user by email
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                $token = md5(uniqid($user->getEmail(), true));

                $user->setResetToken($token);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $email = (new Email())
                    ->from('Ebanking.Society@gmail.com') // replace with your email
                    ->to($user->getEmail())
                    ->subject('Password Reset')
                    ->html($this->renderView('emails/reset_password.html.twig', ['token' => $token]));

                $mailer->send($email);

                $this->addFlash('success', 'Password reset email sent. Check your inbox.');

                return $this->redirectToRoute('app_login');
            } else {
                // Add a flash message for no user found
                $this->addFlash('error', 'No user found with that email address.');
            }
        }

        // Render the password reset form
        return $this->render('mdpoublier/index.html.twig', [
            'controller_name' => 'MdpoublierController',
            'form' => $form->createView(),
        ]);
    }
}
