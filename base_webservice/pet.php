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
    if (parametrosValidos($_POST, ["nomePet", "descricao", "telTutor"])) {
        $nomePet = $_POST["nomePet"];
        $descricao = $_POST["descricao"];
        $telTutor = $_POST["telTutor"];

        if (Pet::cadastrar($nomePet, $descricao, $telTutor)) {
            $msg = ["status" => "Cadastro de PET com sucesso!"];
            responder(201, $msg);
        } else {
            $msg = ["status" => "Cadastro de PET deu algum erro :("];
            responder(500, $msg);
        }
    }
} else {
    echo "Ruim";
}


if (isMetodo("GET")) {
    $resultado = Pet::listar();
    responder(200, $resultado);
}

if (isMetodo("DELETE")) {
    if (parametrosValidos($_DELETE, ["idPet"])) {
        $id = $_DELETE["idPet"];

        if (PET::remover($id)) {
            $msg = ["status" => "PET deletado!!"];
            responder(200, $msg);
        } else {
            $msg = ["status" => "Não foi possível deletar o PET :("];
            responder(400, $msg);
        }
    } else {
        $msg = ["status" => "ID de PET não encontrado :("];
        responder(400, $msg);
    }
}


if (isMetodo("PUT")) {
    if (parametrosValidos($_PUT, ["idPet", "nomePet", "descricao", "telTutor"])) {

        $idPet = $_PUT["idPet"];
        $nomePet = $_PUT["nomePet"];
        $descricao = $_PUT["descricao"];
        $telTutor = $_PUT["telTutor"];

        if (Pet::existe($idPet)) {
            if (Pet::Editar($idPet, $nomePet, $descricao, $telTutor)) {
                $msg = ["status" => "PET atualizado com sucesso!"];
                responder(201, $msg);
            } else {
                $msg = ["status" => "Atualização de PET deu algum erro :("];
                responder(500, $msg);
            }
        } else {
            $msg = ["status" => "PET não cadastrado :("];
            responder(500, $msg);
        }
    }
}