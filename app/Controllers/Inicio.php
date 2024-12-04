<?php

namespace App\Controllers;
use App\Models\AccesoModel;

class Inicio extends BaseController
{
    protected $accesoModel;
    public function __construct()
    {
        $this->accesoModel = model(AccesoModel::class);
    }
    public function index()
    {
        if (!session()->has('nombre')) {
            return redirect()->to('');
        }
        return view('inicio');
    }
}
