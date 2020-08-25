<?php

namespace App\Http\Controllers;

use Exception;
use App\Interfaces\PerfilInterface;
use Illuminate\Http\JsonResponse;

/**
 * Class PerfilController
 * @package App\Http\Controllers
 */
class PerfilController extends Controller
{
    /**
     * @var PerfilInterface
     */
    private PerfilInterface $perfilRepository;

    /**
     * PerfilController constructor.
     * @param PerfilInterface $perfilRepository
     */
    public function __construct(PerfilInterface $perfilRepository)
    {
        $this->perfilRepository = $perfilRepository;
    }

    /**
     * Busca todos os perfis cadastrados
     *
     * @return JsonResponse
     */
    public function buscarPerfis(): JsonResponse
    {
        try {
            $perfis = $this->perfilRepository->buscarPerfis();
            return response()->json($perfis);
        } catch (Exception $e) {
            return response()->json(['message' => 'Listagem não disponível!'], 409);
        }
    }
}
