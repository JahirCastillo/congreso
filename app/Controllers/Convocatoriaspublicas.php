<?php

namespace App\Controllers;
use Requests;
use App\Models\ConvocatoriasModel;
class ConvocatoriasPublicas extends BaseController
{
    protected $convocatoriasModel;
    public function __construct()
    {
        $this->convocatoriasModel = model(ConvocatoriasModel::class);
    }

    public function index()
    {
        $datosConvocatoria['convocatorias'] = $this->convocatoriasModel->getConvocatoriasDisponibles();
        return view('convocatoriasPublicas/inicioConvocatoriasPublicas', $datosConvocatoria);
    }
}
