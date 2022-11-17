<?php


require_once __DIR__ . "/../configs/BancoDados.php";
class Cuidador
{
    public static function cadastrar($nome, $email)
    {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("INSERT INTO cuidador (nome, email, dataCadastro) VALUES ( ?, ?, now())");
            $sql->execute([$nome, $email]);
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
            $sql = $conexao->prepare("SELECT c.id, c.nome, c.email, c.dataCadastro FROM cuidador c ORDER BY c.id");
            $sql->execute();
            return $sql->fetchAll();
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function existeEmail($email)
    {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT count(*) FROM cuidador WHERE email = ?");
            $sql->execute([$email]);
            $quantidade = $sql->fetchColumn();
            if ($quantidade <= 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function existe($id)
    {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT count(*) FROM cuidador WHERE id = ?");
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

    public static function editar($id, $nome, $email)
    {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("UPDATE cuidador SET nome = ?, email = ? WHERE id = ?");
            $sql->execute([$nome, $email, $id]);
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

    public static function existeEmailAlterar($email, $id)
    {
        try {
            $conexao = Conexao::getConexao();
            $sql = $conexao->prepare("SELECT count(*) FROM cuidador WHERE email = ? and id != ?");
            $sql->execute([$email, $id]);
            $quantidade = $sql->fetchColumn();
            if ($quantidade <= 0) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public static function excluir($id)
    {
        try {
            $conexao = Conexao::getConexao();
            $assoc = Cuida::listarCuidador($id);
            foreach ($assoc as $tupla) {
                $sql = $conexao->prepare("DELETE FROM cuida WHERE idCuidador = ? and idPet = ?");
                $sql->execute([$tupla['idCuidador'], $tupla['idPet']]);
            }
            $sql = $conexao->prepare("DELETE FROM cuidador WHERE id = ?");
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