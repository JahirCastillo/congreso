<?php
namespace App\Models;
use CodeIgniter\Model;
class PonenciasModel extends Model
{
    public function getPonencias()
    {
        $idPonente = session()->get('idPonente');
        $builder   = $this->db->table('ponencias');
        $builder->select('po_id_ponencia,po_titulo,po_hora_inicio,po_estatus,nombre as tematica');
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
        return $this->db->insertID();
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

}