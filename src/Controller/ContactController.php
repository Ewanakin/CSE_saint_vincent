<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $em, $contact=null): Response
    {
        $form = $this->createForm(ContactType::class, $contact, array('action'=>$this->generateUrl('app_contact')));
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $contact = $form->getData();
            $em->persist($contact);
            $em->flush($contact);
            return $this->redirectToRoute('app_page_accueil');
        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/contact', name: 'contact_list')]
    #[Route('/admin/contact/delete/{contact}', name: 'contact_delete')]
    public function listContact(EntityManagerInterface $em, Contact $contact=null): Response
    {
        $contacts = $em->getRepository(Contact::class)->findAll();
        if($contact!=null)
        {
            $em->remove($contact);
            $em->flush();
            return $this->redirectToRoute('contact_list');
        }
        return $this->render('contact/list.html.twig', [
            'contacts' => $contacts,
        ]);
    }
}
