<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Camas
 *
 * @author bienTICS
 */
require_once APPPATH.'modules/config/controllers/Config.php';
class Camasv2 extends Config{
    public function __construct() {
        parent::__construct();
    }
    public function index() {
        $this->load->view('choque_camas');
    }
    public function AjaxCamas() {
        $Camas=  $this->config_mdl->_query("SELECT * FROM os_camas WHERE os_camas.area_id=1");
        if(!empty($Camas)){
            foreach ($Camas as $value) {
                $sql_paciente=  $this->config_mdl->_query("SELECT * FROM os_triage, os_choque_v2, os_camas WHERE 
                        os_choque_v2.triage_id=os_triage.triage_id AND
                        os_choque_v2.cama_id=os_camas.cama_id AND os_triage.triage_id='{$value['triage_id']}'");
                $Accion='';
                $Color='';
                $Paciente='&nbsp;';
                $LimpiezaMantenimiento='';
                $AltaPaciente='';
                $Enfermera='<br><br>';
                $SignosVitales='';
                $Anexos='';
                $Tarjeta='';
                $CambiarCama='';
                $Pulsera='';
                $RegistroEnfermera="";
                if($value["cama_status"]=="Disponible"){
                    $Color='blue';
                    $Accion='<button md-ink-ripple="" class="md-btn md-fab m-b green waves-effect tip btn-paciente-agregar" data-cama="'.$value['cama_id'].'"  data-original-title="Agregar Paciente">
                                <i class="mdi-content-add i-24" ></i>
                            </button>';
                }else if($value["cama_status"]=="Ocupado"){
                    $Color='green';
                    $Paciente='<b>PACIENTE</b>: '.($sql_paciente[0]['triage_nombre']=='' ? $sql_paciente[0]['triage_nombre_pseudonimo'] : $sql_paciente[0]['triage_nombre_ap'].' '.$sql_paciente[0]['triage_nombre_am'].' '.$sql_paciente[0]['triage_nombre']) ;
                    $Enf= $this->config_mdl->_get_data_condition('os_empleados',array('empleado_id'=>$sql_paciente[0]['enfermera_id']))[0];
                    $Enfermera='<br><b style="margin-left:-10px">ENF: </b>'.$Enf['empleado_nombre'].' '.$Enf['empleado_apellidos'].' <i class="fa fa-user-md pull-right pointer cambiar-enfermera i-16" style="margin-top:-3px" data-id="'.$sql_paciente[0]['triage_id'].'"></i>';
                    $AltaPaciente='<li><a class="alta-paciente" data-alta="'.$sql_paciente[0]['observacion_alta'].'" data-cama="'.$value['cama_id'].'" data-triage="'.$sql_paciente[0]['triage_id'].'"><i class="fa fa-share-square-o icono-accion"></i> Alta Paciente</a></li>';
                    $Anexos='<li><a href="'.  base_url().'Sections/Documentos/Expediente/'.$sql_paciente[0]['triage_id'].'/?tipo=Choque&url=Enfemeria" target="_blank"><i class="fa fa-files-o icono-accion"></i> Archivos Anexos</a></li>';    
                    $SignosVitales='<li><a href="'.  base_url().'Choque/Choquev2/SignosVitales/'.$sql_paciente[0]['triage_id'].'/?tipo=Choque" target="_blank"><i class="fa fa-stethoscope icono-accion"></i> Signos Vitales</a></li>';    
                    $Pulsera='<li><a href="'. base_url().'Inicio/Documentos/ImprimirPulsera/'.$sql_paciente[0]['triage_id'].'" class="imprimir-pulsera" data-id="'.$sql_paciente[0]['triage_id'].'"><i class="fa fa-print icono-accion"></i> Imprimir Pulsera</a></li>';
                    $RegistroEnfermera='<li><a href="'. base_url().'Choque/Choquev2/RegistrosEnfermeria/'.$sql_paciente[0]['triage_id'].'" target="_blank"><i class="fa fa-heartbeat icono-accion"></i> Registros Enfermería</a></li>';
                    $sql_ti= $this->config_mdl->_get_data_condition('os_tarjeta_identificacion',array('triage_id'=>$sql_paciente[0]['triage_id']))[0];
                    $CambiarCama='<li><a href="" class="cambiar-cama-paciente" data-id="'.$sql_paciente[0]['triage_id'].'" data-area="'.$value['area_id'].'" data-cama="'.$value['cama_id'].'"><i class="fa fa-bed icono-accion"></i> Cambiar Cama</a></li>';
                    $Tarjeta='<li><a href="" class="add-tarjeta-identificacion" data-id="'.$sql_paciente[0]['triage_id'].'" data-enfermedad="'.$sql_ti['ti_enfermedades'].'" data-alergia="'.$sql_ti['ti_alergias'].'"><i class="fa fa-address-card-o icono-accion"></i> Tarjeta de Identificación</a></li>';
                    $Accion='<ul class="list-inline">
                                    <li class="dropdown">
                                        <a md-ink-ripple="" data-toggle="dropdown" class="md-btn md-fab bg-white md-btn-circle waves-effect" aria-expanded="false">
                                            <i class="mdi-navigation-more-vert text-md" style="color:black"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-scale pull-right pull-up top text-color">'.$AltaPaciente.' '.$SignosVitales.' '.$LimpiezaMantenimiento.' '.$Tarjeta.' '.$CambiarCama.' '.$Pulsera.' '.$RegistroEnfermera.' '.$Anexos.'</ul>
                                    </li>
                                </ul>';
                }else if($value["cama_status"]=="En Mantenimiento"){
                    $Color='red';
                    $LimpiezaMantenimiento='<li><a class="finalizar-mantenimiento" data-id="'.$value['cama_id'].'"><i class="fa fa-cogs icono-accion"></i> Finalizar Mantenimiento</a></li>';
                    $Accion='<ul class="list-inline">
                                <li class="dropdown">
                                    <a md-ink-ripple="" data-toggle="dropdown" class="md-btn md-fab bg-white md-btn-circle waves-effect" aria-expanded="false">
                                        <i class="mdi-navigation-more-vert text-md" style="color:black"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-scale pull-right pull-up top text-color">'.$LimpiezaMantenimiento.'</ul>
                                </li>
                            </ul>';    
                }if($value["cama_status"]=="En Limpieza"){
                    $Color='orange';
                    $LimpiezaMantenimiento='<li><a class="finalizar-mantenimiento" data-id="'.$value['cama_id'].'"><i class="fa fa-cogs icono-accion"></i>Finalizar Limpieza</a></li>';
                    $Accion='<ul class="list-inline">
                                <li class="dropdown">
                                    <a md-ink-ripple="" data-toggle="dropdown" class="md-btn md-fab bg-white md-btn-circle waves-effect" aria-expanded="false">
                                        <i class="mdi-navigation-more-vert text-md" style="color:black"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-scale pull-right pull-up top text-color">'.$LimpiezaMantenimiento.'</ul>
                                </li>
                            </ul>';   
                }
                
                $col_md_3.='<div class="col-md-4 cols-camas" style="padding: 3px;margin-top:-10px">
                                    <div class="card '.$Color.' color-white">
                                        <div class="row" style="    background: #256659!important;padding: 4px 2px 2px 12px;width: 100%;margin-left: 0px;">
                                            <div class="col-md-12" style="padding-left:0px;"><b style="text-transform:uppercase;font-size:18px">
                                                <i class="fa fa-bed " ></i> <b>'.$value['cama_nombre'].'</b>
                                            </div>
                                        </div>
                                        <div class="card-heading" >
                                            <div class="row">
                                                <div class="col-md-12" style="margin-left: -14px;margin-top: -20px;">
                                                    <small style="opacity: 1;font-size: 11px"> 
                                                        <i class="fa fa-clock-o"></i> '.$value['cama_status'].'&nbsp;&nbsp;
                                                        '.$value['cama_ingreso_f'].' '.$value['cama_ingreso_h'].'
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12" style="margin-left: -14px;margin-top: -10px;">
                                                    <small style="opacity: 1;font-size: 11px"> 
                                                        <i class="fa fa-barcode"></i> Folio:&nbsp;'.$value['triage_id'].'
                                                        
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-tools" style="right:2px;top:9px">'.$Accion.'</div>
                                        <div class="card-divider" style="margin-top:-10px"></div>
                                        <div class="card-body" style="margin-top:-20px;padding-bottom: 4px;">
                                            <p style="font-size:10px;text-align:left;margin-left:-10px;margin-top:5px">'.$Paciente.'</p>
                                            <p style="font-size:10px;text-align:left;margin-top: -25px;padding-bottom:-5px">'.$Enfermera.'</p>
                                        </div>
                                    </div>
                                </div>';
                $col_md_3.='';
            }
        }else{
            $col_md_3='NO_HAY_CAMAS';
        }
        $this->setOutput(array('result_camas'=>$col_md_3));
    }
    public function AjaxVisorCamas() {
        
        $Camas=  $this->config_mdl->_query("SELECT * FROM os_camas, os_areas
                                            WHERE os_areas.area_id=os_camas.area_id AND os_areas.area_id=6");
        if(!empty($Camas)){
            foreach ($Camas as $value) {
                
                $Accion='';
                $Limpieza='';
                $Limpieza_Mantenimiento='';
                $AccionCambioE='';
                $Ingreso='<br>';
                $NombrePaciente='<br>';
                if($value['cama_status']=='Disponible'){
                    $CamaStatus='blue';
                    $Limpieza_Mantenimiento='<li><a href="#" class="dar-mantenimiento" data-area="'.$value['area_id'].'" data-id="'.$value['cama_id'].'" data-accion="En Limpieza" ><i class="fa fa-cogs icono-accion"></i> En Limpieza</a></li>';
                    $Limpieza='<li><a href="#" class="dar-mantenimiento" data-area="'.$value['area_id'].'" data-id="'.$value['cama_id'].'" data-accion="En Mantenimiento" ><i class="fa fa-cogs icono-accion"></i> En Mantenimiento</a></li>';
                    
                    $Accion='<ul class="list-inline">
                                <li class="dropdown">
                                    <a md-ink-ripple="" data-toggle="dropdown" class="md-btn md-fab bg-white md-btn-circle waves-effect" aria-expanded="false">
                                        <i class="mdi-navigation-more-vert text-md" style="color:black"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-scale pull-right pull-up top text-color">'.$Limpieza_Mantenimiento.' '.$Limpieza.'</ul>
                                </li>
                            </ul>';
                    $area=$value['cama_status'];
                }else if($value['cama_status']=='En Mantenimiento'){
                    $CamaStatus='red';
                    $Limpieza_Mantenimiento='<li><a class="finalizar-mantenimiento" data-id="'.$value['cama_id'].'" data-accion="Disponible"><i class="fa fa-cogs icono-accion"></i> Finalizar Mantenimiento</a></li>';
                    $area=$value['cama_status'];
                    $Accion='<ul class="list-inline">
                                <li class="dropdown">
                                    <a md-ink-ripple="" data-toggle="dropdown" class="md-btn md-fab bg-white md-btn-circle waves-effect" aria-expanded="false">
                                        <i class="mdi-navigation-more-vert text-md" style="color:black"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-scale pull-right pull-up top text-color">'.$Limpieza_Mantenimiento.'</ul>
                                </li>
                            </ul>';
                }else if($value['cama_status']=='En Limpieza'){
                    $CamaStatus='orange';
                    $Limpieza_Mantenimiento='<li><a class="finalizar-mantenimiento" data-id="'.$value['cama_id'].'" data-accion="Disponible"><i class="fa fa-cogs icono-accion"></i> Finalizar Limpieza</a></li>';
                    $area=$value['cama_status'];
                    $Accion='<ul class="list-inline">
                                <li class="dropdown">
                                    <a md-ink-ripple="" data-toggle="dropdown" class="md-btn md-fab bg-white md-btn-circle waves-effect" aria-expanded="false">
                                        <i class="mdi-navigation-more-vert text-md" style="color:black"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-scale pull-right pull-up top text-color">'.$Limpieza_Mantenimiento.'</ul>
                                </li>
                            </ul>';
                }else if($value['cama_status']=='Descompuesta'){
                    $CamaStatus='orange';
                    $Limpieza_Mantenimiento='<li><a class="finalizar-mantenimiento" data-id="'.$value['cama_id'].'" data-accion="Ocupado">Finalizar Limpieza / Mantenimiento</a></li>';
                    $area=$value['cama_status'];
                    $Accion='<ul class="list-inline">
                                <li class="dropdown">
                                    <a md-ink-ripple="" data-toggle="dropdown" class="md-btn md-fab bg-white md-btn-circle waves-effect" aria-expanded="false">
                                        <i class="mdi-navigation-more-vert text-md" style="color:black"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-scale pull-right pull-up top text-color">'.$Limpieza_Mantenimiento.'</ul>
                                </li>
                            </ul>';
                    
                }else if($value['cama_status']=='Ocupado'){
                    $CamaStatus='green';
                    $Paciente=$this->config_mdl->_get_data_condition('os_triage',array(
                        'triage_id'=> $value['cama_dh']
                    ))[0];
                    $Ingreso='<br><b>INGRESO: </b>'.$value['cama_ingreso_f'].' '.$value['cama_ingreso_h'];
                    if(strlen($Paciente['triage_nombre'])>30){
                        $NombrePaciente='<b>PACIENTE: </b>'. substr($Paciente['triage_nombre'], 0,30).'...';
                    }else{
                        $NombrePaciente='<b>PACIENTE: </b>'.$Paciente['triage_nombre'];
                    }                  
                }else if($value['cama_status']=='En Espera'){
                    $AccionCambioE='hidden';
                    $CamaStatus='blue-grey-700';                    
                    $Paciente=$this->config_mdl->_get_data_condition('os_triage',array(
                        'triage_id'=> $value['cama_dh']
                    ))[0];
                    $Ingreso='<br><b>INGRESO: </b>'.$value['cama_ingreso_f'].' '.$value['cama_ingreso_h'];
                    
                    if(strlen($Paciente['triage_nombre'])>30){
                        $NombrePaciente='<b>PACIENTE: </b>'. substr($Paciente['triage_nombre'], 0,30).'...';
                    }else{
                        $NombrePaciente='<b>PACIENTE: </b>'.$Paciente['triage_nombre'];
                    }
                      
                }
                if($value['cama_genero']=='Mujer'){
                    $CamaGenero='<i class="fa fa-female"></i>';
                }else{
                    $CamaGenero='<i class="fa fa-male"></i>';
                }
                $col_md_3.='<div class="col-md-4 cols-camas" >
                                
                                <div class="card '.$CamaStatus.' color-white" style="border-radius:3px">
                                    <div class="row" style="    background: #256659!important;padding: 4px 2px 2px 12px;width: 100%;margin-left: 0px;">
                                        <div class="col-md-12" "><b style="text-transform:uppercase;font-size:10px;margin-left:-14px"><i class="fa fa-window-restore"></i> '.$value['area_nombre'].'</b></div>
                                    </div>
                                    <div class="card-heading" style="margin-top:-10px">
                                        <h5 class="font-thin color-white" style="font-size:19px!important;margin-left: -10px;margin-top: 0px;text-transform: uppercase">
                                            <i class="fa fa-bed " ></i> <b>'.$value['cama_nombre'].'</b>
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-12" style="margin-left: -10px;margin-top:-9px">
                                                <small style="opacity: 1;font-size: 10px"> 
                                                    <b class="text-left"><i class="fa fa-clock-o"></i> '. substr($value['cama_status'], 0,20).'</b>&nbsp;&nbsp;&nbsp;
                                                    <b class="text-left"><i class="fa fa-window-maximize"></i> '.$value['area_tipo'].'</b>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools" style="right:2px;top:2px">'.$Accion.'</div>
                                    <div class="card-divider" style="margin-top:-10px"></div>
                                    <div class="card-body" style="margin-top:-20px;margin-left:-11px">
                                        <span style="font-size:10px"> '.$NombrePaciente.' '.$Ingreso.'</span>
                                    </div>
                                </div>
                            </div>';
                $col_md_3.='';
            }
        }else{
            $col_md_3='NO_HAY_CAMAS';
        }
        $this->setOutput(array(
            'result_camas'=>$col_md_3
        ));
    }
    public function AjaxObtenerPaciente() {
        $sql= $this->config_mdl->_get_data_condition('os_choque_v2',array(
            'triage_id'=> $this->input->post('triage_id')
        ));
        if(!empty($sql)){
            if($sql[0]['cama_id']==''){
                $this->setOutput(array('accion'=>'1')); // Asociar cama
            }else{
                $this->setOutput(array('accion'=>'3')); // Ya tiene asociada un cama
            }
        }else{
            $this->setOutput(array('accion'=>'2'));
        }
    }
    public function AjaxReingreso() {
        $data=array(
            'choque_cama_fe'=>'',
            'choque_cama_he'=>'',
            'choque_cama_fs'=>'',
            'choque_cama_hs'=>'',
            'choque_cama_alta'=>'',
            'choque_cama_status'=>'',
            'cama_id'=>0,
            'empleado_id'=>0,
            'enfermera_id'=>0
        );
        $this->config_mdl->_update_data('os_choque_camas',$data,array(
           'triage_id'=> $this->input->post('triage_id') 
        ));
        $this->setOutput(array('accion'=>'1'));
    }
    public function AjaxAsociarCama() {
        
        $data=array(
            'choque_ac_f'=> date('d/m/Y'), // fecha y hora de ingreso a observación
            'choque_ac_h'=> date('H:i'),   
            'cama_id'=> $this->input->post('cama_id'),
            'enfermera_id'=> $this->UMAE_USER,  // Enfermera que ingresa a observación
            'triage_id'=> $this->input->post('triage_id')
        );
        $this->config_mdl->_update_data('os_choque_v2',$data,array(
            'triage_id'=> $this->input->post('triage_id')
        ));
        
        $this->config_mdl->_update_data('os_camas',array(
            'cama_status'=>'Ocupado',
            'cama_ingreso_f'=> date('d/m/Y'),
            'cama_ingreso_h'=> date('H:i'),
            'cama_fh_estatus'=> date('Y-m-d H:i:s'),
            'triage_id'=>$this->input->post('triage_id')
        ),array(
            'cama_id'=>  $this->input->post('cama_id')
        ));
        Modules::run('Areas/LogCamas',array(
            'log_estatus'=>'Ocupado',
            'cama_id'=>$this->input->post('cama_id'),
        ));
        $choque= $this->config_mdl->_get_data_condition('os_choque_v2',array('triage_id'=> $this->input->post('triage_id')));
        $this->AccesosUsuarios(array('acceso_tipo'=>'Ingreso Choque (Asignación Cama)','triage_id'=>$this->input->post('triage_id'),'areas_id'=>$choque[0]['choque_id']));
        $this->setOutput(array('accion'=>'1'));
    }
    public function AjaxCambiarCama() {
        $this->config_mdl->_update_data('os_camas',array(
            'cama_status'=>'En Limpieza',
            'cama_ingreso_f'=> '',
            'cama_ingreso_h'=> '',
            'cama_fh_estatus'=> date('Y-m-d H:i:s'),
            'cama_dh'=>'0'
        ),array(
            'cama_id'=>  $this->input->post('cama_id_old')
        ));
        Modules::run('Areas/LogCamas',array(
            'log_estatus'=>'En Limpieza',
            'cama_id'=>$this->input->post('cama_id'),
        ));
        $this->config_mdl->_update_data('os_camas',array(
            'cama_status'=>'Ocupado',
            'cama_ingreso_f'=> date('d/m/Y'),
            'cama_ingreso_h'=> date('H:i'),
            'cama_fh_estatus'=> date('Y-m-d H:i:s'),
            'cama_dh'=> $this->input->post('triage_id')
        ),array(
            'cama_id'=>  $this->input->post('cama_id_new')
        ));
        Modules::run('Areas/LogCamas',array(
            'log_estatus'=>'Ocupado',
            'cama_id'=>$this->input->post('cama_id_new'),
        ));
        $this->config_mdl->_update_data('os_choque_v2',array(
            'cama_id'=>  $this->input->post('cama_id_new'),
            'choque_ac_f'=> date('d/m/Y'),
            'choque_ac_h'=>  date('H:i') 
        ),array(
            'triage_id'=>  $this->input->post('triage_id')
        ));
        $this->config_mdl->_insert('os_camas_log',array(
            'cama_log_fecha'=> date('d/m/Y'),
            'cama_log_hora'=> date('H:i'),
            'cama_log_tipo'=>'Cambio de Cama',
            'cama_log_modulo'=>'Choque',
            'cama_id'=> $this->input->post('cama_id_new'),
            'triage_id'=> $this->input->post('triage_id'),
            'empleado_id'=> $this->UMAE_USER
        ));
        $this->setOutput(array('accion'=>'1'));
    }
    public function AjaxCambiarEnfermera() {
        $sql= $this->config_mdl->_get_data_condition('os_empleados',array(
            'empleado_matricula'=> $this->input->post('empleado_matricula')
        ));
        if(!empty($sql)){
            $choque= $this->config_mdl->_get_data_condition('os_choque_v2',array(
                'triage_id'=>  $this->input->post('triage_id')
            ))[0];
            $this->config_mdl->_insert('os_log_cambio_enfermera',array(
                'cambio_fecha'=> date('d/m/Y'),
                'cambio_hora'=> date('H:i'),
                'cambio_modulo'=>'Choque',
                'cambio_cama'=>$choque['cama_id'],
                'empleado_new'=> $sql[0]['empleado_id'],
                'empleado_old'=> $choque['enfermera_id'],
                'empleado_cambio'=> $this->UMAE_USER,
                'triage_id'=>$this->input->post('triage_id')
            ));
            
            $this->config_mdl->_update_data('os_choque_v2',array(
                'enfermera_id'=>$sql[0]['empleado_id']
            ),array(
                'triage_id'=>  $this->input->post('triage_id')
            ));
            $this->AccesosUsuarios(array('acceso_tipo'=>'Cambio de Enfermera Choque','triage_id'=>$this->input->post('triage_id'),'areas_id'=>$choque['choque_id']));
            $this->setOutput(array('accion'=>'1'));
        }else{
            $this->setOutput(array('accion'=>'2'));
        }
    }
}
