<?php 
      $usuario = new usuario();
      $partid = explode('!', $_GET['id']);
      $usernameUrl = $partid[1];
      $usuariofound = $usuario->findforusername($usernameUrl);
      $userid = $usuariofound['_id'];
      $comentarioUser = new comentario();
      $publicaciones = $usuario->verCantidadPublicaciones($usuariofound['_id']);
      $event = new evento();
      $eventfound = $event->findforid('516aed144de8b4a003000003');
      $folder = (string)$eventfound['producido_por']['_id'];
      $url = '../images/productoras/'.$folder.'/'.$eventfound['fotos'][0];
      
        $buttonFriend = '<div id="seguiramigo-perfil" data-userid="'.$userid.'" class="boton-perfiluser botoncancel">Seguir</div>';
         
         //si está logeado buscar las posibles solicitudes
         $idSolicitado = $usuariofound['_id'];
         
         if(!empty($_SESSION['userid'])) //si está logeado
         {           
                $solicitante = $_SESSION['userid'];
                if($solicitante == new MongoId($idSolicitado))
                {//si me busco a mi mismo
                    $buttonFriend = '';//no quiero que aparesca el boton de amigos
                }else{//si busco a otra persona
                        $usuarioSig = $usuario->comprobarSiLoSigo($solicitante, $idSolicitado);
                        if(isset($usuarioSig['_id'])){ //lo sigo
                             $buttonFriend = '<div id="desseguiramigo-perfil" data-userid="'.$userid.'" class="boton-perfiluser botongreen">Siguiendo</div>'; 
                        }
                      // $buttonFriend = '<div id="send-req" class="button-friend">'.$valueButton.'</div>';
                }
         }else{//no esta logeado
                        $buttonFriend = '<div id="logeate-friend" class="boton-perfiluser botongreen">Inicia sesión</div>';
         }
?>

<div class="parte-left-parent">
            <div class="part-left">
                    <div class="part-left-right">
                      
<!--                        <div class="info-num">
                            <div class="item-info-num">
                                <div class="topinfo">Visitas</div>
                                <div class="num-topinfo">1000</div>
                            </div>
                            <div class="item-info-num item-info-num2">
                                <div class="topinfo">Comentarios</div>
                                <div class="num-topinfo">50</div>
                            </div>
                        </div>-->
                    </div>

                    <div class="part-left-cent parte-primera">
                       
                                <?php 
                                      //  $realizacion = $event->formatoFecha($eventfound['fecha_muestra'], $eventfound['hora_inicio']);
                                        $cantidadComentariosUser = $usuario->verCantidadComentarios($userid);
