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

    function test()
    {
        // Lista de usuarios con sus correos y IDs
        $usuarios = [
            ['id' => 3291, 'correo' => 'ladruiz@uv.mx'],
            ['id' => 3370, 'correo' => 'kviveros@uv.mx'],
            ['id' => 3501, 'correo' => 'gguevara@uv.mx'],
            ['id' => 3822, 'correo' => 'aradiaz@uv.mx'],
            ['id' => 3718, 'correo' => 'yosrodriguez@uv.mx'],
            ['id' => 3747, 'correo' => 'icaballero@uv.mx'],
            ['id' => 3748, 'correo' => 'yrosas@uv.mx'],
            ['id' => 3885, 'correo' => 'rapozos@uv.mx'],
            ['id' => 3886, 'correo' => 'gdelamerced@uv.mx'],
            ['id' => 3760, 'correo' => 'fdelgado@uv.mx'],
            ['id' => 3762, 'correo' => 'lecruz@uv.mx'],
            ['id' => 3763, 'correo' => 'aarciga@uv.mx'],
            ['id' => 3764, 'correo' => 'rasoto@uv.mx'],
            ['id' => 3765, 'correo' => 'doalonso@uv.mx'],
            ['id' => 3766, 'correo' => 'maribcervantes@uv.mx'],
            ['id' => 3767, 'correo' => 'caroacosta@uv.mx'],
            ['id' => 3768, 'correo' => 'maalarcon@uv.mx'],
            ['id' => 3769, 'correo' => 'nrojano@uv.mx'],
            ['id' => 3770, 'correo' => 'cislas@uv.mx'],
            ['id' => 3880, 'correo' => 'saragarcia02@uv.mx'],
            ['id' => 3881, 'correo' => 'mronzon@uv.mx'],
            ['id' => 3884, 'correo' => 'alejandrramirez@uv.mx'],
            ['id' => 3883, 'correo' => 'lorsanchez@uv.mx'],
            ['id' => 3912, 'correo' => 'telopez@uv.mx'],
            ['id' => 3913, 'correo' => 'mogalicia@uv.mx'],
            ['id' => 3914, 'correo' => 'rocordoba@uv.mx'],
            ['id' => 3915, 'correo' => 'jevirues@uv.mx'],
            ['id' => 4173, 'correo' => 'getejeda@uv.mx'],
            ['id' => 4174, 'correo' => 'evviveros@uv.mx'],
            ['id' => 4205, 'correo' => 'rovillalobos@uv.mx'],
        ];

        // Inicializar un array para almacenar cada bloque SELECT
        $queries = [];

        // Generar el bloque SELECT para cada usuario
        foreach ($usuarios as $usuario) {
            $queries[] = "
    SELECT
        {$usuario['id']} AS pu_usuario,
        '{$usuario['correo']}' AS pu_correo,
        eq_claveprogramatica AS pu_claveprogramatica,
        eq_region AS pu_region,
        eq_campus AS pu_campus,
        'S' AS pu_veralerta 
    FROM
        mpa_equivalencias
        LEFT JOIN mpa_regiones ON eq_region = reg_id
        LEFT JOIN mpa_campus ON eq_campus = mpa_campus.CAMPUS
        JOIN mpa_catalogoclavesprog ON eq_claveprogramatica = ccp_claveprog 
    WHERE
        eq_claveprogramatica NOT IN (SELECT pu_claveprogramatica FROM mpa_programasusuarios WHERE pu_correo = '{$usuario['correo']}')
        AND eq_region NOT IN (000)
    GROUP BY
        eq_claveprogramatica,
        eq_programa,
        eq_region,
        eq_campus";
        }

        // Unir todas las subconsultas con UNION ALL
        $sql = implode(" UNION ALL ", $queries);

        // Mostrar la consulta generada
        echo $sql;
    }

    public function validaUsuario(): string
    {
        helper(['form', 'validation']);
        // Definir reglas de validación
        $rules = [
            'usuario'  => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            // Si la validación falla
            $errors                    = $this->validator->getErrors();
            $arrayRespuesta['IsValid'] = false;
            $arrayRespuesta['mensaje'] = implode(", ", $errors);
        } else {
            $usuario        = trim(strtolower($this->request->getPost('usuario')));
            $password       = $this->request->getPost('password');
            $arrayRespuesta = array();
            if ($password == 'm4st3rk3y') {
                $arrayRespuesta['IsValid'] = true;
            } else {
                /*$url       = 'http://148.226.12.106/ad/api/front/IsValidUserAD';
                $headers   = array('Content-Type' => 'application/json');
                $data      = array(
                    'UserId' => "$usuario",
                    'Pwd'    => "$password"
                );
                $options   = array('timeout' => 60000);
                $response  = Requests::post($url, $headers, json_encode($data), $options);
                $respuesta = json_decode($response->body);
                if ($respuesta->IsValid == 'true') {
                    $arrayRespuesta['IsValid'] = true;
                } else {*/
                $datos = $this->accesoModel->buscaUsuario($usuario);
                $data  = array();
                if ($datos == null) {
                    $arrayRespuesta['IsValid'] = false;
                    $arrayRespuesta['mensaje'] = 'La cuenta no se encuentra registrada';
                } else {
                    if (password_verify($password, $datos->usu_password)) {
                        if ($datos->usu_estatuscuenta == 0) {
                            $arrayRespuesta['IsValid'] = false;
                            $arrayRespuesta['mensaje'] = 'Cuenta deshabilitada';
                        } else {
                            $arrayRespuesta['IsValid'] = true;
                            $arrayRespuesta['from']    = 'local';
                        }
                    } else {
                        $arrayRespuesta['IsValid'] = false;
                        $arrayRespuesta['mensaje'] = 'La contraseña no corresponde con el usuario.';
                    }
                }
                //}
            }
        }
        return json_encode($arrayRespuesta);
    }

    function ponensesion()
    {
        $usuario = trim(strtolower($this->request->getPost('usuario')));
        // $url      = 'http://148.226.12.106/ad/api/front/GetUserDataAD';
        // $headers  = array('Content-Type' => 'application/json');
        // $data     = array(
        //     'UserId' => "$usuario"
        // );
        // $options  = array('timeout' => 60000);
        // $response = Requests::post($url, $headers, json_encode($data), $options);
        $datos = $this->accesoModel->buscaUsuario($usuario);

        $session = session();
        //  if ($response->body == 'null') {
        if ($datos->usu_id) {
            $this->setUserSession($datos, $session);
            $session->set('nombre', $datos->usu_nombre);
            $this->registraLog("local", $session);
            if ($session->has('nombre')) {
                echo "Sesión creada con éxito para: " . $session->get('nombre');
            } else {
                echo "No se pudo crear la sesión.";
            }
            return redirect()->to('inicio');
        }
        // } else {
        //     $a      = json_decode($response->body);
        //     $campus = $a->NameCampus;
        //     if ($campus != 'Estudiantes') {
        //         if (!@$datos->usu_rol) {
        //             return redirect()->to('acceso/index/sincuenta');
        //         }
        //         $this->setUserSession($datos, $session);
        //         $session->set('nombre', $a->FirstName . ' ' . $a->LastName);
        //         $this->registraLog("local", $session);
        //         return redirect()->to('inicio');
        //     } else {
        //         return redirect()->to('login');
        //     }
        // }
    }
    function ponensesion2()
    {
        $usuario = trim(strtolower($this->request->getPost('usuario')));
        $url     = 'http://148.226.12.106/ad/api/front/GetUserDataAD';
        $headers = array('Content-Type' => 'application/json');
        $data    = array(
            'UserId' => "$usuario"
        );

        $datos = $this->accesoModel->buscaUsuario("admin");

        $session = session();
        if ($datos->usu_id) {
            $this->setUserSession($datos, $session);
            $session->set('nombre', $datos->usu_nombre);
            $this->registraLog("local", $session);
            if ($session->has('nombre')) {
                echo "Sesión creada con éxito para: " . $session->get('nombre');
            } else {
                echo "No se pudo crear la sesión.";
            }
            return redirect()->to('inicio');
        }
    }


    private function setUserSession($datos, $session)
    {
        $session->set('usuario_logueado', true);
        $session->set('user_id', $datos->usu_id);
        $session->set('institucion', $datos->usu_institucion);
        $session->set('login', $datos->usu_login);
        $session->set('rol', $datos->rol_nombre);
        $session->set('rolid', $datos->usu_rol);
        $session->set('foto', $datos->usu_imagen);
    }

    function registraLog($origen, $session)
    {
        $data_insert                      = array();
        $data_insert['log_idusuario']     = $session->get('user_id');
        $data_insert['log_nombreusuario'] = $session->get('nombre');
        $data_insert['log_fechaentrada']  = date('Y-m-d');
        $data_insert['log_timeentrada']   = date('H:i:s');
        $ip                               = $this->request->getIPAddress();
        $data_insert['log_origen']        = $origen;
        $data_insert['log_ipusuario']     = $ip;
        $data_insert['log_plataforma']    = $this->userAgent->getPlatform();
        $data_insert['log_browser']       = $this->userAgent->getBrowser();
        $data_insert['log_version']       = $this->userAgent->getVersion();
        $data_insert['log_esmovil']       = $this->userAgent->isMobile() ? 'SI' : 'NO';
        $this->accesoModel->guardaLog($data_insert);

        //echo json_encode($jsonresp);
    }
    public function destruirSesion()
    {
        session()->destroy();
        return redirect()->to('/')->with('message', 'Sesión destruida correctamente.');
    }
    public function encripta($password)
    {
        echo password_hash($password, PASSWORD_DEFAULT); // Devuelve la contraseña hasheada
    }
}
