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

        // Seleccionar columnas para la consulta
        $builder->select("usu_id as id,usu_login as login, CONCAT(COALESCE(usu_nombre,''),' ',COALESCE(usu_apaterno,''),' ',COALESCE(usu_amaterno,'')) as nombre, rol_nombre as rolNombre, usu_correo correo, case usu_estatuscuenta when 1 then 'Activa' when 0 then 'Inactiva' end as estatusCuenta");
        $builder->join('roles', 'rol_id = usu_rol', 'left');


        // Aplicar filtro de búsqueda en columnas específicas
        if (!empty($search)) {
            $builder->groupStart();
            foreach ($columnasBusqueda as $column) {
                $builder->orLike($column, $search);
            }
            $builder->groupEnd();
        }

        // Contar registros totales sin filtrar
        $totalRecords = $builder->countAllResults(false);

        // Agregar orden y límite
        $builder->orderBy($orderColumn, $orderDir);
        $builder->limit($limit, $offset);
        // $sql = $builder->getCompiledSelect();
        // echo $sql;
        // die();
        // Obtener datos
        $data = $builder->get()->getResultArray();

        // die($this->db->getLastQuery());
        return [
            'total' => $totalRecords,
            'data'  => $data,
        ];
    }

    function getUsuario($id)
    {
        $builder = $this->db->table('scp_usuarios');
        $builder->select("usu_id as id,usu_login as login,usu_nombre as nombre,usu_apaterno as apaterno,usu_amaterno as amaterno,usu_correo as correo,usu_rol as rol,usu_estatuscuenta as estatusCuenta");
        $builder->where('usu_id', $id);
        $usuario = $builder->get()->getRowArray();
        return $usuario;
    }

    function getRoles()
    {
        $builder = $this->db->table('scp_roles');
        $builder->select('rol_id as id,rol_nombre as nombre');
        $roles = $builder->get()->getResultArray();
        return $roles;
    }

    function actualizaUsuario($id, $datosUsuario)
    {
        $respuesta = false;
        $builder   = $this->db->table('scp_usuarios');
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
        $builder   = $this->db->table('scp_usuarios');
        $builder->insert($datosUsuario);
        if ($this->db->affectedRows() > 0) {
            $respuesta = true;
        }
        return $respuesta;
    }

    function getPermisosRol($idRol)
    {
        $sql      = "SELECT 
                    m.mod_id, 
                    m.mod_nombre, 
                    p.per_id, 
                    p.per_nombre, 
                    p.per_descripcion,
                    IF(pr.apr_rol IS NOT NULL, 1, 0) AS tiene_permiso
                FROM 
                    scp_modulos m
                LEFT JOIN 
                    scp_permisos p ON p.per_modulo = m.mod_id
                LEFT JOIN 
                    scp_permisos_rol pr ON pr.apr_permiso = p.per_id AND pr.apr_rol = ?
                    WHERE mod_activo = 1
                ORDER BY 
                    m.mod_orden, p.per_nombre;
                ";
        $query    = $this->db->query($sql, [$idRol]);
        $permisos = $query->getResultArray();
        $modulos = [];

        foreach ($permisos as $permiso) {
            $mod_id = $permiso['mod_id'];

            if (!isset($modulos[$mod_id])) {
                $modulos[$mod_id] = [
                    'id'       => $mod_id,
                    'nombre'   => $permiso['mod_nombre'],
                    'permisos' => []
                ];
            }

            $modulos[$mod_id]['permisos'][] = [
                'id'            => $permiso['per_id'],
                'nombre'        => $permiso['per_nombre'],
                'descripcion'   => $permiso['per_descripcion'],
                'tiene_permiso' => $permiso['tiene_permiso']
            ];
        }

        return array_values($modulos);
    }

}