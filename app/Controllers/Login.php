<?php

namespace App\Controllers;
use Requests;
use App\Models\AccesoModel;
use CodeIgniter\HTTP\UserAgent;
class Login extends BaseController
{
    protected $accesoModel;
    protected $userAgent;
    public function __construct()
    {
        $this->accesoModel = model(AccesoModel::class);
        $this->userAgent   = \Config\Services::request()->getUserAgent();
    }

    public function index(): string
    {
        return view('login/login');
    }

    public function validaUsuario(): string
    {
        helper(['form', 'validation']);
        $rules = [
            'usuario'  => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            $errors                    = $this->validator->getErrors();
            $arrayRespuesta['IsValid'] = false;
            $arrayRespuesta['mensaje'] = implode(", ", $errors);
        } else {
            $usuario        = trim(strtolower(esc($this->request->getPost('usuario'))));
            $password       = esc($this->request->getPost('password'));
            $arrayRespuesta = array();

            $datos = $this->accesoModel->buscaUsuario($usuario);
            if ($datos == null) {
                $arrayRespuesta['esValido'] = false;
                $arrayRespuesta['mensaje']  = 'La cuenta no se encuentra registrada';
            } else {
                if (password_verify($password, $datos->usu_password)) {
                    if ($datos->usu_estatuscuenta == 0) {
                        $arrayRespuesta['esValido'] = false;
                        $arrayRespuesta['mensaje']  = 'Cuenta deshabilitada';
                    } else {
                        $arrayRespuesta['esValido'] = true;
                        $arrayRespuesta['from']     = 'local';
                    }
                } else {
                    $arrayRespuesta['esValido'] = false;
                    $arrayRespuesta['mensaje']  = 'La contraseña no corresponde con el usuario.';
                }
            }
        }
        return json_encode($arrayRespuesta);
    }

    function ponEnSesion()
    {
        $usuario = trim(strtolower(esc($this->request->getPost('usuario'))));
        $datos   = $this->accesoModel->buscaUsuario($usuario);
        $session = session();
        if ($datos->usu_id) {
            $this->registraSesionUsuario($datos, $session);
            $session->set('nombre', $datos->usu_nombre);
            if ($datos->usu_rol == 1) {
                return redirect()->to('inicio');
            } else {
                return redirect()->to('revisor');
            }
        }
    }
    private function registraSesionUsuario($datos, $session)
    {
        $session->set('usuarioLogueado', true);
        $session->set('idUsuario', $datos->usu_id);
        $session->set('login', $datos->usu_login);
        $session->set('foto', $datos->usu_imagen);
        $session->set('rol', $datos->usu_rol);
        $session->set('tematica', $datos->usu_tematica);
    }

    public function destruirSesion()
    {
        session()->destroy();
        return redirect()->to('/')->with('message', 'Sesión destruida correctamente.');
    }
}
