<?php

namespace App\Controller;

// Import des entités utilisées dans ce contrôleur
use App\Entity\Courses;
use App\Entity\Ressources;
use App\Entity\Results;
use App\Entity\Subjects;

// Import des formulaires liés à ces entités
use App\Form\CoursesForm;
use App\Form\RessourcesForm;
use App\Form\ResultsForm;
use App\Form\SubjectsForm;

// Repository pour accéder aux utilisateurs (ex : élèves)
use App\Repository\UsersRepository;

// Service pour envoyer des emails
use App\Service\EmailService;

// Service d'entité pour sauvegarder en base
use Doctrine\ORM\EntityManagerInterface;

// Contrôleur de base Symfony
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// Classes pour gérer la requête et la réponse HTTP
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Attribut pour définir les routes
use Symfony\Component\Routing\Attribute\Route;

final class TeacherController extends AbstractController
{
    // Route accessible via /teacher, nommée 'app_teacher'
    #[Route('/teacher', name: 'app_teacher')]
    public function index(UsersRepository $usersRepository): Response
    {
        // Interdit l'accès à ceux qui n'ont pas le rôle enseignant
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        // Récupère tous les utilisateurs (potentiellement les élèves)
        $students = $usersRepository->findByRole('ROLE_USER');

        // Rend la vue 'teacher/index.html.twig' en passant la liste des élèves
        return $this->render('teacher/index.html.twig', ['students' => $students]);
    }


    // Route pour ajouter une ressource/document, accessible via /teacher/ajout-document
    #[Route('/teacher/ajout-document', name: 'app_teacher_add_ressource')]
    public function addRessource(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Restriction accès aux enseignants
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        // Création d’une nouvelle entité Ressources
        $cours = new Ressources();

        // Création du formulaire lié à l’entité Ressources
        $form = $this->createForm(RessourcesForm::class, $cours);

        // Traitement des données du formulaire
        $form->handleRequest($request);

        // Si formulaire soumis et valide, persiste la ressource en base
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($cours);
            $entityManager->flush();

            // Redirige vers la page principale enseignant
            return $this->redirectToRoute('app_teacher');
        }

        // Affiche le formulaire d’ajout de document
        return $this->render('teacher/document.html.twig', compact('form'));
    }

    // Route pour gérer les notes, accessible via /teacher/notes
    #[Route('/teacher/notes', name: 'app_teacher_notes')]
    public function notes(Request $request, EntityManagerInterface $entityManager, Courses $course): Response
    {
        // Restreint l’accès aux enseignants
        $this->denyAccessUnlessGranted('ROLE_TEACHER');

        // Création d’une nouvelle entité Results (notes)
        $note = new Results();

        // Création du formulaire pour saisir les notes
        $form = $this->createForm(ResultsForm::class, $note);

        // Traitement des données soumises
        $form->handleRequest($request);

        // Si formulaire soumis et valide, enregistre la note en base
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($note);
            $entityManager->flush();

            // Redirige vers la même page pour éventuellement ajouter d'autres notes
            return $this->redirectToRoute('app_teacher_notes');
        }

        // Affiche le formulaire de saisie des notes
        return $this->render('teacher/notes.html.twig', compact('form'));
    }
}
