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
        $datos['tematicas']  = $this->ponenciasModel->getTematicas();
        $datos['autores']    = $this->ponenciasModel->getAutores($idPonencia);
        $datos['ponencia']   = $this->ponenciasModel->getPonencia($idPonencia);
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
        $idPonente      = session()->get('idPonente');
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
            $rutaCarpeta                       = WRITEPATH . "uploads/ponencias/ponente_$idPonente/";
            if (!is_dir($rutaCarpeta)) {
                mkdir($rutaCarpeta, 0755, true);
            }
            $archivo       = $this->request->getFile('archivo');
            $nombreArchivo = $dataPonencia['po_revisiones'] . '.' . $archivo->getExtension();
            $archivo->move($rutaCarpeta, $nombreArchivo);

            $ponenciaId = $this->ponenciasModel->agregaPonencia($dataPonencia);
            $autores    = $this->request->getPost('autores');
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
        $rutaCarpeta = WRITEPATH . "uploads/ponencias/ponente_$idPonente/";
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

}
