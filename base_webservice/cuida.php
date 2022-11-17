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
    if (parametrosValidos($_POST, ["idCuidador", "idPet"])) {
        $idCuidador = $_POST["idCuidador"];
        $idPet = $_POST["idPet"];

        if (Cuidador::existe($idCuidador)) {
            if (Pet::existe($idPet)) {
                if (!Cuida::existeCuida($idPet, $idCuidador)) {
                    if (Cuida::cadastrar($idCuidador, $idPet)) {
                        $msg = ["status" => "Cadastro de relação com sucesso!"];
                        responder(201, $msg);
                    } else {
                        $msg = ["status" => "Cadastro de relação deu algum erro :("];
                        responder(500, $msg);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["mensagem" => "Cuidador já cuida desse pet"]);
                }
            } else {
                $msg = ["status" => "Id de Pet não cadastrado :("];
                responder(500, $msg);
            }
        } else {
            $msg = ["status" => "Id de Cuidador não cadastrado :("];
            responder(500, $msg);
        }
    } else {
        $msg = ["status" => "Parâmetros inválidos :("];
        responder(400, $msg);
    }
}


if (isMetodo("GET")) {
    $resultado = Cuida::listar();
    responder(200, $resultado);
}

if (isMetodo("DELETE")) {
    if (parametrosValidos($_DELETE, ["idCuidador", "idPet"])) {
        $idPet = $_DELETE["idPet"];
        $idCuidador = $_DELETE["idCuidador"];

        if (Cuidador::existe($idCuidador)) {
            if (Pet::existe($idPet)) {
                if (Cuida::existeCuida($idPet, $idCuidador)) {
                    if (Cuida::remover($idPet, $idCuidador)) {
                        $msg = ["status" => "Relação Cuidador/Pet deletada!!"];
                        responder(200, $msg);
                    } else {
                        $msg = ["status" => "Não foi possível deletar a relação Cuidador/Pet :("];
                        responder(400, $msg);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["mensagem" => "Cuidador não cuida desse pet"]);
                }
            } else {
                $msg = ["status" => "Id de Pet não cadastrado :("];
                responder(500, $msg);
            }
        } else {
            $msg = ["status" => "Id de Cuidador não cadastrado :("];
            responder(500, $msg);
        }
    } else {
        $msg = ["status" => "Parâmetros inválidos :("];
        responder(400, $msg);
    }
}


if (isMetodo("PUT")) {
    if (parametrosValidos($_PUT, ["idPetNovo", "idCuidadorNovo", "idPet", "idCuidador"])) {
        $idPet = $_PUT["idPetNovo"];
        $idCuidador = $_PUT["idCuidadorNovo"];
        $idPetAntigo = $_PUT["idPet"];
        $idCuidadorAntigo = $_PUT["idCuidador"];

        if (Cuidador::existe($idCuidadorAntigo)) {
            if (Pet::existe($idPetAntigo)) {
                if (Cuidador::existe($idCuidador)) {
                    if (Pet::existe($idPet)) {
                        if (Cuida::existeCuida($idPetAntigo, $idCuidadorAntigo)) {
                            if (!Cuida::existeCuida($idPet, $idCuidador)) {
                                if (Cuida::alterar($idPetAntigo, $idCuidadorAntigo, $idPet, $idCuidador)) {
                                    $msg = ["status" => "Relação Cuidador/Pet atualizada!!"];
                                    responder(200, $msg);
                                } else {
                                    $msg = ["status" => "Não foi possível atualizar a relação Cuidador/Pet :("];
                                    responder(400, $msg);
                                }
                            } else {
                                http_response_code(400);
                                echo json_encode(["mensagem" => "No Novo update Cuidador já cuida desse pet"]);
                            }
                        } else {
                            http_response_code(400);
                            echo json_encode(["mensagem" => "Cuidador não cuida desse pet"]);
                        }
                    } else {
                        $msg = ["status" => "Id de Pet não cadastrado :("];
                        responder(500, $msg);
                    }
                } else {
                    $msg = ["status" => "Id de Cuidador não cadastrado :("];
                    responder(500, $msg);
                }
            } else {
                $msg = ["status" => "Id do Pet do relacionamento a ser atualizado não cadastrado :("];
                responder(500, $msg);
            }
        } else {
            $msg = ["status" => "Id do Cuidador do relacionamento a ser atualizado não cadastrado :("];
            responder(500, $msg);
        }
    } else {
        $msg = ["status" => "Parâmetros inválidos :("];
        responder(400, $msg);
    }
}