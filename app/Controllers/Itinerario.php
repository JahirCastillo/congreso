<?php

namespace App\Controllers;

class Itinerario extends BaseController
{
    public function __construct()
    {
        if (!session()->has('nombre')) {
            redirect()->to('')->send();
            exit;
        }
        if (session('rol') == 2) {
            redirect()->to('revisor')->send();
            exit;
        } elseif (session('rol') != 1) {
            redirect()->to('')->send();
            exit;
        }
    }
    function index()
    {
        $model             = new \App\Models\ItinerarioModel();
        $data['ponencias'] = $model->ponencias();
        $data['congreso']  = $model->congreso();
        return view('itinerario/itinerarioInicio', $data);
    }

    function actualizarHora()
    {
        if ($this->request->isAJAX()) {
            $model = new \App\Models\ItinerarioModel();
            $model->actualizarHora(
                $this->request->getPost('ponenciaId'),
                $this->request->getPost('fechaInicioMysql'),
                $this->request->getPost('fechaFinMysql')
            );
            echo json_encode(['status' => 'ok']);
        }
    }
}