//                                        $textoComentario = '';
//                                        if($cantidadComentarios == 0){
//                                            $textoComentario = 'Se el primero en comentar!';
//                                        }elseif($cantidadComentarios == 1){
//                                            $textoComentario = 'Un comentario';
//                                        }else{
//                                            $textoComentario = '<span class="bold">'.$cantidadComentarios.'</span> Comentarios';
//                                        }
                                    ?>
                        <?php 
                        if(isset($_SESSION['userid'])){
                            if($_SESSION['userid'] == $userid)//mostrar mis recomendaciones
                             { ?>
                                <div class="titlediv">Actividades sugeridas</div>
                                    <div class="boxscrollEvents">
                                                        <!--<div class="eventsfavo">-->
                                                            <?php 
                                //                          if(isset($_SESSION['userid'])){
                                                        //$pop = $event->findpopular(4);
                                                        if(count($usuariofound['tags_buscados']) > 0){
                                                        $pop = $usuario->verEventosFavoritos($usuariofound['tags_buscados']);
                                                        foreach($pop as $dcto){
                                                            $fotoEvento = $event->verFoto($dcto['_id'], 1);
                                                                $cantidadComentarios = $event->verCantidadComentarios($dcto['_id']);
                                                                $textoComentario = '';
                                                                if($cantidadComentarios == 0){
                                                                    $textoComentario = 'Se el primero en comentar!';
                                                                }elseif($cantidadComentarios == 1){
                                                                    $textoComentario = 'Un comentario';
                                                                }else{
                                                                    $textoComentario = '<span class="bold">'.$cantidadComentarios.'</span> Comentarios';
                                                                }

                                                           // $url = '../images/productoras/'.$dcto['producido_por'].'/'.$dcto['foto'];
                                                        ?>
                                                        <div class="item-event " style="background-image:url(<?php echo $fotoEvento?>); background-size: cover" >   
                                   
                                                            <div class="info-event">
                                                               <a class="tit-eventcerca" href="/break/<?php echo $dcto['hash'];?>"><?php echo $dcto['nombre']; ?></a> 

                                                           </div>
                                                       </div>
                                                        <?php 

                                                        } }?>
                                                    <!--</div>-->
                                </div>
                        
                   <?php }
                   
                   }else{
                        ?>
                               
                        
                        <?php }?>
                    </div>

                    <div class="part-left-cent">
                            <div class="titlediv">Anuncios más visitados</div>
                                    <div class="boxscrollEvents">
                                                        <!--<div class="eventsfavo">-->
                                                            <?php 
                                //                          if(isset($_SESSION['userid'])){
                                                        //$pop = $event->findpopular(4);
                                                        
                                                        $pop = $event->findpopular(4);
                                                        foreach($pop as $dcto){
                                                            $fotoEvento = $event->verFoto($dcto['_id'],1);
                                                                $cantidadComentarios = $event->verCantidadComentarios($dcto['_id']);
                                                                $textoComentario = '';
                                                                if($cantidadComentarios == 0){
                                                                    $textoComentario = 'Se el primero en comentar!';
                                                                }elseif($cantidadComentarios == 1){
                                                                    $textoComentario = 'Un comentario';
                                                                }else{
                                                                    $textoComentario = '<span class="bold">'.$cantidadComentarios.'</span> Comentarios';
                                                                }

                                                           // $url = '../images/productoras/'.$dcto['producido_por'].'/'.$dcto['foto'];
                                                        ?>
                                                       <div class="item-event " style="background-image:url(<?php echo $fotoEvento?>); background-size: cover" >   
                                   
                                                            <div class="info-event">
                                                               <a class="tit-eventcerca" href="/break/<?php echo $dcto['hash'];?>"><?php echo $dcto['nombre']; ?></a> 

                                                           </div>
                                                       </div>
                                                        <?php 

                                                        } ?>
                                                    <!--</div>-->
                                </div>
                    </div>


             </div>
    
   
    
    <div class="part-bottom">
        <div class="publicidad-media"></div>      
    </div>
