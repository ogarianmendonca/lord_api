<?php

namespace App\Http\Controllers;

use App\Services\PerfilService;
use Exception;
use Illuminate\Http\JsonResponse;

/**
 * Class PerfilController
 * @package App\Http\Controllers
 */
class PerfilController extends Controller
{
    /**
     * @var PerfilService
     */
    private $service;

    /**
     * PerfilController constructor.
     * @param PerfilService $service
     */
    public function __construct(PerfilService $service)
    {
        $this->service = $service;
    }

    /**
     * Busca todos os perfis cadastrados
     *
     * @return JsonResponse
     */
    public function buscarTodos()
    {
        try{
            $perfis = $this->service->buscaPerfis();
            return response()->json($perfis);
        } catch (Exception $e){
            return response()->json(['message' => 'Listagem não disponível!'], 409);
        }
    }
}
