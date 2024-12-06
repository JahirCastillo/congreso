<?php

namespace App\Controllers;
use Requests;
use App\Models\RevisorModel;
use CodeIgniter\Password\Password;
class Revisor extends BaseController
{
    protected $revisorModel;
    public function __construct()
    {
        if (!session()->has('rol')) {
            redirect()->to('')->send();
            exit;
        }
        $this->revisorModel = model(RevisorModel::class);
    }

    public function index()
    {
        $datosRevisor['ponenciasPendientes'] = $this->revisorModel->getPonencias("P");
        $datosRevisor['ponenciasRechazadas'] = $this->revisorModel->getPonencias("R");
        $datosRevisor['ponenciasAprobadas']  = $this->revisorModel->getPonencias("A");
        return view('revisores/inicioRevisores', $datosRevisor);
    }

    function getDetallesPonencia()
    {
        if ($this->request->isAJAX()) {
            $idPonencia    = esc($this->request->getPost('id'));
            $datosPonencia = $this->revisorModel->getDetallesPonencia($idPonencia);
            $ponenteId     = $datosPonencia['id_ponente'];
            $rutaCarpeta   = WRITEPATH . 'uploads/ponencias/ponente_' . $ponenteId;
            if (!is_dir($rutaCarpeta)) {
                mkdir($rutaCarpeta, 0777, true);
            }
            $archivos      = scandir($rutaCarpeta);
            $ultimoArchivo = '';
            $numeroArchivo = 0;
            foreach ($archivos as $archivo) {
                if (is_file($rutaCarpeta . '/' . $archivo)) {
                    $numeroArchivo = (int) pathinfo($archivo, PATHINFO_FILENAME);
                    if ($numeroArchivo > $ultimoArchivo) {
                        $ultimoArchivo = $numeroArchivo;
                        $ultimoArchivo = $archivo;
                    }
                }
            }
            $datosPonencia['archivo'] = $ultimoArchivo;
            echo view('revisores/detallesRevisionPonencia', $datosPonencia);
        }
    }

    function rechazarPonencia()
    {
        if ($this->request->isAJAX()) {
            $idPonencia    = esc($this->request->getPost('ponencia'));
            $observaciones = esc($this->request->getPost('motivo'));
            $this->revisorModel->rechazarPonencia($idPonencia, $observaciones);
            echo json_encode(['estatus' => 'ok']);
        }
    }

    function aceptarPonencia()
    {
        if ($this->request->isAJAX()) {
            $idPonencia = esc($this->request->getPost('ponencia'));
            $this->revisorModel->aprobarPonencia($idPonencia);
            echo json_encode(['estatus' => 'ok']);
        }
    }
    public function descargaArchivo($idPonente, $nombreArchivo)
    {
        $rutaArchivo = 'ponencias/ponente_' . $idPonente . '/' . $nombreArchivo;
        return $this->response->download(WRITEPATH . 'uploads/' . $rutaArchivo, null);
    }

}
