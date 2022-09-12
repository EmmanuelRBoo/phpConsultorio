<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController extends AbstractController{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var MedicoFactory
     */
    private $medicoFactory;
    public function __construct(EntityManagerInterface $entityManager, MedicoFactory $medicoFactory) {
        $this->entityManager = $entityManager;
        $this->medicoFactory = $medicoFactory;
    }

    /**
     * @Route("/medicos", methods={"POST"})
     */
    public function postMedicos(Request $request): Response {

        $body = $request->getContent();
        $medico = $this->medicoFactory->createMedico($body);

        $this->entityManager->persist($medico);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

    /**
     * @Route("/medicos", methods={"GET"})
     */
    public function getMedicos(): Response {
        
        $data = $this
            ->entityManager
            ->getRepository(Medico::class);

        $medicos = $data->findAll();

        return new JsonResponse($medicos);
    }

    /**
     * @Route("/medicos/{id}", methods={"GET"})
     */
    public function getMedicoById(int $id): Response {

        $medico = $this->buscaMedico($id);
        $status = is_null($medico) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($medico, $status);
    }

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function putMedico(int $id, Request $request): Response {

        $body = $request->getContent();

        $medico = $this->medicoFactory->createMedico($body);

        $newMedico = $this->buscaMedico($id);
        $newMedico
            ->setCrm($medico->getCrm())
            ->setNome($medico->getNome());

        $status = is_null($newMedico) ? Response::HTTP_NOT_FOUND : 200;

        $this->entityManager->flush();

        return new JsonResponse($newMedico, $status);
    }

    /**
     * @Route("/medicos/{id}", methods={"DELETE"})
     */
    public function deleteMedico(int $id, Request $request): Response {

        $medico = $this->buscaMedico($id);

        $this->entityManager->remove($medico);
        $this->entityManager->flush($medico);

        return new JsonResponse("", Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     */
    public function buscaMedico(int $id) { 
        $data = $this
            ->entityManager
            ->getRepository(Medico::class);

        return $data->find($id);
    }
}