<?php

namespace App\Controller;

use App\Entity\Beneficiaire;
use App\Entity\Client;
use App\Entity\Comptecourant;
use App\Entity\Virement;
use Knp\Snappy\Pdf as SnappyPdf;
use App\Form\VirementType;
use App\Repository\BeneficiaireRepository;
use App\Repository\ClientRepository;
use App\Repository\ComptecourantRepository;
use App\Repository\VirementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;



#[Route('/virement')]
class VirementController extends AbstractController
{
    #[Route('/', name: 'app_virement_index', methods: ['GET'])]
    public function mesVirements(VirementRepository $virementRepository, ClientRepository $clientRepository): Response
    {
        $user = $this->getUser();
        $client = $clientRepository->findOneBy(['user' => $user]);
    
        if (!$client) {
            throw $this->createNotFoundException('Le client associé à l\'utilisateur connecté n\'existe pas.');
        }
    
        $virements = $virementRepository->findBy(['client' => $client]);
    
        return $this->render('virement/index.html.twig', [
            'virements' => $virements,
            'client' => $client,
        ]);
    }

    #[Route('/new', name: 'app_virement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Security $security, MailerInterface $mailer): Response
    {
        $soldeInsuffisantErreur = null;
        $user = $security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login'); // Redirige vers la page de connexion si l'utilisateur n'est pas connecté
        }

        $client = $user->getClient(); // Supposons que getUser() retourne un User qui a une méthode getClient()

        if (!$client) {
            // Gérer l'absence de client associé
            $this->addFlash('error', 'Aucun client associé à cet utilisateur.');
            return $this->redirectToRoute('app_virement_index'); // Ou une autre page appropriée
        }

        $compteCourant = $client->getCompteCourant();
         // Supposons que getClient() retourne un Client qui a une méthode getCompteCourant()

        if (!$compteCourant) {
            // Gérer l'absence de compte courant associé
            $this->addFlash('error', 'Aucun compte courant associé à ce client.');
            return $this->redirectToRoute('app_virement_index'); // Ou une autre page appropriée
        }

        $rib = $compteCourant->getRib(); // Supposons que getCompteCourant() retourne un CompteCourant qui a une méthode getRib()

        $virement = new Virement();
        $virement->setSource($rib); // Pré-remplir le champ source avec le RIB du client
        $virement->setClient($client);
        $virement->setDate(new \DateTime());  // On initialise la
        $beneficiaireId = $request->query->get('beneficiaireId'); // Récupère l'ID du bénéficiaire depuis les paramètres de requête

if ($beneficiaireId) {
    $beneficiaireRepository = $entityManager->getRepository(Beneficiaire::class);
    $beneficiaire = $beneficiaireRepository->find($beneficiaireId);

    if ($beneficiaire) {
        $virement->setDestinataire($beneficiaire->getRib()); // Pré-remplir le champ destinataire avec le RIB du bénéficiaire
    } else {
        // Gérer le cas où le bénéficiaire n'est pas trouvé
        $this->addFlash('error', 'Bénéficiaire non trouvé.');
        
    }
}
        $form = $this->createForm(VirementType::class, $virement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compteSource = $client->getCompteCourant();
    $compteDestinataire = $entityManager->getRepository(Comptecourant::class)->findOneBy(['Rib' => $virement->getDestinataire()]);
    
    if ($compteSource->getSolde() >= $virement->getMontant()) {
        // Mise à jour des soldes
        $compteSource->setSolde($compteSource->getSolde() - $virement->getMontant());
        $compteDestinataire->setSolde($compteDestinataire->getSolde() + $virement->getMontant());

        $entityManager->persist($compteSource);
        $entityManager->persist($compteDestinataire);

            $entityManager->persist($virement);
            $entityManager->flush();
            $clientBeneficiaire = $compteDestinataire->getClient();
            $transport = Transport::fromDsn('smtp://Ebanking.Society@gmail.com:ypbuklkwyqlktqmi@smtp.gmail.com:587');
            $mailer = new Mailer($transport);
            if ($clientBeneficiaire) {
            
            $email = (new Email())
    ->from('Ebanking.Society@gmail.com') // Remplacez par votre adresse email
    ->to($clientBeneficiaire->getEmail()) // Assurez-vous que votre entité Client a une méthode getEmail()
    ->subject('Confirmation de Virement')
    ->html("<p>Bonjour, vous avez reçu un virement. Veuillez vérifier votre historique de transactions pour savoir qui vous a transféré de l'argent. </p>");

$mailer->send($email);}
            

            return $this->redirectToRoute('app_virement_index', [], Response::HTTP_SEE_OTHER);
        }
        else {
            // Gérer le cas où le solde est insuffisant

            $soldeInsuffisantErreur = 'Solde insuffisant pour effectuer le virement.';
        
     }

       
    }
    return $this->render('virement/new.html.twig', [
        'virement' => $virement,
        'form' => $form->createView(),
        'soldeInsuffisantErreur' => $soldeInsuffisantErreur,
    ]);
}

 
#[Route('/{id}', name: 'app_virement_show', methods: ['GET'])]
public function show(Virement $virement): Response
{
    return $this->render('virement/show.html.twig', [
        'virement' => $virement,
    ]);
}



    #[Route('/{id}/edit', name: 'app_virement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Virement $virement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VirementType::class, $virement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_virement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('virement/edit.html.twig', [
            'virement' => $virement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_virement_delete', methods: ['POST'])]
    public function delete(Request $request, Virement $virement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$virement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($virement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_virement_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/list/pdf', name: 'app_virement_list_pdf', methods: ['GET'])]
public function virementListPdf(VirementRepository $virementRepository, SnappyPdf $snappy, Security $security, ComptecourantRepository $compteCourantRepository): Response
{
    // Obtenez l'utilisateur actuellement connecté
    $user = $security->getUser();
    if (!$user) {
        throw $this->createNotFoundException('Utilisateur non trouvé');
    }

    $client = $user->getClient();
    if (!$client) {
        throw $this->createNotFoundException('Client non trouvé pour cet utilisateur');
    }

    $compteCourant = $client->getComptecourant();
    if (!$compteCourant) {
        throw $this->createNotFoundException('Compte courant non trouvé pour ce client');
    }

    $rib = $compteCourant->getRib();
    $virements = $virementRepository->findVirementsByClient($rib); // Utilisez la méthode adaptée pour obtenir les virements

    $html = $this->renderView('virement/list_pdf.html.twig', [
        'virements' => $virements,
        'client' => $client,
    ]);

    $pdfContent = $snappy->getOutputFromHtml($html);

    return new Response(
        $pdfContent,
        200,
        [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="historique_virements.pdf"'
        ]
    );
}

    // src/Controller/VirementController.php

    
    
        #[Route('/virements/historique', name: 'virements_historique')]
        public function historique(VirementRepository $virementRepository, ComptecourantRepository $compteCourantRepository, Security $security): Response
        {
            // Obtenez l'utilisateur actuellement connecté
            $user = $security->getUser();
            if (!$user) {
                throw $this->createNotFoundException('Utilisateur non trouvé');
            }
    
            $client = $user->getClient();
            if (!$client) {
                throw $this->createNotFoundException('Client non trouvé pour cet utilisateur');
            }
    
            $compteCourant = $client->getComptecourant();
            if (!$compteCourant) {
                throw $this->createNotFoundException('Compte courant non trouvé pour ce client');
            }
    
            $rib = $compteCourant->getRib();
            $virements = $virementRepository->findVirementsByClient($rib);
    
            return $this->render('virement/historique.html.twig', [
                'virements' => $virements,
            ]);
        
    }




}
