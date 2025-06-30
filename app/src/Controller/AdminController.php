<?php

namespace App\Controller;

use App\Entity\Contacts;
use App\Entity\Courses;
use App\Entity\Subjects;
use App\Entity\Users;
use App\Form\CoursesForm;
use App\Form\SchoolFeesForm;
use App\Form\UsersForm;
use App\Form\SubjectsForm;
use App\Repository\ClassesRepository;
use App\Repository\ContactsRepository;
use App\Repository\CoursesRepository;
use App\Repository\SubjectsRepository;
use App\Repository\UsersRepository;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

// Contrôleur d'administration de l'application : permet de gérer les utilisateurs, les matières, les horaires,
// les messages de contact et l'envoi des frais de scolarité. Accessible uniquement aux utilisateurs avec le rôle 'ROLE_ADMIN'.
final class AdminController extends AbstractController
{
    // Route pour la page d'administration principale.
    // Récupère tous les utilisateurs, classes, matières, contacts et cours,
    // puis affiche la vue admin/index.html.twig avec ces données.
    #[Route('/admin', name: 'app_admin')]
    public function index(UsersRepository $usersRepository): Response
    {

        // Sécurise l'accès, uniquement les admins peuvent accéder
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // Récupération des cours par catégories ou subjects en base
        $users = $usersRepository->findAll();
        $filteredUsers = array_filter($users, function($user) {
            $roles = $user->getRoles();
            return in_array('ROLE_USER', $roles) && count($roles) === 1;
        });
        // Affichage de la vue avec les données
        return $this->render('admin/index.html.twig',['users' => $filteredUsers]);
    }

    #[Route('/admin/gerer/{id}', name: 'app_admin_gerer')]
    public function roleEdit(Users $user, EntityManagerInterface $entityManager, Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(UsersForm::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //dd($user->getRoles());
            $entityManager->flush();
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/gerer.html.twig', compact('form'));
    }

    #[Route('/admin/horaires', name: 'app_admin_timer')]
    public function horaires(CoursesRepository $coursesRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $programs = $coursesRepository->findAll();
        $events = [];
        foreach($programs as $program){
            $events[] = [
                'id' => $program->getId(),
                'title' => $program->getName(),
                'start' => $program->getStartAt()->format('Y-m-d\TH:i:s'),
                'end' => $program->getEndAt()->format('Y-m-d\TH:i:s'),
                'extendedProps' => [
                    'coefficient' => $program->getCoefficient(),
                    'day' => $program->getDay(),
                    'room' => $program->getRoom(),
                ]
            ];
        };
        $horaire = json_encode($events);
        return $this->render('admin/calendar.html.twig', compact('horaire'));
    }

    #[Route('/admin/effacer-message/{id}', name: 'app_admin_delete_message')]
    public function deleteMessage(EntityManagerInterface $entityManager, Contacts $contact): Response
    {
        $entityManager->remove($contact);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/subject', name: 'app_admin_add_subject')]
    public function addSubject(Request $request, EntityManagerInterface $entityManager, EmailService $emailService, UsersRepository $usersRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $subject = new Subjects();
        $form = $this->createForm(SubjectsForm::class, $subject);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($subject);
            $entityManager->flush();

            // Envoie des mails de notifications aux enseignants
            $users = $usersRepository->findAll();
            $teacher = null;
            foreach($users as $user){
                if(in_array('ROLE_TEACHER', $user->getRoles())){
                    $teacher = $user;
                    break;
                }
            }
            $emailService->send(
                'admin@tech-school.fr',
                $teacher->getEmail(),
                'Ajout d\'une nouvelle matiere',
                'teacher-notification',
                [
                    'nomDeLaMatière' => $form->get('name')->getData(),
                    'nomDuTeacher' => $teacher->getLastname(),
                    'prénomDuTeacher' => $teacher->getFirstname()
                ]
            );
            return $this->redirectToRoute('app_teacher');
        }
        return $this->render('admin/subject.html.twig', compact('form'));
    }

    #[Route('/frais-scolaires', name: 'app_admin_school_fees')]
    public function schoolFees(Request $request, UsersRepository $usersRepository, EmailService $emailService, SessionInterface $session): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(SchoolFeesForm::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $name = $form->get('name')->getData();
            $amount = $form->get('amount')->getData();
            $parents = $usersRepository->findByRole('ROLE_PARENT');
            
            foreach($parents as $parent){
                // Envoie de mail de paiement uniquement au parents
                $emailService->send(
                    'admin@tech-school.fr',
                    $parent->getEmail(),
                    "Facture de $name",
                    'facture',
                    [
                        'fees_name' => $name,
                        'amount' => $amount,
                        'parent_firstname' => $parent->getLastname(),
                        'parent_lastname' => $parent->getFirstname(),
                        'parent_id' => $parent->getId(),
                    ]
                );    
            }

            // stocker le nom et le montant des frais
            $request->getSession()->set('name', $name);
            $request->getSession()->set('amount', $amount);
            $request->getsession()->set('parent_id', $parent->getId());
            
            return $this->redirectToRoute('app_admin');
        }
        return $this->render('admin/school-fees.html.twig', compact('form'));
    }

    #[Route('ajouter-cours', name: 'app_admin_add_course')]
    public function addCourse(Request $request, EntityManagerInterface $entityManager, Courses $cours, EmailService $emailService):Response
    {
        // Restreint l'accès aux enseignants uniquement
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Création d'une nouvelle entité Course
        $cours = new Courses();

        // Création du formulaire lié à cette entité
        $form = $this->createForm(CoursesForm::class, $cours);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($cours);

            // Lier l'utilisateur à sa classe
            $classe = $cours->getClasses();
            $users = $cours->getUsers();

            foreach($users as $user) {
                $classe->addUser($user);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_admin_add_course');
        }
        return $this->render('admin/ajout-cours.html.twig', compact('form'));
    }
}
