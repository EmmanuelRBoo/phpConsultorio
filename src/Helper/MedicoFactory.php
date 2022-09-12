<?php

namespace App\Helper;

use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;

class MedicoFactory {
    /**
     * @var EspecialidadeRepository
     */
    private $especialidadeRepository;
    public function __construct(EspecialidadeRepository $especialidadeRepository) {
        $this->especialidadeRepository = $especialidadeRepository;
    }

    public function createMedico(string $json): Medico {
        $data = json_decode($json);
        
        $especialidadeId = $data->especialidadeId;
        $especialidade = $this->especialidadeRepository->find($especialidadeId);

        $medico = new Medico();
        $medico
            ->setCrm($data->crm)
            ->setNome($data->nome)
            ->setEspecialidade($especialidade);

        return $medico;
    }
}