<?php

namespace App\Controllers;
use Requests;
use App\Models\PonenciasModel;
use App\Models\ConvocatoriasModel;
use CodeIgniter\HTTP\UserAgent;
class Ponencias extends BaseController
{
    protected $ponenciasModel;
    protected $convocatoriasModel;
    public function __construct()
    {
        if (!session()->has('idPonente')) {
            redirect()->to('registro')->send();
            exit;
        }

        $this->ponenciasModel     = model(PonenciasModel::class);
        $this->convocatoriasModel = model(ConvocatoriasModel::class);
    }

    public function index()
    {
        $datos['ponencias'] = $this->ponenciasModel->getPonencias();
        return view('registro/inicioPonentes', $datos);
    }

    public function nuevaPonencia($idPonencia = 0)
    {
        $datos['idPonencia'] = $idPonencia;
        $datos['tematicas']  = $this->ponenciasModel->getTematicas();
        $datos['autores']    = $this->ponenciasModel->getAutores($idPonencia);
        $datos['ponencia']   = ['po_id_ponencia' => 0, 'po_estatus' => '', 'po_titulo' => '', 'po_id_tematica' => 0, 'po_id_subtematica' => 0, 'po_palabrasclave' => '', 'po_resumen' => ''];
        return view('ponencias/nuevaPonencia', $datos);
    }
    function editar($idPonencia)
    {
        $datos['idPonencia'] = $idPonencia;
        $ponenciasDePonente  = $this->ponenciasModel->getPonencias();
        if (!empty($ponenciasDePonente) && !in_array($idPonencia, array_column($ponenciasDePonente, 'po_id_ponencia'))) {
            return redirect()->to('ponencias')->with('errors', ['No tienes permiso para editar esta ponencia']);
        }
        $datos['tematicas'] = $this->ponenciasModel->getTematicas();
        $datos['autores']   = $this->ponenciasModel->getAutores($idPonencia);
        $datos['ponencia']  = $this->ponenciasModel->getPonencia($idPonencia);
        return view('ponencias/nuevaPonencia', $datos);
    }

    public function guardar()
    {
        $validation = $this->validate([
            'po_titulo'      => 'required|max_length[255]',
            'po_id_tematica' => 'required|integer',
            'archivo'        => 'uploaded[archivo]|ext_in[archivo,pdf,tex]|max_size[archivo,2048]'
        ]);
        if (!$validation) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $idPonente      = session('idPonente');
        $idPonencia     = esc($this->request->getPost('po_id_ponencia'));
        $idConvocatoria = $this->convocatoriasModel->getConvocatoriasDisponibles()[0]['id'];
        $dataPonencia   = [
            'po_titulo'          => $this->request->getPost('po_titulo'),
            'po_id_tematica'     => $this->request->getPost('po_id_tematica'),
            'po_id_subtematica'  => $this->request->getPost('po_id_subtematica'),
            'po_palabrasclave'   => $this->request->getPost('po_palabrasclave'),
            'po_resumen'         => $this->request->getPost('po_resumen'),
            'po_id_ponente'      => $idPonente,
            'po_id_convocatoria' => $idConvocatoria
        ];
        if ($idPonencia == 0) {
            $dataPonencia['po_fecha_registro'] = date('Y-m-d H:i:s');
            $dataPonencia['po_estatus']        = 'P';
            $dataPonencia['po_revisiones']     = 1;

            $archivo       = $this->request->getFile('archivo');
            $nombreArchivo = $dataPonencia['po_revisiones'] . '.' . $archivo->getExtension();
            $ponenciaId    = $this->ponenciasModel->agregaPonencia($dataPonencia);
            $rutaCarpeta   = WRITEPATH . "uploads/ponencias/ponente_$idPonente/" . $ponenciaId . "/";

            if (!is_dir($rutaCarpeta)) {
                mkdir($rutaCarpeta, 0755, true);
            }
            $archivo->move($rutaCarpeta, $nombreArchivo);
            $autores = $this->request->getPost('autores');
            if (!empty($autores)) {
                $respuestaGuardaAutores = $this->ponenciasModel->insertaAutores($autores, $ponenciaId);
                if (!$respuestaGuardaAutores) {
                    return redirect()->back()->withInput()->with('errors', ['No se pudieron guardar los autores']);
                }
            }
        }
        return redirect()->to('ponencias')->with('success', 'Ponencia registrada exitosamente.');
    }

    public function actualizarArchivo()
    {
        $idPonencia  = esc($this->request->getPost('po_id_ponencia'));
        $idPonente   = session()->get('idPonente');
        $rutaCarpeta = WRITEPATH . "uploads/ponencias/ponente_$idPonente/" . "$idPonencia/";
        if (!is_dir($rutaCarpeta)) {
            mkdir($rutaCarpeta, 0755, true);
        }
        $archivo        = $this->request->getFile('archivo');
        $numeroRevision = esc($this->request->getPost('po_revisiones')) + 1;
        $nombreArchivo  = $numeroRevision . '.' . $archivo->getExtension();
        $archivo->move($rutaCarpeta, $nombreArchivo);
        $dataPonencia = [
            'po_revisiones' => $numeroRevision,
            'po_estatus'    => 'P'
        ];
        $this->ponenciasModel->actualizaPonencia($idPonencia, $dataPonencia);
        return redirect()->to('ponencias')->with('success', 'Archivo actualizado exitosamente.');
    }

    public function subtematicas($idTematica)
    {
        $subtematicas = $this->ponenciasModel->getSubtematicas($idTematica);
        return $this->response->setJSON($subtematicas);
    }

    public function mostrarConstancia()
    {
        ob_clean();
        $ponente          = $this->request->getGet('ponente') ?? 'Ponente Desconocido';
        $ponencia         = $this->request->getGet('ponencia') ?? 'Ponencia Desconocida';
        $archivoplantilla = './images/plantilla.jpg';
        $fuente           = './images/Roboto-Bold.ttf';
        $anchodelaimagen  = 2045;

        if (!file_exists($archivoplantilla)) {
            return $this->response->setStatusCode(404)->setBody('No se encontró la plantilla.');
        }

        if (!file_exists($fuente)) {
            return $this->response->setStatusCode(404)->setBody('No se encontró la fuente.');
        }

        $imagen = imagecreatefromjpeg($archivoplantilla);

        if (!$imagen) {
            return $this->response->setStatusCode(500)->setBody('No se pudo crear la imagen.');
        }
        $color        = imagecolorallocate($imagen, 0, 0, 0);
        $bboxPonencia = imagettfbbox(40, 0, $fuente, $ponencia);
        $xPonencia    = ($anchodelaimagen - ($bboxPonencia[2] - $bboxPonencia[0])) / 2;

        $bboxPonente = imagettfbbox(70, 0, $fuente, $ponente);
        $xPonente    = ($anchodelaimagen - ($bboxPonente[2] - $bboxPonente[0])) / 2;

        imagettftext($imagen, 40, 0, $xPonencia, 950, $color, $fuente, $ponencia);
        imagettftext($imagen, 70, 0, $xPonente, 1220, $color, $fuente, $ponente);

        imagejpeg($imagen);
        imagedestroy($imagen);

        return $this->response->noCache()->setHeader('Content-Type', 'image/jpeg');
    }

}
