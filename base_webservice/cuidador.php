<?php
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "model/Pet.php";
require_once "model/Cuida.php";
require_once "model/Cuidador.php";
require_once "configs/utils.php";
require_once "configs/methods.php";

if (isMetodo("POST")) {
    if (parametrosValidos($_POST, ["nome", "email"])) {
        $nome = $_POST["nome"];
        $email = $_POST["email"];
        if (Cuidador::existeEmail($email)) {
            if (Cuidador::cadastrar($nome, $email)) {
                $msg = ["status" => "Cadastro de cuidador com sucesso!"];
                responder(201, $msg);
            } else {
                $msg = ["status" => "Cadastro de cuidador deu algum erro :("];
                responder(500, $msg);
            }
        } else {
            $msg = ["status" => "Email já cadastrado :("];
            responder(500, $msg);
        }
    } else {
        echo "Deu ruim";
    }
}


if (isMetodo("GET")) {
    $resultado = Cuidador::listar();
    responder(200, $resultado);
}


if (isMetodo("DELETE")) {
    if (parametrosValidos($_DELETE, ["id"])) {
        $id = $_DELETE["id"];

        if (Cuidador::existe($id)) {
            if (Cuidador::excluir($id)) {
                $msg = ["status" => "Cuidador deletado!!"];
                responder(200, $msg);
            } else {
                $msg = ["status" => "Não foi possível deletar o cuidador :("];
                responder(400, $msg);
                exit;
            }
        } else {
            $msg = ["status" => "ID de cuidador não encontrado :("];
            responder(400, $msg);
        }
    } else {
        $msg = ["status" => "ID de cuidador não informado "];
        responder(400, $msg);
    }
}

if (isMetodo("PUT")) {
    if (parametrosValidos($_PUT, ["id", "nome", "email"])) {
        $id = $_PUT["id"];
        $nome = $_PUT["nome"];
        $email = $_PUT["email"];

        if (Cuidador::existeEmailAlterar($email, $id)) {
            if (Cuidador::existe($id)) {
                if (Cuidador::editar($id, $nome, $email)) {
                    $msg = ["status" => "Cuidador atualizado com sucesso!"];
                    responder(201, $msg);
                } else {
                    $msg = ["status" => "Atualização de cuidador deu algum erro :("];
                    responder(500, $msg);
                }
            } else {
                $msg = ["status" => "Cuidador não cadastrado :("];
                responder(500, $msg);
            }
        } else {
            $msg = ["status" => "Email Já existe :("];
            responder(400, $msg);
        }
    } else {
        $msg = ["status" => "Deu ruim"];
        responder(400, $msg);
    }
}