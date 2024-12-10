<?php
namespace App\Models;
use CodeIgniter\Model;
class RegistroModel extends Model
{
    public function buscaPonente($usuario)
    {
        $sql   = "SELECT * FROM ponentes WHERE email = ? LIMIT 1";
        $query = $this->db->query($sql, [$usuario]);
        return $query->getRow();
    }
    function guardaPonente($datosPonente)
    {
        $respuesta     = array();
        $existePonente = $this->buscaPonente($datosPonente['email']);
        if ($existePonente) {
            $respuesta['esValido'] = false;
            $respuesta['mensaje']  = 'El correo electrónico ya se encuentra registrado.';
            return $respuesta;
        }
        $builder = $this->db->table('ponentes');
        $builder->insert($datosPonente);
        $idPonente = $this->db->insertID();
        if ($this->db->transStatus() === FALSE) {
            $this->db->transRollback();
            $respuesta['esValido'] = false;
            $respuesta['mensaje']  = 'Error al guardar los datos.';
        } else {
            $this->db->transCommit();
            session()->set('idPonente', $idPonente);
            session()->set('nombrePonente', $datosPonente['nombre']);
            session()->set('emailPonente', $datosPonente['email']);
            session()->set('institucion', $datosPonente['institucion']);
            session()->set('pais', $datosPonente['pais']);
            $respuesta['esValido'] = true;
            $respuesta['mensaje']  = 'Los datos se guardaron correctamente.';
        }
        return $respuesta;
    }
}