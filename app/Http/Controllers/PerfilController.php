<?php

namespace App\Http\Controllers;

use App\Services\PerfilService;
use Exception;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function buscarTodos()
    {
        try{
            $perfis = $this->service->buscaPerfis();
            return response()->json(compact('perfis'));
        } catch (Exception $e){
            return response()->json(['message' => 'Listagem de peril não disponível!'], 409);
        }
    }
}
