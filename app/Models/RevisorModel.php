<?php
namespace App\Models;
use CodeIgniter\Model;
class RevisorModel extends Model
{

    function getPonencias($estatus = 'P')
    {
        $tematicaRevisor = session('tematica');
        $sql             = "select po_id_ponencia,id_ponente,po_titulo,tematicas.nombre as tematica,institucion,pais,ponentes.nombre as ponente from ponencias
        join convocatorias_congresos on po_id_convocatoria=id_convocatoria        
        left join ponentes on po_id_ponente=id_ponente
                left join tematicas on po_id_tematica=id_tematica
                where po_id_tematica=? and po_estatus=? and estatus='A'";
        $builder         = $this->db->query($sql, [$tematicaRevisor, $estatus]);
        return $builder->getResultArray();
    }

    function getDetallesPonencia($idPonencia)
    {
        $sql     = "SELECT
                    po_id_ponencia,
                    id_ponente,
                    po_titulo,
                    po_id_tematica,
                    po_id_subtematica,
                    po_fecha_registro,
                    tematicas.nombre AS tematica,
                    subtematicas.nombre AS subtematica,
                    institucion,
                    pais,
                    po_estatus,
                    po_motivorechazo,
                    ponentes.nombre AS ponente 
                    FROM
                    ponencias
                    LEFT JOIN ponentes ON po_id_ponente = id_ponente
                    LEFT JOIN tematicas ON po_id_tematica = id_tematica
                    LEFT JOIN subtematicas ON po_id_subtematica = subtematicas.id_subtematica 
                    WHERE
                    po_id_ponencia = ?";
        $builder = $this->db->query($sql, [$idPonencia]);
        return $builder->getRowArray();
    }

    function rechazarPonencia($idPonencia, $observaciones)
    {
        $idRevisor = session('idUsuario');
        $sql       = "UPDATE ponencias SET po_estatus='R',po_motivorechazo=?,po_fechamovimiento=now(),po_id_revisor=? WHERE po_id_ponencia=?";
        $this->db->query($sql, [$observaciones, $idRevisor, $idPonencia]);
        return true;
    }

    function aprobarPonencia($idPonencia)
    {
        $idRevisor = session('idUsuario');
        $sql       = "UPDATE ponencias SET po_estatus='A',po_fechamovimiento=now(),po_id_revisor=? WHERE po_id_ponencia=?";
        $this->db->query($sql, [$idRevisor, $idPonencia]);
        return true;
    }
}