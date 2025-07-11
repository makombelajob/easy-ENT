<?php

namespace App\Controller;

// Import des classes nécessaires
use App\Entity\Contacts; // Entité représentant un message de contact
use App\Form\ContactsForm; // Formulaire lié à l'entité Contacts
use Doctrine\ORM\EntityManagerInterface; // Interface pour gérer la persistance en base
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // Contrôleur de base Symfony
use Symfony\Component\HttpFoundation\Request; // Requête HTTP
use Symfony\Component\HttpFoundation\Response; // Réponse HTTP
use Symfony\Component\Routing\Attribute\Route; // Annotation pour définir les routes

final class MainController extends AbstractController
{
    // Route racine '/' nommée 'app_main'
    #[Route('/', name: 'app_main')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'une nouvelle instance de Contact
        $contact = new Contacts();

        // Création du formulaire de contact basé sur ContactsForm, lié à l'objet $contact
        $formContact = $this->createForm(ContactsForm::class, $contact);

        // Traitement de la requête HTTP pour alimenter le formulaire
        $formContact->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($formContact->isSubmitted() && $formContact->isValid()) {
            // Sauvegarde de l'objet Contact en base de données
            $entityManager->persist($contact);
            $entityManager->flush();

            // Ajout d'un message flash de succès
            $this->addFlash('success', 'Votre message a bien été envoyé');

            // Redirection vers la même route (page d'accueil)
            return $this->redirectToRoute('app_main');
        }

        // Affiche la vue avec le formulaire de contact (non soumis ou non valide)
        return $this->render('main/index.html.twig', compact('formContact'));
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('main/about.html.twig');
    }
}
