<?php
namespace App\Models;
use CodeIgniter\Model;
class ItinerarioModel extends Model
{
    public function ponencias()
    {
        $sql   = "SELECT po_id_ponencia, po_hora_inicio, po_hora_fin,
         concat(po_titulo, ' - ', (select nombre from ponentes where po_id_ponente = id_ponente)  )  as po_titulo,
         (select nombre from tematicas where po_id_tematica = id_tematica) as tematica
          FROM ponencias
          WHERE po_id_convocatoria = (SELECT id_convocatoria FROM convocatorias_congresos where estatus = 'A' limit 1)
           and po_estatus = 'A'";
        $db    = db_connect();
        $query = $db->query($sql);
        return $query->getResultArray();
    }


    public function congreso()
    {
        $sql   = "SELECT * FROM convocatorias_congresos where estatus = 'A' limit 1";
        $db    = db_connect();
        $query = $db->query($sql);
        return $query->getRowArray();
    }

    public function actualizarHora($ponenciaId, $fechaInicio, $fechaFin)
    {
        $sql = "UPDATE ponencias SET po_hora_inicio = ?, po_hora_fin = ? WHERE po_id_ponencia = ?";
        $db  = db_connect();
        $db->query($sql, [$fechaInicio, $fechaFin, $ponenciaId]);
    }

}