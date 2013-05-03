<?php
    require_once '../DAL/connect.php';
    require_once '../DAL/usuario.php';
    date_default_timezone_set("Chile/Continental");
    //reemplazarBr
    if(!empty($_POST["reemplazarBr"]))
    {
        $textoPlano = $_POST["textoPlano"];
        echo nl2br($textoPlano);
    }
    if(!empty($_POST["dejarEnlace"]))
    {
        $id = $_POST["id"];
        $nombre = $_POST["nombre"];
        $busqueda = $_POST["coment"];

        $palabras = explode(' ', $busqueda);
        $textoAmigo = '';
        if(count($palabras) > 1){
            for($i=0; $i<count($palabras); $i++){
                   if(strpos($palabras[$i], '@') !== false){//si encuentro el arroa paro
                       $textoAmigo = $palabras[$i];
                        break;
                   }
                }
        }else{//es una palabra
            $textoAmigo = $busqueda;
        }
        $nombreCitaDB2 = ' <a href="/findbreak/!#'.$id.'"
                                    data-id="'.$id.'"
                                    class="itemcita">'.$nombre.'</a> ';
        
        $nombreCita = $nombre;
        $comentarioFinal = str_replace($textoAmigo, $nombreCitaDB2, $busqueda);
        
       
        echo $comentarioFinal;
        /*
         var nombreCita = ' <a \n\
                                    href="/findbreak/!#'+id+'" \n\
                                    data-id="'+id+'"\n\
                                    class="itemcita">'+nombre+'</a> ';
         */
    }
    if(!empty($_POST["search-friend-cit"]))
    {
            $busqueda = $_POST["textoAmigo"];
            $palabras = explode(' ', $busqueda);
            $textoAmigo = '';
            $r = 0;
            $hayArroa = false;
            for($i=0; $i<count($palabras); $i++){
               if(strpos($palabras[$i], '@') !== false){//si encuentro el arroa paro
                   $textoAmigo = $palabras[$i];
                   $hayArroa = true;
                    //break;
               }
            }
            $usuario = new usuario();
           $textoAmigoSinArroa = str_replace('@', '', $textoAmigo);
            $coincidencia = $usuario->findFriend($textoAmigoSinArroa);
            //crear cuadro de busqueda de AMIGOS
            $cuadrouser = '';
            $hayuser = false;
            foreach($coincidencia as $dcto)
            {
                $hayuser = true;
                $cuadrouser.= '<div data-id="'.$dcto["_id"].'" class="item-friends-user itemCitar">
                                <div style="background-image:url(https://fbcdn-profile-a.akamaihd.net/hprofile-ak-prn1/c170.50.621.621/s160x160/604099_10200642826730761_1474278375_n.jpg)" class="item-friends-userpic"></div>
                                <div class="item-friends-username">'.$dcto["nombre"].'</div>
                             </div>';
                
            }
            
            if($hayArroa)
            echo $cuadrouser;
            else
            echo '';
            
    }
    if(!empty($_POST["revisarnot2"]))
    {
        require_once '../DAL/comentario.php';
        $id = $_POST['id'];
        $comentarios = new comentario();
        $comentarios->revisado($id);//dejo la notificacion como revisada
    }
    if(!empty($_POST["search-friend-citar"]))
    {
         session_start();
         $usuario = new usuario();
         $id = $_SESSION['userid'];
         $yo = $usuario->findforid($id);
         $html = '';
         if(isset($yo['siguiendo']) && count($yo['siguiendo'])>0){
             foreach ($yo['siguiendo'] as $item){
                 $html.= '<div data-id="'.$item['_id'].'" class="item-friends-user itemCitar">
                                        <div style="background-image:url('.$item['foto'].')" class="item-friends-userpic"></div>
                                        <div class="item-friends-username">'.ucwords($item['nombre']).'</div>
                                    </div>';
             }
         }else{
             $html = 'aun no sigues a tus amigos, búscalos !';
         }
         echo $html;
    }
    if(!empty($_POST["search-friend"]))
    {
            $busqueda = $_POST["textoAmigo"];
            $usuario = new usuario();
            $coincidencia = $usuario->findFriend($busqueda);
            
            
            //crear cuadro de busqueda de AMIGOS
            $cuadrouser = '<div class="title-search-item">Personas</div>';
            $hayuser = false;
            foreach($coincidencia as $dcto)
            {
                $hayuser = true;
                $cuadrouser.= 
                '<div class="item-search item-search-friend">
                   <div class="foto-item-search"></div>
                   <div class="name-item-search tit">'.$dcto["nombre"].'</div>
                   <div style="display:none" class="id-item-search">'.$dcto["_id"].'</div>
                </div>';
                
            }
            if(!$hayuser){//si no hay usuarios encontrados borro el titulo
                $cuadrouser = '';
            }
            //EVENTOS 
             require_once '../DAL/evento.php';
             $cuadroevento = '<div class="title-search-item">Eventos</div>';
             
             $evento = new evento();
             $coincidenciaevento = $evento->filtrar($busqueda);
             $hayevents = false;
             foreach($coincidenciaevento as $dcto)
            {
                $hayevents = true;
                $cuadroevento.= 
                '<a href="/findbreak/break/'.(string)$dcto['_id'].'" target="_blank" class="item-search item-search-event">
                   <div class="foto-item-search"></div>
                   <div class="name-item-search tit">'.$dcto["nombre"].'</div>
                   <div style="display:none" class="id-item-search">'.$dcto["_id"].'</div>
                </a>';
                
            }
            
            if(!$hayevents){//si no hay usuarios encontrados borro el titulo
                $cuadroevento = '';
            }
            //ESTABLECIMIENTOS
//            if($cuadro == ""){
//                $cuadro = "no";
//            }
            echo $cuadrouser.$cuadroevento;
    }
           
    if(!empty($_REQUEST["seguirpersona"]))
    {
        session_start();
        $quien = $_SESSION["userid"];
        $aquien = $_REQUEST['idSolicitado'];
        $solicitud = new usuario();
        $userQuien = $solicitud->findforid($quien);
        $userAquien = $solicitud->findforid($aquien);
        $resp = $solicitud->agregarSeguidor($userQuien, $userAquien);
        $resp2 = $solicitud->agregarSiguiendo($userQuien, $userAquien);
        $fecha = date('Y-m-d H:i:s');
        $fechaMongo = new MongoDate(strtotime($fecha));
        $solicitud->guardarNotificacion2($userQuien, $userAquien, $fechaMongo, $fecha);
        $re = -1;
        $item = '<div data-id="'.$userQuien['_id'].'" class="item-friends-user">';
        $item.=  '<div style="background-image:url('.$userQuien['foto'].')" class="item-friends-userpic"></div>';
        $item.=  '<div class="item-friends-username">'.$userQuien['nombre'].'</div>';
        $item.= '</div>';
        if($resp == $resp2){
            $re = 1;
        }else{
            $re = -1;
        }
        $re = json_encode(array('re'=>$re, 'item'=>$item));
        echo $re;
    }
    
    if(!empty($_REQUEST["dejardeseguirpersona"]))
    {
        session_start();
        $quien = $_SESSION["userid"];
        $aquien = $_REQUEST['idSolicitado'];
        $solicitud = new usuario();
        $resp = $solicitud->dejarDeSeguir($quien, $aquien);
        $resp2 = $solicitud->eliminarSeguidor($quien, $aquien);
        if($resp == $resp2){
            $re = 1;
        }else{
            $re = -1;
        }
        $re = json_encode(array('re'=>$re, 'idUser'=>(string)$quien));
        echo $re;
    }
    if(!empty($_POST["guardaruser"]))
    {
//"guardaruser=1&nomuser="+nomeuser+"&nombrefoto="+res.nombrefoto+"&apellido="+apellido+"&correousuario="+correousuario+"&claveusuario="+claveusuario, 
        $name = $_POST['nomuser'];
        $apellido = $_POST['apellido'];
        $mail = $_POST['correousuario'];
        $pass = $_POST['claveusuario'];
        
        $usuario = new usuario();
        
        $resp = $usuario->insertar(strtolower($name), strtolower($apellido), strtolower($mail), $pass);
        $_SESSION['mailuser'] = $mail;
        echo $resp;
        
        
    }
            

?>
