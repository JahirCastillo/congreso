<?php
namespace App\Models;
use CodeIgniter\Model;
class UsuariosModel extends Model
{
    public function getDatosUsuarios($limit, $offset, $search = '', $orderColumn = 'usu_id', $orderDir = 'asc')
    {
        $filtros          = session()->get('filtros');
        $filtros          = json_decode($filtros, true);
        $columnasBusqueda = ['usu_login', 'usu_nombre', 'usu_apaterno', 'usu_amaterno', 'rol_nombre', 'usu_correo'];
        $table            = 'usuarios';
        $builder          = $this->db->table($table);
        $builder->select("usu_id as id,usu_login as login, CONCAT(COALESCE(usu_nombre,''),' ',COALESCE(usu_apaterno,''),' ',COALESCE(usu_amaterno,'')) as nombre, (select nombre from tematicas where usu_tematica=id_tematica limit 1) as tematica, usu_correo correo, case usu_estatuscuenta when 1 then 'Activa' when 0 then 'Inactiva' end as estatusCuenta");
        $builder->join('roles', 'rol_id = usu_rol', 'left');
        if (!empty($search)) {
            $builder->groupStart();
            foreach ($columnasBusqueda as $column) {
                $builder->orLike($column, $search);
            }
            $builder->groupEnd();
        }
        $totalRecords = $builder->countAllResults(false);
        $builder->orderBy($orderColumn, $orderDir);
        $builder->limit($limit, $offset);
        $data = $builder->get()->getResultArray();
        return [
            'total' => $totalRecords,
            'data'  => $data,
        ];
    }

    function getUsuario($id)
    {
        $builder = $this->db->table('usuarios');
        $builder->select("usu_id as id,usu_login as login,usu_nombre as nombre,usu_password as password,usu_apaterno as apaterno,usu_amaterno as amaterno,usu_correo as correo,usu_tematica,usu_estatuscuenta as estatusCuenta");
        $builder->where('usu_id', $id);
        $usuario = $builder->get()->getRowArray();
        return $usuario;
    }
    function actualizaUsuario($id, $datosUsuario)
    {
        $respuesta = false;
        $builder   = $this->db->table('usuarios');
        $builder->where('usu_id', $id);
        $builder->update($datosUsuario);
        if ($this->db->affectedRows() > 0) {
            $respuesta = true;
        }
        return $respuesta;
    }

    function agregaUsuario($datosUsuario)
    {
        $respuesta = false;
        $builder   = $this->db->table('usuarios');
        $builder->insert($datosUsuario);
        if ($this->db->affectedRows() > 0) {
            $respuesta = true;
        }
        return $respuesta;
    }

    function getTematicas()
    {
        $builder = $this->db->table('tematicas');
        $builder->select('id_tematica as id,nombre');
        $tematicas = $builder->get()->getResultArray();
        return $tematicas;
    }

    function eliminaUsuario($id)
    {
        $respuesta = false;
        $builder   = $this->db->table('usuarios');
        $builder->where('usu_id', $id);
        $builder->delete();
        if ($this->db->affectedRows() > 0) {
            $respuesta = true;
        }
        return $respuesta;
    }
}