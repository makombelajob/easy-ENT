<?php

namespace App\Controller;

// Import du contrôleur de base Symfony
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Import de la classe Response pour gérer les réponses HTTP
use Symfony\Component\HttpFoundation\Response;
// Import de l’attribut pour définir les routes
use Symfony\Component\Routing\Attribute\Route;
// Service pour hasher les mots de passe utilisateurs
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
// Formulaire pour générer un token de réinitialisation
use App\Form\GenerateTokenForm;
// Formulaire pour réinitialiser le mot de passe
use App\Form\ResetPasswordForm;
// Repository pour accéder aux données des utilisateurs
use App\Repository\UsersRepository;
// Service d'envoi d'email
use App\Service\EmailService;
// Service d'entité pour persister les données
use Doctrine\ORM\EntityManagerInterface;
// Classe pour manipuler les requêtes HTTP
use Symfony\Component\HttpFoundation\Request;

final class ResetPasswordController extends AbstractController
{
    // Route affichant la page principale de réinitialisation de mot de passe
    // Route pour générer un token de réinitialisation (formulaire de demande)
    #[Route('/reset-password', name: 'app_reset_password')]
    public function generateToken(Request $request, EntityManagerInterface $entityManager, UsersRepository $usersRepository, EmailService $emailService): Response
    {
        // Création du formulaire de demande de token
        $formResetPass = $this->createForm(GenerateTokenForm::class);
        // Traitement des données envoyées
        $formResetPass->handleRequest($request);

        // Si formulaire soumis et valide
        if ($formResetPass->isSubmitted() && $formResetPass->isValid()) {
            // Récupération de l’email du formulaire
            $email = $formResetPass->get('email')->getData();
            // Recherche de l'utilisateur correspondant à l'email
            $user = $usersRepository->findOneBy(['email' => $email]);

            // Si utilisateur non trouvé
            if (!$user) {
                // Message générique (pour ne pas révéler si email existe ou non)
                $this->addFlash('error', 'Si votre compte existe, nous venons d\'envoyer un message dans votre boite mail');
                // Redirection vers la page de login
                return $this->redirectToRoute('app_login');
            }

            // Génération d'un token sécurisé aléatoire
            $token = bin2hex(random_bytes(16));
            // Attribution du token à l'utilisateur
            $user->setResetToken($token);

            // Sauvegarde du token dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Envoi d'un email avec le token de réinitialisation
            $emailService->send(
                'admin@tech-school.fr',
                $email,
                'Demande de reinitialisation de mots de passe',
                'reset-pass',
                [
                    'token' => $token,
                ]
            );

            // Message flash pour informer l'utilisateur (à revoir pour la clarté)
            $this->addFlash('success', 'Si votre compte existe, nous venons d\'envoyer un message dans votre boite mail');
            // Redirection vers la page de login
            return $this->redirectToRoute('app_login');
        }

        // Affiche le formulaire de demande de token
        return $this->render('reset_password/generate-token.html.twig', compact('formResetPass'));
    }

    // Route pour réinitialiser le mot de passe via le token envoyé par mail
    #[Route('/reinitialiser-mots-de-passe/{token}', name: 'app_reset_pass_user')]
    public function resetPasswordLink(string $token, UsersRepository $usersRepository, Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        // Recherche de l'utilisateur avec le token donné
        $user = $usersRepository->findOneBy([
            'resetToken' => $token
        ]);

        // Si aucun utilisateur trouvé (token invalide ou expiré)
        if (!$user) {
            $this->addFlash('error', 'Lien invalide ou exipirée');
            return $this->redirectToRoute('app_reset_password');
        }

        // Création du formulaire pour entrer un nouveau mot de passe
        $form = $this->createForm(ResetPasswordForm::class);
        // Traitement des données du formulaire
        $form->handleRequest($request);

        // Si formulaire soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération du nouveau mot de passe en clair
            $newPassword = $form->get('plainPassword')->getData();

            // Hashage du nouveau mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
            // Mise à jour du mot de passe de l'utilisateur
            $user->setPassword($hashedPassword);
            // Suppression du token pour empêcher réutilisation
            $user->setResetToken(null);

            // Sauvegarde des changements en base
            $entityManager->persist($user);
            $entityManager->flush();

            // Message flash de succès
            $this->addFlash('success', 'Mot de passe réinitialisé avec succès.');
            // Redirection vers la page de login
            return $this->redirectToRoute('app_login');
        }

        // Affichage du formulaire de réinitialisation de mot de passe
        return $this->render('reset_password/passwd-reset.html.twig', compact('form'));
    }
}
