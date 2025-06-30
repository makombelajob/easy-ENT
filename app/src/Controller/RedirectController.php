<?php

namespace App\Controller;

// Import du contrôleur de base Symfony
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Import de la classe pour gérer la réponse HTTP
use Symfony\Component\HttpFoundation\Response;
// Import de l’attribut pour définir les routes
use Symfony\Component\Routing\Attribute\Route;
// Service pour vérifier les rôles/autorisation d'un utilisateur
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class RedirectController extends AbstractController
{
    // Route accessible via /redirect-after-login, nommée 'app_redirect'
    #[Route('/redirect-after-login', name: 'app_redirect')]
    public function redirectAfterLogin(AuthorizationCheckerInterface $authorizationChecker): Response
    {
        // Si l'utilisateur a le rôle ROLE_ADMIN, redirige vers la route admin
        if ($authorizationChecker->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_admin');
        }

        // Sinon si l'utilisateur a le rôle ROLE_TEACHER, redirige vers la route teacher
        if ($authorizationChecker->isGranted('ROLE_TEACHER')) {
            return $this->redirectToRoute('app_teacher');
        }

        // Sinon si l'utilisateur a le rôle ROLE_PARENT, redirige vers la route parents
        if ($authorizationChecker->isGranted('ROLE_PARENT')) {
            return $this->redirectToRoute('app_parents');
        }

        // Sinon redirige vers la page de profil par défaut (ROLE_USER ou autre)
        return $this->redirectToRoute('app_profile');
    }
}
