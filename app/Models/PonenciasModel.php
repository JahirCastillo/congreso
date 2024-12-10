<?php
namespace App\Models;
use CodeIgniter\Model;
class PonenciasModel extends Model
{
    public function getPonencias()
    {
        $idPonente = session()->get('idPonente');
        $builder   = $this->db->table('ponencias');
        $builder->select('po_id_ponencia,po_titulo,po_hora_inicio,po_estatus,nombre as tematica,po_motivorechazo');
        $builder->join('tematicas', 'ponencias.po_id_tematica = tematicas.id_tematica', 'left');
        $builder->where('po_id_ponente', $idPonente);
        $ponencias = $builder->get()->getResultArray();
        return $ponencias;
    }

    public function getPonencia($idPonencia)
    {
        $builder = $this->db->table('ponencias');
        $builder->select('*');
        $builder->where('po_id_ponencia', $idPonencia);
        $ponencia = $builder->get()->getRowArray();
        return $ponencia;
    }
    public function getTematicas()
    {
        return $this->db->table('tematicas')->get()->getResultArray();
    }

    public function getSubtematicas($idTematica)
    {
        return $this->db->table('subtematicas')
            ->where('id_tematica', $idTematica)
            ->get()
            ->getResultArray();
    }

    public function agregaPonencia($data)
    {
        $this->db->table('ponencias')->insert($data);
        $idPonencia = $this->db->insertID();
        return $idPonencia;
    }

    function getAutores($idPonencia)
    {
        return $this->db->table('autores')
            ->where('aut_id_ponencia', $idPonencia)
            ->get()
            ->getResultArray();
    }
    function insertaAutores($datos, $ponenciaId)
    {
        foreach ($datos as $dato) {
            $dato['aut_id_ponencia'] = $ponenciaId;
            $this->db->table('autores')->insert($dato);
        }
        return true;
    }

    function actualizaPonencia($idPonencia, $datos)
    {
        $this->db->table('ponencias')
            ->where('po_id_ponencia', $idPonencia)
            ->update($datos);
        return true;
    }

    function obtenerConteosPonencias()
    {
        $sql = "SELECT
         COALESCE(COUNT(po_id_ponencia), 0) AS total,
        COALESCE(SUM(po_estatus = 'P'), 0) AS ponencias_pendientes,
        COALESCE(ROUND(SUM(po_estatus = 'P') * 100.0 / NULLIF(COUNT(po_id_ponencia), 0), 2), 0) AS porcentaje_pendientes,
        COALESCE(SUM(po_estatus = 'A'), 0) AS ponencias_aceptadas,
        COALESCE(ROUND(SUM(po_estatus = 'A') * 100.0 / NULLIF(COUNT(po_id_ponencia), 0), 2), 0) AS porcentaje_aceptadas,
        COALESCE(SUM(po_estatus = 'R'), 0) AS ponencias_rechazadas,
        COALESCE(ROUND(SUM(po_estatus = 'R') * 100.0 / NULLIF(COUNT(po_id_ponencia), 0), 2), 0) AS porcentaje_rechazadas
        FROM
        ponencias;";
        return $this->db->query($sql)->getRowArray();
    }
}