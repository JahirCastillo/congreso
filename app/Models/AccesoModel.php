<?php
namespace App\Models;
use CodeIgniter\Model;
class AccesoModel extends Model
{
    public function buscaUsuario($usuario)
    {
        $sql   = "SELECT * FROM scp_usuarios JOIN scp_roles ON usu_rol=rol_id WHERE usu_login = ?";
        $query = $this->db->query($sql, [$usuario]);
        return $query->getRow();
    }

    function guardaLog($arrayDatosInsert)
    {
        $this->db->table('scp_logaccesos')->insert($arrayDatosInsert);
    }

    function obtieneModulos()
    {
        $idRol = session('rolid');
        if ($idRol) {
            $sql   = "SELECT * FROM scp_modulos
                JOIN scp_permisos ON mod_id = per_modulo
                JOIN scp_permisos_rol ON apr_permiso = per_id 
                WHERE
                mod_activo = 1 
                AND apr_rol = ? 
                GROUP BY
                mod_id
                ORDER BY mod_orden";
            $query = $this->db->query($sql, [$idRol]);
            return $query->getResultArray();
        }
    }
}