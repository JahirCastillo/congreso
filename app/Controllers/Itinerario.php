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
        if (session('rol') != 1) {
            redirect()->to('revisores')->send();
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
        //solo si es ajax
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