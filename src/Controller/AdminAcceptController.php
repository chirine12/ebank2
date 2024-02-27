<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Compteep;
use App\Repository\DemandeDesacCERepository;
use App\Service\SMSService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Exceptions\TwilioException;

class AdminAcceptController extends AbstractController
{
    #[Route('/admin/accept/index', name: 'app_admin_accept_index')]
    public function index(DemandeDesacCERepository $demandeDesacCERepository): Response
    {
        $demandes = $demandeDesacCERepository->findAll();
        return $this->render('admin_accept/index.html.twig', [
            'controller_name' => 'AdminAcceptController',
            'demande_desac_c_es' => $demandes,
        ]);
    }
    private $smsService;

public function __construct(SMSService $smsService)
{
    $this->smsService = $smsService;
}

#[Route('/admin/accept/{id}', name: 'app_admin_accept', methods: ['GET'])]
public function accept(
    DemandeDesacCERepository $demandeDesacCERepository,
    EntityManagerInterface $entityManager,
    $id
): RedirectResponse {
    $demande = $demandeDesacCERepository->find($id);

    if (!$demande) {
        $this->addFlash('danger', 'Demande non trouvée.');
        return $this->redirectToRoute('app_admin_accept_index');
    }

    // Vérifier le solde du compte avant la désactivation
    $compte = $demande->getCompteep();

    if ($compte instanceof Compteep) {
        $solde = $compte->getSolde();

        if ($solde < 0) {
            $this->addFlash('danger', 'Impossible de désactiver le compte, solde négatif.');
            return $this->redirectToRoute('app_admin_accept_index');
        }

        // Récupérer le numéro de téléphone du client associé au compte
        $client = $compte->getClient();

        if ($client instanceof Client) {
        
            // Mettre à jour l'état du compte
            if ($compte->isEtat() !== null) {
                $compte->setEtat(false); // Or set it to true if that's the expected behavior.
                $entityManager->persist($compte);
                $entityManager->remove($demande);
                $entityManager->flush();
                $accountSid = 'ACf8274d9e2bd87fe4670877052f812508';
                $authToken = 'b2e40d854a8115bd87fbc86fe33889bd';
                $twilioPhoneNumber = '+19514358768';
                
                // Créez une instance du service SMS
                $smsService = new SMSService($accountSid, $authToken, $twilioPhoneNumber);
                try {
                        // Utilisez le numéro de téléphone du client
                        $toPhoneNumber = '+' . $client->getTel();
                        var_dump($toPhoneNumber);
                        $smsService->sendAccountDeactivationSMS($toPhoneNumber);
                  
                    echo "SMS sent successfully!";
                } catch (TwilioException $e) {
                    echo 'Error: ' . $e->getMessage();
                }
            } else {
                $this->addFlash('danger', 'Erreur lors de la mise à jour de l\'état du compte.');
            }

            return $this->redirectToRoute('app_admin_accept_index');
        }
    }

    $this->addFlash('danger', 'Compte non trouvé.');
    return $this->redirectToRoute('app_admin_accept_index');
}




#[Route('/admin/refuse/{id}', name: 'app_admin_refuse', methods: ['GET'])]
public function refuse(
    DemandeDesacCERepository $demandeDesacCERepository,
    EntityManagerInterface $entityManager,
    $id
): RedirectResponse {
    $demande = $demandeDesacCERepository->find($id);

    if (!$demande) {
        $this->addFlash('danger', 'Demande non trouvée.');
        return $this->redirectToRoute('app_admin_accept_index');
    }

    // Supprimer la demande sans désactiver le compte
    $entityManager->remove($demande);
    $entityManager->flush();

    $this->addFlash('success', 'Demande refusée et supprimé.');
    return $this->redirectToRoute('app_admin_accept_index');
}

}
