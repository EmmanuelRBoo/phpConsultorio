<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadesController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $entity;
    /**
     * @var EspecialidadeRepository
     */
    private $repository;

    public function __construct(
        EntityManagerInterface $entity,
        EspecialidadeRepository $repository
    ) {
        $this->entity = $entity;
        $this->repository = $repository;
    }

    #[Route('/especialidades', methods:"POST")]
    public function postEspecialidade(Request $request): Response {
        $data = json_decode($request->getContent());

        $especialidade = new Especialidade();
        $especialidade->setDescricao($data->descricao);

        $this->entity->persist($especialidade);
        $this->entity->flush();

        return new JsonResponse($especialidade);
    }

    /**
     * @Route("/especialidades", methods={"GET"})
     */
    public function getEspecialidades(): Response {
        $data = $this
            ->repository
            ->findAll();

        return new JsonResponse($data);
    }

   /** 
    * @Route("/especialidades/{id}", methods={"GET"}) 
    */ 
    public function getEspecialidadesById(int $id): Response {
        $data = $this
            ->repository
            ->find($id);

        return new JsonResponse($data);
    }

    /**
     * @Route("/especialidades/{id}", methods={"PUT"})
     */
    public function putEspecialidades(int $id, Request $request): Response {
        $data = json_decode($request->getContent());

        $especialidade = $this->repository->find($id);
        $especialidade
            ->setDescricao($data->descricao);
        
        $this->entity->flush();
    
        return new JsonResponse($especialidade);
    }

    /**
     * @Route("/especialidades/{id}", methods={"DELETE"})
     */
    public function deleteEspecialidades(int $id, Request $request): Response {
        $especialidade = $this->repository->find($id);

        $this->entity->remove($especialidade);
        $this->entity->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
