<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Documentos
 *
 * @author felipe de jesus
 */
require_once APPPATH.'modules/config/controllers/Config.php';
class DocumentosProductividad extends Config{
    public function index() {
        $this->load->view('documentos/index');
    }
    
    public function LechugaAdmisionContinua() {
        $inputFechaInicio= $this->input->get_post('inputFechaInicio');
        $fecha = date("Y-m-d", strtotime($inputFechaInicio));
        $fechaObs = date("d/m/Y", strtotime($inputFechaInicio));
        $id_medico = $this->input->get_post('id_medico');
        $area = $this->input->get_post('area_ac');
        $turno = $this->input->get_post('turno');
          $sql['medico']= $this->config_mdl->_get_data_condition('os_empleados',array(
            'empleado_id' => $id_medico
        ))[0];
        if($area == 'Cons-Obs') {
            switch ($turno) {
                case 'Mañana':  $sql['HF_Consultorios']= $this->config_mdl->_query("
                                    SELECT      os_triage.triage_id,
                                                os_triage.triage_horacero_f,
                                                os_triage.triage_horacero_h,
                                                os_triage.triage_hora,
                                                os_triage.triage_hora_clasifica,
                                                os_triage.triage_nombre,
                                                os_triage.triage_nombre_ap,
                                                os_triage.triage_nombre_am,
                                                os_triage.triage_nombre_pseudonimo,
                                                os_triage.triage_color,
                                                os_consultorios_especialidad.ce_fe,
                                                os_consultorios_especialidad.ce_he,
                                                os_consultorios_especialidad_hf.hf_ce,
                                                os_consultorios_especialidad_hf.hf_obs,
                                                os_consultorios_especialidad_hf.hf_choque,
                                                os_consultorios_especialidad_hf.empleado_id,
                                                os_consultorios_especialidad_hf.hf_fg,
                                                os_consultorios_especialidad_hf.hf_hg,
                                                os_consultorios_especialidad_hf.hf_alta,
                                                os_consultorios_especialidad_hf.hf_procedimientos
                                        FROM    os_consultorios_especialidad, os_consultorios_especialidad_hf, os_triage
                                        WHERE   os_consultorios_especialidad_hf.triage_id=os_triage.triage_id 
                                            AND os_consultorios_especialidad.triage_id = os_consultorios_especialidad_hf.triage_id
                                            AND os_consultorios_especialidad_hf.empleado_id='$id_medico'  
                                            AND os_consultorios_especialidad_hf.hf_fg='$inputFechaInicio'
                                            AND os_consultorios_especialidad_hf.hf_hg BETWEEN '07:20' AND '14:00' 
                                        ");
                                $sql['HF_Observacion'] = $this->config_mdl->_query(
                                    "SELECT
                                            os_triage.triage_id,
                                            os_triage.triage_horacero_f,
                                            os_triage.triage_horacero_h,
                                            os_triage.triage_hora,
                                            os_triage.triage_hora_clasifica,
                                            os_observacion.observacion_fe,
                                            os_observacion.observacion_he,
                                            os_triage.triage_nombre,
                                            os_triage.triage_nombre_ap,
                                            os_triage.triage_nombre_am,
                                            os_triage.triage_nombre_pseudonimo,
                                            os_triage.triage_color,
                                            os_consultorios_especialidad_hf.hf_ce,
                                            os_consultorios_especialidad_hf.hf_obs,
                                            os_consultorios_especialidad_hf.hf_choque,
                                            os_consultorios_especialidad_hf.empleado_id,
                                            os_consultorios_especialidad_hf.hf_fg,
                                            os_consultorios_especialidad_hf.hf_hg,
                                            os_consultorios_especialidad_hf.hf_alta,
                                            os_consultorios_especialidad_hf.hf_procedimientos                               
                                    FROM    os_consultorios_especialidad_hf, os_observacion, os_triage
                                    WHERE   os_observacion.triage_id = os_consultorios_especialidad_hf.triage_id
                                        AND os_observacion.empleado_id = '$id_medico'
                                        AND os_observacion.observacion_fe = '$fechaObs'
                                        AND os_consultorios_especialidad_hf.triage_id = os_triage.triage_id
                                        AND os_consultorios_especialidad_hf.hf_hg BETWEEN '07:20' AND '14:00' 
                                    ");
                                $sql['Notas']= $this->config_mdl->_query("
                                    SELECT  
                                        os_triage.triage_id,
                                        os_triage.triage_horacero_f,
                                        os_triage.triage_horacero_h,
                                        os_triage.triage_hora,
                                        os_triage.triage_hora_clasifica,
                                        doc_notas.notas_fecha,
                                        doc_notas.notas_hora,
                                        os_triage.triage_nombre,
                                        os_triage.triage_nombre_ap,
                                        os_triage.triage_nombre_am,
                                        os_triage.triage_nombre_pseudonimo,
                                        os_triage.triage_color,
                                        doc_notas.empleado_id
                                    FROM doc_notas, os_triage WHERE doc_notas.triage_id=os_triage.triage_id 
                                    AND doc_notas.empleado_id='$id_medico' 
                                    AND doc_notas.notas_fecha='$inputFechaInicio'
                                    AND doc_notas.notas_hora BETWEEN '07:20' AND '14:00'
                                    ");
                    break;
                case 'Tarde':   $sql['HF_Consultorios']= $this->config_mdl->_query("
                                        SELECT  os_triage.triage_id,
                                                os_triage.triage_horacero_f,
                                                os_triage.triage_horacero_h,
                                                os_triage.triage_hora,
                                                os_triage.triage_hora_clasifica,
                                                os_triage.triage_nombre,
                                                os_triage.triage_nombre_ap,
                                                os_triage.triage_nombre_am,
                                                os_triage.triage_nombre_pseudonimo,
                                                os_triage.triage_color,
                                                os_consultorios_especialidad.ce_fe,
                                                os_consultorios_especialidad.ce_he,
                                                os_consultorios_especialidad_hf.hf_ce,
                                                os_consultorios_especialidad_hf.hf_obs,
                                                os_consultorios_especialidad_hf.hf_choque,
                                                os_consultorios_especialidad_hf.empleado_id,
                                                os_consultorios_especialidad_hf.hf_fg,
                                                os_consultorios_especialidad_hf.hf_hg,
                                                os_consultorios_especialidad_hf.hf_alta,
                                                os_consultorios_especialidad_hf.hf_procedimientos
                                        FROM    os_consultorios_especialidad, os_consultorios_especialidad_hf, os_triage
                                        WHERE   os_consultorios_especialidad_hf.triage_id=os_triage.triage_id
                                            AND os_consultorios_especialidad.triage_id = os_consultorios_especialidad_hf.triage_id
                                            AND os_consultorios_especialidad_hf.empleado_id='$id_medico'  
                                            AND os_consultorios_especialidad_hf.hf_fg='$inputFechaInicio'
                                            AND os_consultorios_especialidad_hf.hf_hg BETWEEN '14:00' AND '20:30' 
                                        ");

                                    $sql['HF_Observacion'] = $this->config_mdl->_query(
                                        "SELECT
                                                os_triage.triage_id,
                                                os_triage.triage_horacero_f,
                                                os_triage.triage_horacero_h,
                                                os_triage.triage_hora,
                                                os_triage.triage_hora_clasifica,
                                                os_observacion.observacion_fe,
                                                os_observacion.observacion_he,
                                                os_triage.triage_nombre,
                                                os_triage.triage_nombre_ap,
                                                os_triage.triage_nombre_am,
                                                os_triage.triage_nombre_pseudonimo,
                                                os_triage.triage_color,
                                                os_consultorios_especialidad_hf.hf_ce,
                                                os_consultorios_especialidad_hf.hf_obs,
                                                os_consultorios_especialidad_hf.hf_choque,
                                                os_consultorios_especialidad_hf.empleado_id,
                                                os_consultorios_especialidad_hf.hf_fg,
                                                os_consultorios_especialidad_hf.hf_hg,
                                                os_consultorios_especialidad_hf.hf_alta,
                                                os_consultorios_especialidad_hf.hf_procedimientos                               
                                        FROM    os_consultorios_especialidad_hf, os_observacion, os_triage
                                        WHERE   os_observacion.triage_id = os_consultorios_especialidad_hf.triage_id
                                            AND os_observacion.empleado_id = '$id_medico'
                                            AND os_observacion.observacion_fe = '$fechaObs'
                                            AND os_consultorios_especialidad_hf.triage_id = os_triage.triage_id
                                            AND os_consultorios_especialidad_hf.hf_hg BETWEEN '14:00' AND '20:30' 
                                    ");
                                    $sql['Notas']= $this->config_mdl->_query("
                                        SELECT 
                                            os_triage.triage_id,
                                            os_triage.triage_horacero_f,
                                            os_triage.triage_horacero_h,
                                            os_triage.triage_hora,
                                            os_triage.triage_hora_clasifica,
                                            doc_notas.notas_fecha,
                                            doc_notas.notas_hora,
                                            os_triage.triage_nombre,
                                            os_triage.triage_nombre_ap,
                                            os_triage.triage_nombre_am,
                                            os_triage.triage_nombre_pseudonimo,
                                            os_triage.triage_color,
                                            doc_notas.empleado_id
                                        FROM doc_notas, os_triage WHERE doc_notas.triage_id=os_triage.triage_id 
                                        AND doc_notas.empleado_id='$id_medico'  
                                        AND doc_notas.notas_fecha='$inputFechaInicio'
                                        AND doc_notas.notas_hora BETWEEN '14:00' AND '20:30'
                                    ");
                                
                    break;
                case 'Noche':   $fechaNoche = strtotime('+1 day', strtotime($inputFechaInicio)); 
                                $fechaNoche = date('d-m-Y', $fechaNoche); 
                                $fechaObsNoche =date("d/m/Y", strtotime($fechaNoche)); 

                                $sql['HF_Consultorios']= $this->config_mdl->_query("
                                        SELECT  os_triage.triage_id,
                                                os_triage.triage_horacero_f,
                                                os_triage.triage_horacero_h,
                                                os_triage.triage_hora,
                                                os_triage.triage_hora_clasifica,
                                                os_triage.triage_nombre,
                                                os_triage.triage_nombre_ap,
                                                os_triage.triage_nombre_am,
                                                os_triage.triage_nombre_pseudonimo,
                                                os_triage.triage_color,
                                                os_consultorios_especialidad.ce_fe,
                                                os_consultorios_especialidad.ce_he,
                                                os_consultorios_especialidad_hf.hf_ce,
                                                os_consultorios_especialidad_hf.hf_obs,
                                                os_consultorios_especialidad_hf.hf_choque,
                                                os_consultorios_especialidad_hf.empleado_id,
                                                os_consultorios_especialidad_hf.hf_fg,
                                                os_consultorios_especialidad_hf.hf_hg,
                                                os_consultorios_especialidad_hf.hf_alta,
                                                os_consultorios_especialidad_hf.hf_procedimientos
                                        FROM    os_consultorios_especialidad, os_consultorios_especialidad_hf, os_triage
                                        WHERE os_consultorios_especialidad_hf.triage_id=os_triage.triage_id 
                                            AND os_consultorios_especialidad.triage_id = os_consultorios_especialidad_hf.triage_id
                                            AND os_consultorios_especialidad_hf.empleado_id='$id_medico'  
                                            AND os_consultorios_especialidad_hf.hf_fg='$inputFechaInicio'
                                            AND os_consultorios_especialidad_hf.hf_hg BETWEEN '20:30' AND '23:59' 
                                        UNION
                                        SELECT  os_triage.triage_id,
                                                os_triage.triage_horacero_f,
                                                os_triage.triage_horacero_h,
                                                os_triage.triage_hora,
                                                os_triage.triage_hora_clasifica,
                                                os_triage.triage_nombre,
                                                os_triage.triage_nombre_ap,
                                                os_triage.triage_nombre_am,
                                                os_triage.triage_nombre_pseudonimo,
                                                os_triage.triage_color,
                                                os_consultorios_especialidad.ce_fe,
                                                os_consultorios_especialidad.ce_he,
                                                os_consultorios_especialidad_hf.hf_ce,
                                                os_consultorios_especialidad_hf.hf_obs,
                                                os_consultorios_especialidad_hf.hf_choque,
                                                os_consultorios_especialidad_hf.empleado_id,
                                                os_consultorios_especialidad_hf.hf_fg,
                                                os_consultorios_especialidad_hf.hf_hg,
                                                os_consultorios_especialidad_hf.hf_alta,
                                                os_consultorios_especialidad_hf.hf_procedimientos
                                        FROM    os_consultorios_especialidad, os_consultorios_especialidad_hf, os_triage
                                        WHERE   os_consultorios_especialidad_hf.triage_id=os_triage.triage_id 
                                            AND os_consultorios_especialidad.triage_id = os_consultorios_especialidad_hf.triage_id
                                            AND os_consultorios_especialidad_hf.empleado_id='$id_medico'  
                                            AND os_consultorios_especialidad_hf.hf_fg='$fechaNoche'
                                            AND os_consultorios_especialidad_hf.hf_hg BETWEEN '00:00' AND '07:20' 

                                        ");

                                $sql['HF_Observacion'] = $this->config_mdl->_query(
                                    "SELECT
                                                os_triage.triage_id,
                                                os_triage.triage_horacero_f,
                                                os_triage.triage_horacero_h,
                                                os_triage.triage_hora,
                                                os_triage.triage_hora_clasifica,
                                                os_observacion.observacion_fe,
                                                os_observacion.observacion_he,
                                                os_triage.triage_nombre,
                                                os_triage.triage_nombre_ap,
                                                os_triage.triage_nombre_am,
                                                os_triage.triage_nombre_pseudonimo,
                                                os_triage.triage_color,
                                                os_consultorios_especialidad_hf.hf_ce,
                                                os_consultorios_especialidad_hf.hf_obs,
                                                os_consultorios_especialidad_hf.hf_choque,
                                                os_consultorios_especialidad_hf.empleado_id,
                                                os_consultorios_especialidad_hf.hf_fg,
                                                os_consultorios_especialidad_hf.hf_hg,
                                                os_consultorios_especialidad_hf.hf_alta,
                                                os_consultorios_especialidad_hf.hf_procedimientos                               
                                        FROM    os_consultorios_especialidad_hf, os_observacion, os_triage
                                        WHERE   os_observacion.triage_id = os_consultorios_especialidad_hf.triage_id
                                            AND os_observacion.empleado_id = '$id_medico'
                                            AND os_observacion.observacion_fe = '$fechaObs'
                                            AND os_consultorios_especialidad_hf.triage_id = os_triage.triage_id
                                            AND os_consultorios_especialidad_hf.hf_hg BETWEEN '20:30' AND '23:59' 
                                    UNION
                                    SELECT
                                                os_triage.triage_id,
                                                os_triage.triage_horacero_f,
                                                os_triage.triage_horacero_h,
                                                os_triage.triage_hora,
                                                os_triage.triage_hora_clasifica,
                                                os_observacion.observacion_fe,
                                                os_observacion.observacion_he,
                                                os_triage.triage_nombre,
                                                os_triage.triage_nombre_ap,
                                                os_triage.triage_nombre_am,
                                                os_triage.triage_nombre_pseudonimo,
                                                os_triage.triage_color,
                                                os_consultorios_especialidad_hf.hf_ce,
                                                os_consultorios_especialidad_hf.hf_obs,
                                                os_consultorios_especialidad_hf.hf_choque,
                                                os_consultorios_especialidad_hf.empleado_id,
                                                os_consultorios_especialidad_hf.hf_fg,
                                                os_consultorios_especialidad_hf.hf_hg,
                                                os_consultorios_especialidad_hf.hf_alta,
                                                os_consultorios_especialidad_hf.hf_procedimientos                               
                                        FROM    os_consultorios_especialidad_hf, os_observacion, os_triage
                                        WHERE   os_observacion.triage_id = os_consultorios_especialidad_hf.triage_id
                                            AND os_observacion.empleado_id = '$id_medico'
                                            AND os_observacion.observacion_fe = '$fechaObsNoche'
                                            AND os_consultorios_especialidad_hf.triage_id = os_triage.triage_id
                                            AND os_consultorios_especialidad_hf.hf_hg BETWEEN '00:00' AND '07:20' 
                                    
                                    ");

                                $sql['Notas']= $this->config_mdl->_query("
                                    SELECT 
                                            os_triage.triage_id,
                                            os_triage.triage_horacero_f,
                                            os_triage.triage_horacero_h,
                                            os_triage.triage_hora,
                                            os_triage.triage_hora_clasifica,
                                            doc_notas.notas_fecha,
                                            doc_notas.notas_hora,
                                            os_triage.triage_nombre,
                                            os_triage.triage_nombre_ap,
                                            os_triage.triage_nombre_am,
                                            os_triage.triage_nombre_pseudonimo,
                                            os_triage.triage_color,
                                            doc_notas.empleado_id
                                    FROM doc_notas, os_triage WHERE doc_notas.triage_id=os_triage.triage_id 
                                        AND doc_notas.empleado_id='$id_medico'
                                        AND doc_notas.notas_fecha='$inputFechaInicio'
                                        AND doc_notas.notas_hora BETWEEN '20:30' AND '23:59'
                                    UNION
                                    SELECT 
                                            os_triage.triage_id,
                                            os_triage.triage_horacero_f,
                                            os_triage.triage_horacero_h,
                                            os_triage.triage_hora,
                                            os_triage.triage_hora_clasifica,
                                            doc_notas.notas_fecha,
                                            doc_notas.notas_hora,
                                            os_triage.triage_nombre,
                                            os_triage.triage_nombre_ap,
                                            os_triage.triage_nombre_am,
                                            os_triage.triage_nombre_pseudonimo,
                                            os_triage.triage_color,
                                            doc_notas.empleado_id
                                    FROM doc_notas, os_triage WHERE doc_notas.triage_id=os_triage.triage_id 
                                        AND doc_notas.empleado_id='$id_medico'  
                                        AND doc_notas.notas_fecha='$fechaNoche'
                                        AND doc_notas.notas_hora BETWEEN '00:00' AND '07:20'
                                        ");

                    break;
            }
        }else {
                switch ($turno) {
                    case 'Mañana':
                                    $horaInicial = '07:20';
                                    $horaFinal   = '14:00';
                        break;
                    case 'Tarde':
                                    $horaInicial = '14:00';
                                    $horaFinal   = '20:30';
                        break;
                    case 'Noche':
                                    $horaInicial_A = '20:30';
                                    $horaFinal_A   = '23:59';
                                    $horaInicial_B = '00:00';
                                    $horaFinal_B   = '07:20'; 
                        break;
                }  
                if($turno == 'Noche') { 
                       
                        $fechaNoche = strtotime('+1 day', strtotime($inputFechaInicio)); 
                        $fechaNoche = date('Y-m-d', $fechaNoche);  
                        $sql['HojasFrontales_medico_triage']= $this->config_mdl->_query("
                            SELECT  os_triage.triage_id,
                                    os_triage.triage_horacero_f,
                                    os_triage.triage_horacero_h,
                                    os_triage.triage_hora,
                                    os_triage.triage_hora_clasifica,
                                    os_triage.triage_nombre,
                                    os_triage.triage_nombre_ap,
                                    os_triage.triage_nombre_am,
                                    os_triage.triage_nombre_pseudonimo,
                                    os_triage.triage_color,
                                    os_triage.triage_crea_medico,
                                    os_triage.triage_motivoAtencion,
                                    os_triage.triage_envio_otraunidad,
                                    os_triage.triage_envio_nombre,
                                    paciente_info.triage_id
                            FROM os_triage
                            JOIN paciente_info on paciente_info.triage_id=os_triage.triage_id 
                                AND os_triage.triage_fecha='$fecha'
                                AND os_triage.triage_crea_medico= '$id_medico'
                                AND os_triage.triage_hora_clasifica BETWEEN '$horaInicial_A' AND '$horaFinal_A'
                            UNION
                            SELECT  os_triage.triage_id,
                                    os_triage.triage_horacero_f,
                                    os_triage.triage_horacero_h,
                                    os_triage.triage_hora,
                                    os_triage.triage_hora_clasifica,
                                    os_triage.triage_nombre,
                                    os_triage.triage_nombre_ap,
                                    os_triage.triage_nombre_am,
                                    os_triage.triage_nombre_pseudonimo,
                                    os_triage.triage_color,
                                    os_triage.triage_crea_medico,
                                    os_triage.triage_motivoAtencion,
                                    os_triage.triage_envio_otraunidad,
                                    os_triage.triage_envio_nombre,
                                    paciente_info.triage_id
                            FROM os_triage
                            JOIN paciente_info on paciente_info.triage_id=os_triage.triage_id 
                                AND os_triage.triage_fecha='$fechaNoche'
                                AND os_triage.triage_crea_medico= '$id_medico'
                                AND os_triage.triage_hora_clasifica BETWEEN '$horaInicial_B' AND '$horaFinal_B'
                        ");

                } else {
                            $sql['HojasFrontales_medico_triage']= $this->config_mdl->_query("
                            SELECT  os_triage.triage_id,
                                    os_triage.triage_horacero_f,
                                    os_triage.triage_horacero_h,
                                    os_triage.triage_hora,
                                    os_triage.triage_hora_clasifica,
                                    os_triage.triage_nombre,
                                    os_triage.triage_nombre_ap,
                                    os_triage.triage_nombre_am,
                                    os_triage.triage_nombre_pseudonimo,
                                    os_triage.triage_color,
                                    os_triage.triage_crea_medico,
                                    os_triage.triage_motivoAtencion,
                                    os_triage.triage_envio_otraunidad,
                                    os_triage.triage_envio_nombre,
                                    paciente_info.triage_id
                            FROM os_triage
                            JOIN paciente_info on paciente_info.triage_id=os_triage.triage_id 
                                AND os_triage.triage_fecha='$fecha'
                                AND os_triage.triage_crea_medico='$id_medico'
                                AND os_triage.triage_hora_clasifica BETWEEN '$horaInicial' AND '$horaFinal'
                            ");
                       }
        }
        $this->load->view('documentosProductividad/lechugaAdmisionContinua',$sql);
    }
}