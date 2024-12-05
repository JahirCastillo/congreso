<?php
namespace App\Models;
use CodeIgniter\Model;
class AccesoModel extends Model
{
    public function buscaUsuario($usuario)
    {
        $sql   = "SELECT * FROM usuarios WHERE usu_login = ? LIMIT 1";
        $query = $this->db->query($sql, [$usuario]);
        return $query->getRow();
    }
}