<?php

namespace App\Controllers;
use App\Models\PonenciasModel;

class Inicio extends BaseController
{
    protected $ponenciasModel;
    public function __construct()
    {
        $this->ponenciasModel = model(PonenciasModel::class);
    }
    public function index()
    {
        if (!session()->has('nombre')) {
            return redirect()->to('');
        }
        $datosVistaInicio['conteosPonencias'] = $this->ponenciasModel->obtenerConteosPonencias();
        return view('inicio', $datosVistaInicio);
    }
}