</div>
<div class="parte-der">
    <div class="part-right divtrans2">
          <div class="foto-user" style="background-size: cover; background-image: url(<?php echo $usuariofound['foto']['gr'] ?>)"></div>
          
          <div class="bloque-info info-event-item">
              <div class="title-user tit-gray"><?php echo ucwords($usuariofound['nombre']) ?></div>
              <div class="username">@<?= $usuariofound['username']?></div>
              <div class="info-num">
                    <div class="item-info-num">
                        <div class="topinfo">Comentarios</div>
                        <div id="totalComent" class="num-topinfo"><?= $cantidadComentariosUser?></div>
                    </div>
                    <div class="item-info-num">
                        <div class="topinfo">Seguidores</div>
                        
                        <div id="num-seguidores" class="num-topinfo">
                            <?php if(isset($usuariofound['seguidores']))
                                        echo count($usuariofound['seguidores']);
                                   else
                                       echo 0;
                            ?>
                        
                        </div>
                    </div>
                    <div class="item-info-num">
                        <div class="topinfo">Siguiendo</div>
                        <div class="num-topinfo">
                            <?php if(isset($usuariofound['siguiendo']))
                                        echo count($usuariofound['siguiendo']);
                                   else
                                       echo 0;
                            ?>
                        
                        </div>
                    </div>
                  <div class="item-info-num item-info-num2">
                      <div class="topinfo">Publicaciones</div>
                      <div class="num-topinfo">
                          <?= $publicaciones ?>                       
                      </div>
                  </div>
              </div>
              <!--redes sociales-->
                <div class="fb-like" data-href="http://www.nowsup.com/<?= $_GET['id'] ?>" data-send="false" data-width="450" data-show-faces="true" data-font="arial" data-colorscheme="light"></div>
              <!--redes sociales-->
              <!--<div class="info-num moreinfouser"></div>-->
          </div>
          <!--<div class="tit tit1">Comenta el evento</div>-->
          <?= $buttonFriend?>
          <div class="part-right divtrans3">
                 <?php if(isset($_SESSION['userid'])){ 
                         if($_SESSION['userid'] == $userid)
                         {
                     ?>
                 
                            <div  class="coments">
                                <input type="hidden" id="iduser" value="<?php echo $userid ?>"/>
        <!--                        <input type="hidden" id="hashevent" value="<?ph//p echo $eventfound['hash'] ?>"/>-->
                                <div class="input-transcom">
                                    <!--<div class="hash"><?php //echo $eventfound['hash']?></div>-->
                                    <textarea id="hasheventos" class="hash" placeholder="menciona a un evento #" style="display:none"></textarea>
                                    <div class="eventosCitar"></div>


                                    <div id="overcoment">
                                      <textarea class="textoajustable" id="coment" placeholder="¿Qué está pasando?"></textarea>
                                    </div>
                    <!--                <div id="citasHidden"></div>
                                    <div class="citasHiddenReservas"></div>-->
                                    <div id="replica"></div>
                                </div>
                                <div class="showfocuscom">
                                 <!--<div class="divcitar">@</div>-->
                                 <div class="amigosCitar"></div>
                                 <input type="button" class="botonblue" id="btn-comentar-puser" value="Comentar" />
                                </div>

                            </div>
                    <?php }
                    
                    }
                      else{ //si no esta logueado no puedo comentar ?>  
                    <div  class="coments-nolog">
                         <input type="hidden" id="idevent" value="<?php echo $_GET['id'] ?>"/>
                         <input type="hidden" id="hashevent" value="<?php echo '#'.$eventfound['hash'] ?>"/>
                        <div class="advert mjscoment">
                            Para comentar el evento debes <a class="login-hover login-hover-com" href="#">Iniciar sesión</a> ó
                            <a class="paracoment login-face login-fb" href="<?php echo ''; ?>">
                                <div id="loginbtn-fb"></div>
                                <div class="txtfb">Ingresar con Facebook</div>
                            </a>
                        </div>
                    </div>
                     <?php } ?>
         
        <div class="list boxscroll">
            
                <?php 
                
                $comentarios = $comentarioUser->verMisComentarios($userid);
                $numComent = 0;
                foreach($comentarios as $dcto){
                     
                     $realizacion = $comentarioUser->verFecha($dcto['fechaMuestra']);
                     $useridComent = $dcto['_userId'];
                ?>
                <div data-num="<?= $numComent ?>" class="itemcoment">
                    <div class="line"></div>
                    <div class="bloq1" style="background: url('<?php echo $usuariofound['foto']['pe']?>') no-repeat"></div>
                    <div class="bloq2">
                        <div class="titu-usercom">
                            <a href="/!<?php echo $dcto['userName']?>" class="nomusercom tit-gray"><?php echo $dcto['nombreUsuario'] ?></a>
                            <spam class="username usernamecom">@<?php echo $dcto['userName']?></spam>
                        </div>
                        <div class="comentuser">
                            
<!--                              <a href="/findbreak/break/<?php //echo $dcto['_eventId'];?>" class="hashlink"><?php //echo $eventfound['hash']?></a>-->
                                                           <?php echo $dcto['comentario'] ?>

                        </div>
                    </div>
                    <div class="bloq3">
                        
                        <div class="hacecuant">
                            <?php echo $realizacion;
                            ?>
                        </div>
                        <?php 
                        if(isset($_SESSION['userid'])){
                            if($useridComent == $_SESSION['userid']){?>
                                <div data-id="<?php echo $dcto['_id'];?>" id="delcoment" class="aparececom">Eliminar</div>
           
                            <?php }else{?>
                                <div data-id="<?php echo $dcto['_id'];?>" id="compartircoment" class="aparececom">Compartir</div>
                            <?php }
                             }else{?>
                                <div data-id="<?php echo $dcto['_id'];?>" id="compartircoment" class="aparececom">Compartir</div>
                          <?php }?>
                           
                    </div>
                </div>
            
                <?php $numComent++;}
                $comentRestantes = $cantidadComentariosUser - $numComent; //ultimo = limit
                
                if($comentRestantes > 0){
                ?>
                
                <a  href="#" class="leermas-comentuser readmorecoment">Ver más comentarios</a>
                <?php } ?>
            </div>
          </div>
    </div>
   
</div>
<div class="publicidad-large"></div>
<script type="text/javascript" src="js/userprofile.js"></script>
 
