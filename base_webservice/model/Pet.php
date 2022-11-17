<?php

require_once __DIR__ . "/../configs/BancoDados.php";

class Pet
{

    public static function cadastrar($nome, $descricao, $telTutor)
    {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("INSERT INTO pet (nome, descricao, telTutor, dataCadastro) VALUES (?, ?, ?, now())");
            $sql->execute([$nome, $descricao, $telTutor]);
            if ($sql->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function listar()
    {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT p.id, p.nome, p.descricao, p.telTutor, p.dataCadastro FROM pet p ORDER BY p.id");
            $sql->execute();
            return $sql->fetchAll();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function existe($id)
    {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT count(*) FROM pet WHERE id = ?");
            $sql->execute([$id]);
            $quantidade = $sql->fetchColumn();
            if ($quantidade > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function Editar($id, $nome, $descricao, $telTutor)
    {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("UPDATE pet SET nome = ?, descricao = ?, telTutor = ? WHERE id = ?");
            $sql->execute([$nome, $descricao, $telTutor, $id]);
            if ($sql->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function remover($id)
    {
        try {
            $conexao = Conexao::getConexao();
            $assoc = Cuida::listarPets($id);
            foreach ($assoc as $tupla) {
                $sql = $conexao->prepare("DELETE FROM cuida WHERE idCuidador = ? and idPet = ?");
                $sql->execute([$tupla['idCuidador'], $tupla['idPet']]);
            }
            $sql = $conexao->prepare("DELETE FROM pet WHERE id = ?");
            $sql->execute([$id]);
            if ($sql->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }
}