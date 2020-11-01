<?php

namespace App\API;

class ApiMessages
{
    //aqui estão armazenadas todas as mensagens de resposta da API
    //para facilitar a manutenção e concistencia

    public static function message(int $message_id, $additional_information = null)
    {
        $message = null;
        switch ($message_id) {
            case 1:
                $message = "Use algum dos endpoint";
                break;
            case 2:
                $message = $additional_information == null ? "Houve um erro ao realizar alguma operacao" : "Houve um erro ao realizar a operação de " . $additional_information;
                break;
            case 4:
                $message = "Sucesso";
                break;
            case 6:
                $message = "Criado com sucesso";
                break;
            case 8:
                $message = "Algum campo esta incorreto";
                break;
            case 9:
                $message = "Alterado com sucesso";
                break;
            case 11:
                $message = "Deletado com sucesso";
                break;
            case 12:
                $message = $additional_information == null ? "Não encontrado" : $additional_information . " não encontrado";
                break;
            default:
                $message = "Erro desconhecido";
                break;
        }
        return $message;
    }
}
