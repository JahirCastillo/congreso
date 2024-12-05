<?php

namespace App\Controllers;
use App\Models\UsuariosModel;

class Usuarios extends BaseController
{
    protected $usuariosModel;
    public function __construct()
    {
        $this->usuariosModel = model(UsuariosModel::class);
    }
    public function index()
    {
        if (!session()->has('usuarioLogueado')) {
            return redirect()->to('');
        }
        return view('usuarios/usuariosInicio');
    }

    function getUsuarios()
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
        $result = $this->usuariosModel->getDatosUsuarios($limit, $offset, $search, $orderColumnName, $orderDir);

        // Retornar datos en formato JSON
        return $this->response->setJSON([
            'draw'            => $this->request->getPost('draw'),
            'recordsTotal'    => $result['total'],
            'recordsFiltered' => $result['total'],
            'data'            => $result['data'],
        ]);
    }

    function getDetallesUsuario()
    {
        if ($this->request->isAJAX()) {
            $id                  = $this->request->getPost('id');
            $arrayDatos['roles'] = $this->usuariosModel->getRoles();
            if ($id != 0) {
                $arrayDatos['datosUsuario'] = $this->usuariosModel->getUsuario($id);
                echo view('usuarios/detallesUsuario', $arrayDatos);
            } else {
                $arrayDatos['datosUsuario'] = [
                    'id'            => '0',
                    'login'         => '',
                    'nombre'        => '',
                    'apaterno'      => '',
                    'amaterno'      => '',
                    'correo'        => '',
                    'rol'           => '',
                    'estatusCuenta' => '',
                ];
                echo view('usuarios/detallesUsuario', $arrayDatos);
            }
        } else {
            return $this->response->setStatusCode(403, 'Forbidden');
        }
    }

    function actualizaUsuario()
    {
        $id           = esc($this->request->getPost('id'));
        $datosUsuario = [
            'usu_login'         => esc($this->request->getPost('login')),
            'usu_nombre'        => esc($this->request->getPost('nombre')),
            'usu_apaterno'      => esc($this->request->getPost('apaterno')),
            'usu_amaterno'      => esc($this->request->getPost('amaterno')),
            'usu_correo'        => esc($this->request->getPost('correo')),
            'usu_rol'           => esc($this->request->getPost('rol')),
            'usu_estatuscuenta' => esc($this->request->getPost('estatusCuenta'))
        ];
        if ($id == 0) {
            $this->usuariosModel->agregaUsuario($datosUsuario);
        } else {
            $this->usuariosModel->actualizaUsuario($id, $datosUsuario);
        }
        return $this->response->setJSON(['estatus' => 'ok']);
    }

    function getPermisosRol()
    {
        $idRol = esc($this->request->getPost('rol'));
        if ($this->request->isAJAX()) {
            $permisos = $this->usuariosModel->getPermisosRol($idRol);
            return $this->response->setJSON($permisos);
        } else {
            return $this->response->setStatusCode(403, 'Forbidden');
        }
    }
}
