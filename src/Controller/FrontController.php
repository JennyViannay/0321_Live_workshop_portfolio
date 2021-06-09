<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Project;
use App\Form\ContactType;
use App\Repository\AboutMeRepository;
use App\Repository\ProjectRepository;
use App\Repository\TimelineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    private $aboutMeRepository;
    private $projectRepository;
    private $timelineRepository;

    public function __construct(
        AboutMeRepository $aboutMeRepository,
        ProjectRepository $projectRepository,
        TimelineRepository $timelineRepository)
    {
        $this->aboutMeRepository = $aboutMeRepository;
        $this->projectRepository = $projectRepository;
        $this->timelineRepository = $timelineRepository;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('front/index.html.twig', [
            'aboutMe' => $this->aboutMeRepository->findAll()[0],
        ]);
    }

    /**
     * @Route("/projets", name="all_projects")
     */
    public function AllProjects(): Response
    {
        return $this->render('front/all_projects.html.twig', [
            'projects' => $this->projectRepository->findAll()
        ]);
    }

    /**
     * @Route("/projet/{slug}", name="show_project")
     */
    public function showProject(Project $project): Response
    {
        return $this->render('front/show_project.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * @Route("/a-propos", name="about_me")
     */
    public function AboutMe(): Response
    {
        return $this->render('front/about_me.html.twig', [
            'timeline' => $this->timelineRepository->findAll(),
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function Contact(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('front/contact.html.twig', [
            'contact' => $contact,
            'form' => $form->createView()
        ]); 
    }
}
