<?php

namespace App\Http\Controllers\Api;

use App\API\ApiError;
use App\API\ApiMessages;
use App\Contatos;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContatosController extends Controller
{
    public function __construct(Contatos $contato)
    {
        $this->contato = $contato;
    }

    public function index()
    {
        $data = ['data' => ApiMessages::message(1)];
        return response()->json($data);
    }

    public function ordenar_por($ordenar_por)
    {
        if (
            $ordenar_por == "id"
            or $ordenar_por == "nome"
            or $ordenar_por == "endereco"
            or $ordenar_por == "telefone"
            or $ordenar_por == "created_at"
            or $ordenar_por == "updated_at"
        ) {
            return true;
        }
        return false;
    }

    public function show(Contatos $id)
    {
        try {
            $data = ['data' => $id];
            return response()->json($data);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage(ApiMessages::message(2, __FUNCTION__), 1010), 500);
        }
    }

    public function criar(Request $request)
    {
        try {
            $contatoData = $request->all();
            if (!isset($contatoData['nome'])) {
                return response()->json(ApiError::errorMessage("Falta colocar um nome", 400), 400);
            }
            if (!isset($contatoData['telefone'])) {
                return response()->json(ApiError::errorMessage("Falta colocar um telefone", 400), 400);
            }
            $contato_criado = $this->contato->create($contatoData);
            return response()->json(['data' => ['id' => $contato_criado->id, 'msg' => ApiMessages::message(6)]], 201);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            if ($e->getCode() == 'HY000') {
                return response()->json(ApiError::errorMessage(ApiMessages::message(8), 1014), 422);
            }
            if ($e->getCode() == '22007') {
                return response()->json(ApiError::errorMessage(ApiMessages::message(8), 1010), 422);
            }
            return response()->json(ApiError::errorMessage(ApiMessages::message(2, __FUNCTION__), 1010), 500);
        }
    }

    public function alterar(Request $request)
    {
        try {
            $contatoData = $request->all();
            if (!isset($contatoData['id'])) {
                return response()->json(ApiError::errorMessage("Falta colocar um id", 400), 400);
            }
            $contato_encontrado = $this->contato->find($contatoData['id']);
            if (isset($contato_encontrado)) {
                $contato_encontrado->update($contatoData);
                return response()->json(['data' => ['msg' => ApiMessages::message(9)]], 200);
            }
            return response()->json(ApiError::errorMessage(ApiMessages::message(12, "Contato"), 404), 404);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            if ($e->getCode() == 'HY000') {
                return response()->json(ApiError::errorMessage(ApiMessages::message(8), 1014), 422);
            }
            if ($e->getCode() == '22007') {
                return response()->json(ApiError::errorMessage(ApiMessages::message(8), 1010), 422);
            }
            return response()->json(ApiError::errorMessage(ApiMessages::message(2, __FUNCTION__), 1010), 500);
        }
    }

    public function deletar(Request $request)
    {
        try {
            $contatoData = $request->all();
            if (!isset($contatoData['id'])) {
                return response()->json(ApiError::errorMessage("Falta colocar um id", 400), 400);
            }
            $contato_encontrado = $this->contato->find($contatoData['id']);
            if (isset($contato_encontrado)) {
                $contato_encontrado->delete($contatoData);
                return response()->json(['data' => ['msg' => ApiMessages::message(11)]], 200);
            }
            return response()->json(ApiError::errorMessage(ApiMessages::message(12, "Contato"), 404), 404);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            if ($e->getCode() == 'HY000') {
                return response()->json(ApiError::errorMessage(ApiMessages::message(8), 1014), 422);
            }
            if ($e->getCode() == '22007') {
                return response()->json(ApiError::errorMessage(ApiMessages::message(8), 1010), 422);
            }
            return response()->json(ApiError::errorMessage(ApiMessages::message(2, __FUNCTION__), 1010), 500);
        }
    }

    public function listar_contatos(Request $request)
    {
        try {
            $contatoData = $request->all();
            $orderBy = "nome";
            if (isset($contatoData['orderby'])) {
                if (ContatosController::ordenar_por($contatoData['orderby'])) {
                    $orderBy = $contatoData['orderby'];
                } else {
                    return \response()->json(["data" => ['msg' => ApiMessages::message(8)]], 422);
                }
            }
            $contatos_encontrados = Contatos::where('id', '<>', null)
                ->orderBy($orderBy)
                ->get();
            if (!$contatos_encontrados->isEmpty()) {
                return response()->json(['data' => $contatos_encontrados], 200);
            }
            return response()->json(['data' => ['msg' => 'Nenhum cliente encontrado']], 404);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010), 500);
        }
    }

    public function buscar_contato(Request $request)
    {
        try {
            $contatoData = $request->all();
            //construindo a query
            $orderBy = "nome";
            $tipoOrderBy = "asc";
            if (isset($contatoData['orderby'])) {
                if (ContatosController::ordenar_por($contatoData['orderby'])) {
                    $orderBy = $contatoData['orderby'];
                } else {
                    return \response()->json(["data" => ['msg' => ApiMessages::message(8)]], 422);
                }
            }
            if (isset($contatoData['tipoorderby'])) {
                if ($contatoData['tipoorderby'] == "asc" or $contatoData['tipoorderby'] == "desc") {
                    $tipoOrderBy = $contatoData['tipoorderby'];
                } else {
                    return \response()->json(["data" => ['msg' => ApiMessages::message(8)]], 422);
                }
            }
            $query = Contatos::query();
            $query->when(
                isset($contatoData['id']),
                function ($q) {
                    $id = request('id');
                    return $q->where('id', '=', $id);
                }
            );
            $query->when(
                isset($contatoData['nome']),
                function ($q) {
                    $nome = request('nome');
                    return $q->where('nome', 'like', "%$nome%");
                }
            );
            $query->when(
                isset($contatoData['telefone']),
                function ($q) {
                    $telefone = request('telefone');
                    return $q->where('telefone', 'like', "%$telefone%");
                }
            );
            $query->when(
                isset($contatoData['endereco']),
                function ($q) {
                    $endereco = request('endereco');
                    return $q->where('endereco', 'like', "%$endereco%");
                }
            );
            $query->when(
                isset($contatoData['created_at']),
                function ($q) {
                    $created_at = request('created_at');
                    return $q->whereDate('created_at',  $created_at);
                }
            );
            $query->when(
                isset($contatoData['updated_at']),
                function ($q) {
                    $updated_at = request('updated_at');
                    return $q->whereDate('updated_at',  $updated_at);
                }
            );
            $query->orderBy($orderBy, $tipoOrderBy);
            $contatos_encontrados = $query->get();
            if ($contatos_encontrados->isEmpty()) {
                return response()->json(["data" => ["msg" => "Nenhum contato encontrado"]], 404);
            }
            return response()->json(["data" => $contatos_encontrados]);
        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1010), 500);
            }
            return response()->json(ApiError::errorMessage('Houve um erro ao realizar a operação de ' . __FUNCTION__, 1010), 500);
        }
    }
}
