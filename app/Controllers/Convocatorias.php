<?php

namespace App\Controllers;
use Requests;
use App\Models\ConvocatoriasModel;
class Convocatorias extends BaseController
{
    protected $convocatoriasModel;
    public function __construct()
    {
        if (!session()->has('nombre')) {
            redirect()->to('')->send();
            exit;
        }
        $this->convocatoriasModel = model(ConvocatoriasModel::class);
    }

    public function index()
    {

        return view('convocatorias/inicioConvocatorias');
    }

    function getConvocatorias()
    {

        // Recoger parámetros de DataTables
        $limit            = $this->request->getPost('length');
        $offset           = $this->request->getPost('start');
        $search           = $this->request->getPost('search')['value'];
        $orderColumnIndex = $this->request->getPost('order')[0]['column'] ?? 0;
        $orderDir         = $this->request->getPost('order')[0]['dir'] ?? 'asc';

        // Definir las columnas para la consulta
        $orderColumnName = $orderColumnIndex + 1;

        // Obtener datos pasando el nombre de la tabla y columnas
        $result = $this->convocatoriasModel->getDatosConvocatorias($limit, $offset, $search, $orderColumnName, $orderDir);

        // Retornar datos en formato JSON
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['total'],
            'recordsFiltered' => $result['total'],
            'data'            => $result['data'],
        ]);
    }

    function getDetallesConvocatoria()
    {
        if ($this->request->isAJAX()) {
            $id = esc($this->request->getPost('id'));
            if ($id == 0) {
                $arrayDatos['detallesConvocatoria'] = [
                    'id' => '0'
                ];
            } else {
                $arrayDatos['detallesConvocatoria'] = $this->convocatoriasModel->getConvocatoria($id);
            }
            echo view('convocatorias/detallesConvocatoria', $arrayDatos);
        } else {
            echo 'No se puede acceder a este recurso';
        }
    }

    function guardaCambiosConvocatoria()
    {
        if ($this->request->isAJAX()) {
            helper(['form', 'validation']);
            $reglas = [
                'nombre'                  => 'required',
                'fecha_inicio'            => 'required',
                'fecha_limite_documentos' => 'required',
                'ubicacion'               => 'required',
                'estatus'                 => 'required'
            ];

            if (!$this->validate($reglas)) {
                return $this->response->setJSON([
                    'estatus'          => "no",
                    'formularioValido' => "no",
                    'errores'          => $this->validator->getErrors()
                ]);
            }

            $id    = esc($this->request->getPost('id'));
            $datos = [
                'nombre'                  => esc($this->request->getPost('nombre')),
                'fecha_inicio'            => esc($this->request->getPost('fecha_inicio')),
                'fecha_limite_documentos' => esc($this->request->getPost('fecha_limite_documentos')),
                'descripcion'             => esc($this->request->getPost('descripcion')),
                'ubicacion'               => esc($this->request->getPost('ubicacion')),
                'estatus'                 => esc($this->request->getPost('estatus'))
            ];

            $resultado = $id == 0
                ? $this->convocatoriasModel->insertaConvocatoria($datos)
                : $this->convocatoriasModel->actualizaConvocatoria($id, $datos);

            return $this->response->setJSON([
                'estatus' => $resultado ? "ok" : "no",
                'mensaje' => $resultado ? 'Cambios guardados' : ''
            ]);
        } else {
            return $this->response->setJSON(['estatus' => "no", 'mensaje' => 'No se puede acceder a este recurso']);
        }
    }
}
