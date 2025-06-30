<?php

namespace App\Controller;

// Import des entités Contacts et Users
use App\Entity\Contacts;
use App\Entity\Users;
// Import des formulaires personnalisés pour ajouter un parent et pour contact étudiant
use App\Form\AddParentForm;
use App\Form\StudentsContactForm;
use App\Repository\UsersRepository;
// Service pour l'envoi d'emails
use App\Service\EmailService;
// Gestionnaire d'entités Doctrine pour gérer la base de données
use Doctrine\ORM\EntityManagerInterface;
// Contrôleur de base Symfony
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Service de sécurité pour accéder à l'utilisateur connecté
use Symfony\Bundle\SecurityBundle\Security;
// Gestionnaire des requêtes HTTP
use Symfony\Component\HttpFoundation\Request;
// Réponse HTTP
use Symfony\Component\HttpFoundation\Response;
// Attribut pour définir les routes
use Symfony\Component\Routing\Attribute\Route;
// Service pour le hachage des mots de passe utilisateurs
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class ProfileController extends AbstractController
{
    // Injection du service de hachage de mot de passe via constructeur (PHP 8 propriété promotion)
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    // Route /profile, nommée 'app_profile'
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager, Security $security, UsersRepository $usersRepository): Response
    {
        // Vérifie que l'utilisateur a le rôle ROLE_USER, sinon accès interdit
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Nouvelle instance de Contact pour formulaire
        $contact = new Contacts();
        /**
         * @var Users $user
         */
        // Récupère l'utilisateur actuellement connecté
        $user = $security->getUser();

        // Récupère les classes associées à cet utilisateur
        $classes = $user->getClasses();

        // Récupère les parents liés à cet utilisateur

        $parents = $user->getParent();

        // Crée un formulaire de contact étudiant avec l'objet Contact
        $studentMessage = $this->createForm(StudentsContactForm::class, $contact);

        // Rend la vue profile/index.html.twig en passant les variables nécessaires
        return $this->render('profile/index.html.twig', compact('studentMessage', 'classes', 'parents'));
    }

    // Route /tous-les-cours, nommée 'app_profile_courses'
    #[Route('/tous-les-cours', name: 'app_profile_courses')]
    public function courses(Security $security): Response
    {
        // Vérifie que l'utilisateur est authentifié avec le rôle ROLE_USER
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Récupère l'utilisateur connecté
        $user = $security->getUser();
        /**
         * @var Users $user
         */
        // Récupère la liste des cours liés à l'utilisateur
        $courses = $user->getCourses();

        // Affiche la vue profile/courses.html.twig en passant la liste des cours
        return $this->render('profile/courses.html.twig', compact('courses'));
    }

    // Route /ajouter-responsable, nommée 'app_add_parent'
    #[Route('/ajouter-responsable', name: 'app_add_parent')]
    public function addParent(EntityManagerInterface $entityManager, Request $request, EmailService $emailService): Response
    {
        // Accès réservé aux utilisateurs avec rôle ROLE_USER
        $this->denyAccessUnlessGranted('ROLE_USER');

        // Création d'une nouvelle entité utilisateur (parent)
        $parent = new Users();

        // Création du formulaire d'ajout de parent lié à l'entité $parent
        $form = $this->createForm(AddParentForm::class, $parent);

        // Traitement de la requête dans le formulaire (soumission)
        $form->handleRequest($request);

        // Si formulaire soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de l'utilisateur connecté (l'élève)
            $student = $this->getUser();

            // Attribution du rôle ROLE_PARENT à ce nouvel utilisateur
            $parent->setRoles(['ROLE_PARENT']);

            // Association du parent à l'élève (enfant)
            $parent->addUser($student);

            // Génère un mot de passe aléatoire sécurisé (16 caractères hexadécimaux)
            $plainPassword = bin2hex(random_bytes(8));

            // Hash le mot de passe avec le service passwordHasher
            $hashPassword = $this->passwordHasher->hashPassword($parent, $plainPassword);

            // Définit le mot de passe haché sur l'entité parent
            $parent->setPassword($hashPassword);

            // Prépare l'entité parent pour être persistée en base
            $entityManager->persist($parent);

            // Exécute les requêtes en base pour sauvegarder le parent
            $entityManager->flush();
            /**
             * @var Users $student
             */
            // Envoi d'un email au parent avec ses infos et le mot de passe généré
            $emailService->send(
                'admin@tech-school.fr',            // Expéditeur
                $parent->getEmail(),               // Destinataire
                'Responble ajouter',              // Sujet de l'email (typo possible ici)
                'responsable',                    // Template email
                [                                // Variables pour le template
                    'nom' => $parent->getLastname(),
                    'prénom' => $parent->getFirstname(),
                    'password' => $plainPassword,
                    'parent_email' => $parent->getEmail(),
                    'nom_eleve' =>  $student->getLastname(),
                    'prenom_eleve' =>  $student->getFirstname(),
                ]
            );

            // Ajoute un message flash de succès à afficher à l'utilisateur
            $this->addFlash('success', "Responsable ajoutér avec success");

            // Redirige vers la page de profil
            return $this->redirectToRoute('app_profile');
        }

        // Si formulaire non soumis ou invalide, affiche la page avec le formulaire
        return $this->render('profile/add-parent.html.twig', compact('form'));
    }
}
