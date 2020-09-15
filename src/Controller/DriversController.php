<?php

namespace App\Controller;

use App\Entity\Drivers;
use App\Form\DriversType;
use App\Repository\DriversRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/drivers")
 */
class DriversController extends AbstractController
{
    /**
     * @Route("/", name="drivers_index", methods={"GET"})
     */
    public function index(DriversRepository $driversRepository): Response
    {
        return $this->render('drivers/index.html.twig', [
            'drivers' => $driversRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="drivers_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $driver = new Drivers();
        $form = $this->createForm(DriversType::class, $driver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($driver);
            $entityManager->flush();

            return $this->redirectToRoute('drivers_index');
        }

        return $this->render('drivers/new.html.twig', [
            'driver' => $driver,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="drivers_show", methods={"GET"})
     */
    public function show(Drivers $driver): Response
    {
        return $this->render('drivers/show.html.twig', [
            'driver' => $driver,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="drivers_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Drivers $driver): Response
    {
        $form = $this->createForm(DriversType::class, $driver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('drivers_index');
        }

        return $this->render('drivers/edit.html.twig', [
            'driver' => $driver,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="drivers_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Drivers $driver): Response
    {
        if ($this->isCsrfTokenValid('delete'.$driver->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($driver);
            $entityManager->flush();
        }

        return $this->redirectToRoute('drivers_index');
    }
}
