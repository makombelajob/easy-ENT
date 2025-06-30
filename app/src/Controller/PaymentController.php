<?php

namespace App\Controller;

use App\Entity\SchoolFees;
use App\Repository\UsersRepository;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Stripe;
use Dompdf\Dompdf;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\IsTrueValidator;

final class PaymentController extends AbstractController
{
    #[Route('/paiement-facture', name: 'app_payment')]
    public function index(EntityManagerInterface $entityManager, Request $request, UsersRepository $usersRepository): Response
    {
        $name = $request->getSession()->get('name');
        $amount = $request->getSession()->get('amount');
        $parentId =$request->getSession()->get('parent_id');
        $parent = $usersRepository->find($parentId);

        Stripe::setApiKey($this->getParameter('stripe.secret.key'));
        
        $facture = new SchoolFees();
        $facture->setUsers($parent);
        $facture->setName($name);
        $facture->setAmount($amount);

        $entityManager->persist($facture);
        $entityManager->flush();
        
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $amount * 100,
                    'product_data' => [
                        'name' =>  $name
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'  => $this->generateUrl('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        return new RedirectResponse($session->url);
    }

    #[Route('/payment/success', name: 'payment_success')]
    public function success(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer, UsersRepository $usersRepository, \Twig\Environment $twig): Response
    {
        $amount = $request->getSession()->get('amount');
        $name = $request->getSession()->get('name');
        $parentId = $request->getSession()->get('parent_id');

        // Récuperation du parent
        $parent = $usersRepository->find($parentId);

        // Récuperer la facture dans la base
        $facture = $entityManager->getRepository(SchoolFees::class)->findOneBy([
            'users' => $parent,
            'name' => $name,
            'amount' => $amount
        ], 
        ['id' => 'DESC']);
        
        // vérification de la facture existante ou pas
        if(!$facture) {
            throw $this->createNotFoundException('Facture introuvale');
        }

        // Générer le PDF
        $html = $twig->render('payment/facture.html.twig', compact('facture'));

        $domPdf = new Dompdf;
        $domPdf->loadHtml($html);
        $domPdf->setPaper('A4', 'portrait');
        $domPdf->render();
        $pdfOutPut = $domPdf->output();

        // Stockage du pdf dans un dossier temporaire
        $factureFileName = 'facture_' . $parent->getId() . '.pdf';
        $factureFilePath = $this->getParameter('kernel.project_dir') . '/public/uploads/factures/' . $factureFileName;

        file_put_contents($factureFilePath, $pdfOutPut);

        $request->getSession()->set('facture_filename', $factureFileName);
        $pdfUrl = $this->generateUrl(
            'default_facture_download',
            ['filename' => $factureFileName],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        // Envoie du mail à l'élève
        $email = (new TemplatedEmail())
                ->from('noreply@tech-school.fr')
                ->to($parent->getEmail())
                ->subject('Votre facture de paiment')
                ->htmlTemplate('emails/facture-pdf.html.twig')
                ->attachFromPath($factureFilePath, $factureFileName, 'application/pdf')
                ->context([
                    'name' => $name,
                    'amount' => $amount,
                    'facture' => $facture,
                    'parent' => $parent,
                    'pdfUrl' => $pdfUrl,
                ])
                
        ;
        $mailer->send($email);

        return $this->render('payment/success.html.twig', compact('amount', 'name', 'factureFileName'));
    }

    #[Route('/payment/cancel', name: 'payment_cancel')]
    public function cancel(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }
}
