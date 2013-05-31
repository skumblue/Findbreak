<?php
    class comentario{
        private $db;
        function __construct() {
            $conn = new connect();
            $this->db = $conn->getDB();
        }
//        public function revisado($id, $userid){
//            $theObjId = new MongoId($id); 
//            return $this->db->comentariosEvento->update(array("_id" => $theObjId, "mencionados.id" => $userid),array('$set' => array('mencionados.$.revisado' => 1)));
//        }
         public function revisado($id){
            $theObjId = new MongoId($id); 
            return $this->db->notificaciones->update(array("_id" => $theObjId),array('$set' => array('estado' => 1)));
        }
        public function eliminar($id){
         $theObjId = new MongoId($id); 
          $this->db->notificaciones->remove(array("idComentario" => $theObjId));
         return $this->db->comentariosEvento->remove(array("_id" => $theObjId));
        }
        public function findNotificacionForId($id){
         $theObjId = new MongoId($id); 
         return $this->db->notificaciones->findOne(array("_id" => $theObjId));
        }
        public function findcomentarioforid($id){
         $theObjId = new MongoId($id); 
         return $this->db->comentariosEvento->findOne(array("_id" => $theObjId));
        }
        public function findforid($id){
        // $theObjId = new MongoId($id); 
         return $this->db->comentariosEvento->find(array("_eventId" => $id))->sort(array("fechaMongo" => -1 ))->limit(10);
        }
        public function verMisComentarios($id){
         $theObjId = new MongoId($id); 
         return $this->db->comentariosEvento->find(array("_userId" => $theObjId))->sort(array("fechaMongo" => -1 ))->limit(10);
        }
        public function findUltimoscomentUsuario($id,$limit){
        // $theObjId = new MongoId($id); 
         return $this->db->comentariosEvento->find(array("_userId" => $id))->sort(array("fechaMongo" => -1 ))->limit($limit);
        }
        public function findUltimoscoment($id,$limit){
        // $theObjId = new MongoId($id); 
         return $this->db->comentariosEvento->find(array("_eventId" => $id))->sort(array("fechaMongo" => -1 ))->limit($limit);
        }
        public function findOtrasMenciones($aquien, $limit){
       //  $theObjId = new MongoId($id); 
         return $this->db->comentariosEvento->find(array("mencionados.id" => $aquien))->sort(array("fechaMongo" => -1 ))->limit($limit);
        }
        public function verFecha($fechaComentario){
//           $datosComentario = explode(' ', $dcto['fechaMuestra']);
//           $fechaComentario = $datosComentario[0];
//           $horaComentario = $datosComentario[1];
        $fechaAc = date('Y-m-d H:i:s');
        
        $fechaAcsinHora = explode(" ",$fechaAc);
        $datosFechaAc = explode("-",$fechaAcsinHora[0]);
        $anioA = $datosFechaAc[0];
        $mesA = $datosFechaAc[1];
        $diaA = $datosFechaAc[2];
        
        
        $fechaComsinHora = explode(" ",$fechaComentario);
        $datosFechaTe = explode("-",$fechaComsinHora[0]);
       // $datosFechaTe = explode("-",$fechaComentarioSin);
        $anioT = $datosFechaTe[0];
        $mesT = $datosFechaTe[1];
        $diaT = $datosFechaTe[2];
        
        if($anioA < $anioT){//si la caducidad es al otro aÃ±o
            $difAnio = $anioT - $anioA;
            $meses = $difAnio * 12;
            if($mesA < $mesT)//si el mes actual es menor al de caducidad
            {  
                $meses+= $mesT - $mesA;    
            }elseif ($mesA > $mesT) {
                $meses-= $mesA - $mesT;
            }
                
        }elseif($anioA == $anioT){//si estoy en el mismo aÃ±o
            if($mesA < $mesT){
                $meses = $mesT - $mesA;
            }elseif ($mesA == $mesT) {
                $meses = 0;
            }else{
                $meses = 0;
            }
            
        }
        
        if($diaA < $diaT){
                    $dias = $diaT - $diaA;
                }elseif($diaA > $diaT){
                    $dias = 30 - ($diaA - $diaT);
                    $meses--;
                }else{//mismo dia
                    $dias = 0;
                }
       $texto = '';
       //SI ES HOY
       if($meses == 0 && $dias == 0){
            $resta = $this->diferenciaEntreFechas($fechaComentario, $fechaAc, "MINUTOS", TRUE);
            if($resta < 60){
                if($resta == 0){
                    $texto = 'Justo ahora';
                }elseif($resta == 1){
                    $texto = 'Hace 1 minuto';
                }else{
                    $texto = 'Hace '.$resta.' minutos';
                }
            }else{
                $resta = $this->diferenciaEntreFechas($fechaComentario, $fechaAc, "HORAS", TRUE);
                if($resta == 1){
                    $texto = 'Hace una hora';
                }else{
                    $texto = 'Hace '.$resta.' horas';
                }     
            }
       }else{
           $resta = $this->diferenciaEntreFechas($fechaComentario, $fechaAc, "DIAS", TRUE);
           if($resta == 1){
               $texto = 'Hace un dia';
           }elseif($resta == 0){
               $texto = 'Hace algunas horas';
           }else{
                $texto = 'Hace '.$resta.' dias';
           }
       }       
       return $texto;   
    }
    
    function diferenciaEntreFechas($fecha_principal, $fecha_secundaria, $obtener = 'SEGUNDOS', $redondear = false){
        $f0 = strtotime($fecha_principal);
        $f1 = strtotime($fecha_secundaria);
        if ($f0 < $f1) 
        {
            $tmp = $f1; 
            $f1 = $f0; 
            $f0 = $tmp; 
         }
        $resultado = ($f0 - $f1);
        switch ($obtener) {
            default: break;
            case "MINUTOS"   :   $resultado = $resultado / 60;   break;
            case "HORAS"     :   $resultado = $resultado / 60 / 60;   break;
            case "DIAS"      :   $resultado = $resultado / 60 / 60 / 24;   break;
            case "SEMANAS"   :   $resultado = $resultado / 60 / 60 / 24 / 7;   break;
        }
        if($redondear) $resultado = round($resultado);
        return $resultado;
}
//        private function transformarMenciones($menciones){
////            $puntosDeVenta = array( array('id'=>'232323',
////                                          'nombre'=>'Ticket Master',
////                                          'web'=>'http://www.google.cl'),
////                                    array('id'=>'232323',
////                                          'nombre'=>'Ticket Master',
////                                          'web'=>'http://www.google.cl')
////                                   );
//            $partes = explode('-', $menciones);
//            $arr = array();
//            for($i=0; $i<count($partes)-1; $i++){
//                $id = new MongoId($partes[$i]);
//                $arr[] = array('id'=>$id,'revisado'=>0);
//            }
//            return $arr;
//        }
        private function guardarNotificacion1($quien, $menciones, $idComent, $nombreEvent, $fechaMongo, $fecha){
            $partes = explode('-', $menciones);
            if(count($partes)>0){
                 for($i=0; $i<count($partes)-1; $i++){
                    $idM = new MongoId($partes[$i]); 
                    $noti1 = array(
                        "quien"=>$quien,
                        "aquien"=>$idM,
                        "idComentario"=>$idComent,
                        'nombreEvent'=>$nombreEvent,
                        "tipo"=>1,
                        "fechaMongo"=>$fechaMongo,
                        "fechaMuestra"=>$fecha,
                        "estado"=>0
                      );
                    $this->db->notificaciones->insert($noti1);
                 }
            }
        }
        public function findUsuarioForNick($nickname){
//         return $this->db->usuario->findOne(array("nickname" => $nickname));
             return $this->db->usuario->findOne(array("nombre" => $nickname));
        }
        private function buscarMencionados($comentario){
            //$usuario = new usuario();
//            $comentario = 'vamaam #ignacio';
            $partes = explode(' ', $comentario);
            $menciones = '';
            for($i=0; $i<count($partes); $i++){
                if(strpos($partes[$i], '#' ) !== false){//es un usuario
                    $nicknameCompleto = $partes[$i];//completo es con #
                    $nickname = str_replace('#','', $nicknameCompleto);
                    
                    $usuario = $this->findUsuarioForNick($nickname);
                    $menciones.= $usuario['_id'].'-';
                    $itemCita = '<a class="itemcita" href="/findbreak/!#'.$usuario['_id'].'">@'.$usuario['nombre'].'</a>';
                    $comentario = str_replace($nicknameCompleto, $itemCita, $comentario);
                    //reemplazar los !# por los links
                }
            }
            $re = array('menciones'=>$menciones, 'comentario'=>  nl2br($comentario));
            return $re;
        }
        public function guardarComentarioEvento($comentario,$userId,$eventId,$userName, $fecha,$nombreevent) {  
            $theObjId = new MongoId($eventId);
            $fechaMongo = new MongoDate(strtotime($fecha));
            $re = $this->buscarMencionados($comentario);
           // $mencionados = $this->transformarMenciones($menciones);
            $coment = array(
                "_userId"=>$userId,
                "_eventId"=>$theObjId,
                "nombreevent"=>$nombreevent,
                "userName"=>$userName,
                "comentario"=>$re['comentario'],
                "fechaMongo"=>$fechaMongo,
                "fechaMuestra"=>$fecha
//                "mencionados"=>$mencionados
            );
             $this->db->comentariosEvento->insert($coment); 
             $this->guardarNotificacion1($userId, $re['menciones'],$coment['_id'] , $nombreevent, $fechaMongo, $fecha);
             
            
        }
        
        public function guardarComentarioEstablecimiento($comentario,$userId,$estaId,$userName) {         
            $fechaMongo = new MongoDate(strtotime(date('Y-m-d H:i:s')));
            $fechaMuestra = date('Y-m-d H:i:s');
            $coment = array(
                "_userId"=>$userId,
                "_eventId"=>$estaId,
                "userName"=>$userName,
                "comentario"=>$comentario,
                "fechaMongo"=>$fechaMongo,
                "fechaMuestra"=>$fechaMuestra
            );
            return $this->db->comentariosEstablecimiento->insert($coment);           
        }
        
        public function guardarHashtag($hash) {
            $fecha = new MongoDate(strtotime(date('Y-m-d H:i:s')));
            $hashTagNew = array(
                "hashtag"=>$hash,
                "fecha"=>$fecha,
                "cont"=>1
            );
            return $this->db->hashtags->insert($hashTagNew);
        }
        
        
    }
?>
