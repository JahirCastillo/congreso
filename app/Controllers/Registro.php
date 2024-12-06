<?php

namespace App\Controllers;
use Requests;
use App\Models\RegistroModel;
use CodeIgniter\Password\Password;
class Registro extends BaseController
{
    protected $registroModel;
    public function __construct()
    {
        $this->registroModel = model(RegistroModel::class);
    }

    public function index()
    {
        return view('registro/loginPonentes');
    }

    public function validaPonente()
    {
        helper(['form', 'validation']);
        if ($this->request->isAJAX()) {
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

                $datos = $this->registroModel->buscaPonente($usuario);
                if ($datos == null) {
                    $arrayRespuesta['esValido'] = false;
                    $arrayRespuesta['mensaje']  = 'La cuenta no se encuentra registrada';
                } else {
                    if (password_verify($password, $datos->contrasena)) {
                        $arrayRespuesta['esValido'] = true;
                        $arrayRespuesta['from']     = 'local';
                        $session                    = session();
                        $this->registraSesionUsuario($datos, $session);
                    } else {
                        $arrayRespuesta['esValido'] = false;
                        $arrayRespuesta['mensaje']  = 'La contraseña no corresponde con el usuario.';
                    }
                }
            }
            return json_encode($arrayRespuesta);
        } else {
            return redirect()->to('registro');
        }
    }

    function iniciarSesion()
    {
        return redirect()->to('ponencias');
    }
    private function registraSesionUsuario($datos, $session)
    {
        $session->set('idPonente', $datos->id_ponente);
        $session->set('nombrePonente', $datos->nombre);
        $session->set('emailPonente', $datos->email);
        $session->set('institucion', $datos->institucion);
        $session->set('pais', $datos->pais);
    }

    function registroPonentes()
    {
        return view('registro/registroPonentes');
    }
    public function destruirSesion()
    {
        session()->destroy();
        return redirect()->to('/registro')->with('message', 'Sesión destruida correctamente.');
    }

    function guardaRegistro()
    {
        helper(['form', 'url']);
        $datosPonente                = array();
        $datosPonente['nombre']      = esc($this->request->getPost('nombre'));
        $datosPonente['email']       = esc($this->request->getPost('correo'));
        $datosPonente['institucion'] = esc($this->request->getPost('institucion'));
        $datosPonente['pais']        = esc($this->request->getPost('pais'));
        $contrasena                  = esc($this->request->getPost('contrasena'));
        $hashedContrasena            = password_hash($contrasena, PASSWORD_BCRYPT);
        $datosPonente['contrasena']  = $hashedContrasena;
        $respuesta                   = $this->registroModel->guardaPonente($datosPonente);
        return json_encode($respuesta);
    }
}
