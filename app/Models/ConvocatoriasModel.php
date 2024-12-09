<?php
namespace App\Models;
use CodeIgniter\Model;
class ConvocatoriasModel extends Model
{

    public function getDatosConvocatorias($limit, $offset, $search = '', $orderColumn = 'id_convocatoria', $orderDir = 'asc')
    {
        $columnas = ['id_convocatoria', 'nombre', 'fecha_inicio', 'fecha_fin', 'ubicacion', 'estatus'];
        $table    = 'convocatorias_congresos';
        $builder  = $this->db->table($table);
        $builder->select(implode(',', $columnas));
        if (!empty($search)) {
            $builder->groupStart();
            foreach ($columnas as $column) {
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

    function getConvocatoria($id)
    {
        $builder = $this->db->table('convocatorias_congresos');
        $builder->select('id_convocatoria as id,nombre,fecha_inicio,fecha_fin,fecha_inicio_recepcion_documentos,fecha_limite_documentos,fecha_inicio_dictaminacion,fecha_fin_dictaminacion,descripcion,ubicacion,estatus');
        $builder->where('id_convocatoria', $id);
        $convocatoria = $builder->get()->getRowArray();
        return $convocatoria;
    }
    function insertaConvocatoria($datos)
    {
        // Start transaction
        $this->db->transStart();
        $builder = $this->db->table('convocatorias_congresos');
        $builder->where('estatus', 'A');
        $builder->update(['estatus' => 'I']);
        $builder->insert($datos);
        $this->db->transComplete();

        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            return false;
        } else {
            $this->db->transCommit();
            return true;
        }
    }

    function actualizaConvocatoria($id, $datos)
    {
        $builder = $this->db->table('convocatorias_congresos');
        if (isset($datos['estatus']) && $datos['estatus'] === 'A') {
            $builder->where('estatus', 'A');
            $builder->update(['estatus' => 'I']);
        }

        $builder->where('id_convocatoria', $id);
        $builder->update($datos);
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            return false;
        } else {
            $this->db->transCommit();
            return true;
        }
    }

    function eliminaConvocatoria($id)
    {
        $builder = $this->db->table('convocatorias_congresos');
        $builder->where('id_convocatoria', $id);
        $builder->delete();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            return false;
        } else {
            $this->db->transCommit();
            return true;
        }
    }

    function getConvocatoriasDisponibles()
    {
        $builder = $this->db->table('convocatorias_congresos');
        $builder->select('id_convocatoria as id,nombre,fecha_inicio,fecha_fin,fecha_inicio_recepcion_documentos,fecha_limite_documentos,descripcion,ubicacion,estatus');
        $builder->where('estatus', 'A');
        $convocatorias = $builder->get()->getResultArray();
        return $convocatorias;
    }
}