<?php 
      $usuario = new usuario();
      $comentarioEvent = new comentario();
      $event = new evento();
      $eventfound = $event->findforhash($_GET['id']);
      
      $pagar = new usuarioRelacional();
      $visitasEvento = $eventfound['visitas'];
      $folder = (string)$eventfound['producido_por']['_id'];
      $url = '/images/anuncios/'.$eventfound['fotos'][0]['gr'];
        if(isset($_SESSION['username'])){  
                $userid = $_SESSION['userid'];
                $tags = $eventfound['tags'];
                $re = $usuario->guardarTagsBuscados($userid, $tags);
                $re2 = $usuario->guardarHistorial($eventfound['_id'], $eventfound['fotos'][0]['pe'], $eventfound['nombre'],$eventfound['producido_por']['_id'],$userid);
                $visitaResp = $event->registrarVisita($eventfound['_id']); // sumar visita y se guardo la hora de cuando entró
                $visitasEvento+= $visitaResp;
        }
?>
 <div id="fb-root"></div>
    <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/es_LA/all.js#xfbml=1";
    fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    
</script>

<script type="text/javascript">
    window.___gcfg = {lang: 'es'};
    (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
</script>

<div class="more-fotos">
             <?php
               
                   $fotos = $eventfound['fotos'];
                   
                   if(count($eventfound['fotos']) >= 1){
                       for($i=0; $i<count($eventfound['fotos']) ; $i++){
                           
                           $url = '/images/anuncios/'.$fotos[$i]['gr'];
//                           $url = 'http://cdn.lifeboxset.com/wp-content/uploads/2010/09/millencolin-flyer.jpg';
                           ?>
                       <div class="foto-event-small" style="background-size: cover; background-image: url(<?php echo $url ?>)"></div>
                <?php
                     }
                   }
                   $urlPrincipal = '/images/anuncios/'.$fotos[0]['gr'];
               ?>
</div>
<div class="content-perfilevento">
    <?php 
     if(!empty($_SESSION['userid']))
                {
                $mio = false;
                if($_SESSION['userid'] == $eventfound['producido_por']['_id'])
                {
                    $mio = true;
                    ?> 
                            <a href="<?= PATH?>editar-evento/<?= $eventfound['_id'] ?>" id="editar-mievento" class="botongreen">Editar anuncio</a>
                            
                        <?php
                        
//                        if( $visitasEvento%10000 <= 0 ) 
//                        {
//                            if($pagar->VerPiso($_SESSION['userid'], $eventfound['_id']) == 1  ){
//                                $pagar->CambiarPiso($_SESSION['userid'], $eventfound['_id']);
//                                $pagar->PagoVisitas($_SESSION['userid']); //Esto depende del piso, en este caso piso 1
//                            }
//                                $mensajePremio = "Estas en el piso 2, te faltan ".(24000-$visitasEvento)." visitas para los 1390 pesos :)";
//                        }
                      // ES MI ES MI O ES MIO EMSI O EMOS MEISMO ESMI ES MIO
//                               
                }
                else
                {
                    
                    ?> 
                        
                                    <div id="denunciar-evento" class="botonred" >  
                                            Denunciar 
                                        <input type="hidden" class="denid" value="<?php echo $eventfound['nombre'] ?>"/> 
                                        <input type="hidden" class="denuser" value="<?php echo $_SESSION['username'] ?>"/> 
                                        <div style="display:none" id="enviar-denuncia">
                                            <textarea id="text-denuncia">      </textarea>
                                            <input type="button" id="boton-denuncia" value="Enviar" data-idevent='<?php echo $eventfound['_id']?>' data-userid='<?php echo $_SESSION['userid'] ?>'>
                                        </div>
                                        
                                    </div>  
                            
                            
                        <?php
                }
                }
                
                ?>
    
    <div id="content-mapaenvento">
        <div id='map_establecimientos' style='width:100%; height:250px;'></div>
    </div>
 
<div class="parte-left-parent">
            <div class="part-left divtrans">
                    <div class="part-left-right">
                        <div class="foto-event" style="background-size: cover; background-image: url(<?php echo trim($urlPrincipal) ?>)"></div>
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

                    <div class="part-left-cent">
                        <div class="title-event tit"><?php echo $eventfound['nombre']; ?></div>
                        <div class="inner-eveninfo info-eventcerca">
                                <?php 
                                        
                                        $cantidadComentarios = $event->verCantidadComentarios($eventfound['_id']);
                                        $textoComentario = '';
                                        if($cantidadComentarios == 0){
                                            $textoComentario = 'Se el primero en comentar!';
                                        }elseif($cantidadComentarios == 1){
                                            $textoComentario = 'Un comentario';
                                        }else{
                                            $textoComentario = '<span class="bold">'.$cantidadComentarios.'</span> Comentarios';
                                        }
                                    ?>
                              
                               
                                <div class="info-event-item">
                                    <div id="dondeevent-prof"></div>
                                    <div class="info-dire"><?php  echo $eventfound['direccion'];?></div>
                                    <a href="#" class="verMapaEvento hashlink">ver en mapa</a>
                                </div>     
                               
                                <?php 
                                    $idMongoUsuario = new MongoId($eventfound['producido_por']['_id']);
                                    $nombreproductora = $usuario->verNombre($idMongoUsuario);
                                    $usernameProductora = $usuario->verUserName($idMongoUsuario);
                                ?>
                                <div  class="info-event-item">
                                  <div id="precioevent-prof"></div>
                                  <span class="producidoPor">Publicado por: </span>
                                  <a style="float:left; margin-top: 0px; " class="hashlink" href="/!<?= $usernameProductora['username']?>">
                                    <?php echo $usernameProductora['username'] ?>
                                  </a>
                                   
                                  
                                </div>
                                <div class="info-event-item">
                                    <div class="item-visita">
                                        <div id="visitavent-prof"></div>
                                        <span>Visto por </span>
                                        <span class="bold"><?php echo $visitasEvento?></span>
                                    </div>
                                    <div class="item-visita">
                                        <div id="comentaevent-prof"></div>
                                        <div class="textoComentario"><?php echo $textoComentario?> </div>
                                        <input type="hidden" id="totalComent" value="<?= $cantidadComentarios?>"/>
                                    </div>
                                </div>
                                
                        </div>
                    </div>

                    <div class="part-left-lf">
                        <!--para redes sociales-->
                                <div class="redes-event">

                                           <!--<b cond='data:blog.pageType == &quot;item&quot;'>-->
                                            
                                            <a id="boton-compartirfb" class="btn icons-fb" data-id="<?= $_GET['id']?>"
                                                onclick="window.open(this.href,this.target, 'width=600, height=400'); return false;"
                                                href='http://www.facebook.com/sharer.php?s=100&p[url]=http://www.nowsup.com/break/<?= $_GET['id']?>&p[images][0]=http://www.nowsup.com<?= $urlPrincipal?>&p[title]=<?= $eventfound['nombre'] ?>&p[summary]=<?= $page_description?> :) !' target="_blank">
                                                 <div id="loginbtn-fb"></div> 
                                                 <div class="txtfb">Compartir</div>
                                             </a>
                                            <a id="boton-compartirfb" href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.nowsup.com/break/<?= $_GET['id']?>" data-hashtags="<?= $_GET['id']?>">Tweet</a>
                                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                                            <div id="boton-gplus" class="g-plusone" data-size="medium" data-annotation="none"></div>
                                </div>
                                           <!--</b>-->
                           <!--redes sociales-->
                        <div id="icondescrip"></div>
                        <?php 
                                $c = 0;
                                foreach(str_word_count($eventfound['descripcion'],1) as $w){
                                    $c+= strlen($w);
                                }
                        ?>
                        <div class="descripcion-event"><?php echo nl2br($eventfound['descripcion'])?></div>
                        <div class="leer-masevent">
                            <?php 
                                    if($c > 100)//alcanza en todo el cuadro
                                  {
                                       echo '<a href="#" class="readmore">Leer más...</a>';
                                  }
                            ?>
                        </div>
                        <div class="masinfo-event"></div>
                    </div>


             </div>
    
    <div class="cercanos">
                <?php 
                $videoCompleto = $eventfound['redes'][2];//
                $youtube = '';
                if($videoCompleto != ''){
                    $videoPartes = explode('=', $videoCompleto);
                    if(count($videoPartes) > 1){
                        $video = $videoPartes[1];
                    }else{
                        $video = '';//link roto poner video fbk
                    }
                    $youtube = '<iframe width="100%" height="100%" src="//www.youtube.com/embed/'.$video.'" frameborder="0" allowfullscreen></iframe>';
                
                     echo ' <div id="videoEvento">'.$youtube.'</div>';
                }
                ?>
               
         
    </div>
    
<!--    <div class="part-bottom">
        <div class="publicidad-media"></div>      
    </div>-->
</div>
<div class="parte-der">
    <div class="part-right divtrans">
         <!--<div class="tit tit1">Comenta el evento</div>-->
        <?php if(isset($_SESSION['userid'])){ ?>
        <div  class="coments">
            <input type="hidden" class="idevent" value="<?php echo $eventfound['_id'] ?>"/>
            <input type="hidden" id="hashevent" value="<?php echo '#'.$eventfound['hash'] ?>"/>
            <div class="input-transcom">
                <div class="hash"><?php echo '#'.$eventfound['hash']?></div>
                <div id="overcoment">
                  <textarea class="textoajustable" id="coment" placeholder="¿Qué opinas?"></textarea>
                </div>
<!--                <div id="citasHidden"></div>
                <div class="citasHiddenReservas"></div>-->
                <div id="replica"></div>
            </div>
            <div class="showfocuscom">
             <!--<div class="divcitar">@</div>-->
             <div class="amigosCitar"></div>
             <input type="button" class="botonblue" id="btn-comentar" value="Comentar" />
            </div>
            
        </div>
        <?php }
          else{ //si no esta logueado no puedo comentar ?>  
        <div  class="coments-nolog">
             <input type="hidden" id="idevent" value="<?php echo $_GET['id'] ?>"/>
             <input type="hidden" id="hashevent" value="<?php echo '#'.$eventfound['hash'] ?>"/>
            <div class="advert mjscoment">
                Para comentar el anuncio debes <a class="login-hover login-hover-com" href="#">Iniciar sesión</a> ó
                <a class="paracoment login-face login-fb" href="<?php echo '#'; ?>">
                    <div id="loginbtn-fb"></div>
                    <div class="txtfb">Ingresar con Facebook</div>
                </a>
            </div>
        </div>
         <?php } ?>
         
        <div class="list boxscroll">
            
                <?php 
                
                $comentarios = $comentarioEvent->findforid($eventfound['_id']);
                $numComent = 0;
                foreach($comentarios as $dcto){
                 
                     $userFoto = $usuario->verFoto($dcto['_userId']);
                     $realizacion = $comentarioEvent->verFecha($dcto['fechaMuestra']);
                     $useridComent = $dcto['_userId'];
//                     $mostrarHash = '';
//                     if(isset($dcto['eventos_mencionados']) != null){
//                        $mencionado = $comentarioEvent->verCitaEvento( $eventfound['_id'], $dcto['eventos_mencionados']);
//                        if(!$mencionado)
//                         $mostrarHash = $eventfound['_id'];
//                     }
                ?>
                <div data-num="<?= $numComent ?>" class="itemcoment">
                    <div class="line"></div>
                    <div class="bloq1" style="background: url('<?php echo $userFoto['foto']['pe']?>') no-repeat"></div>
                    <div class="bloq2">
                        <div class="titu-usercom">
                            <a href="/!<?php echo $dcto['userName']?>" class="nomusercom tit-gray"><?php echo ucwords($dcto['nombreUsuario'])?></a>
                            <spam class="username usernamecom">@<?php echo $dcto['userName']?></spam>
                        </div> 
                        <div class="comentuser">
<!--                            
                              <a href="/findbreak/break/<?php //echo $dcto['_eventId'];?>" class="hashlink"><?php//s echo $mostrarHash?></a>-->
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
                $comentRestantes = $cantidadComentarios - $numComent; //ultimo = limit
                if($comentRestantes > 0){
                ?>
                
                <a  href="#" class="leermas-coment readmorecoment">Ver más comentarios</a>
                <?php } ?>
            </div>
         
    </div>
   <div class="pub-ladoder">
       <script type="text/javascript"><!--
        google_ad_client = "ca-pub-3584063439345600";
        /* banner_right2 */
        google_ad_slot = "2905630576";
        google_ad_width = 120;
        google_ad_height = 600;
        //-->
        </script>
        <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
        </script>
   </div>
    <div id="list-similares" class="part-right divtrans">
        <div class="titlediv">Actividades similares</div>
        <div class="boxscroll boxscrollEvents">
                            <!--<div class="eventsfavo">-->
                                <?php 
//                                if(isset($_SESSION['userid'])){
                                $similares = $event->similares($eventfound['_id'], $eventfound['tags'],5);
                                foreach($similares as $dcto){
                                    $fotoEvento = $event->verFoto($dcto['_id'], 1);
                                   // $realizacion = $event->formatoFecha($dcto['fecha_muestra'], $dcto['hora_inicio'], 1);
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

     <div id="list-similares" class="part-right divtrans">
        <div class="titlediv">Anuncios más visitados</div>
        <div class="boxscrollEvents">
                            <!--<div class="eventsfavo">-->
                                <?php 
//                                if(isset($_SESSION['userid'])){
                                $pop = $event->findpopular(4);
                                foreach($pop as $dcto){
                                    $fotoEvento = $event->verFoto($dcto['_id'], 1);
//                                    $realizacion = $event->formatoFecha($dcto['fecha_muestra'], $dcto['hora_inicio'], 1);
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
<div class="publicidad-large">
     <script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- banner_bottom -->
        <ins class="adsbygoogle"
        style="display:inline-block;width:728px;height:90px"
        data-ad-client="ca-pub-3584063439345600"
        data-ad-slot="4857028578"></ins>
        <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
</div>





<?php 
    $loc = $eventfound['loc'];
?>
  
 <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script> <!--Cargamos la API de Google Maps-->
 <script type="text/javascript" src="js/evento.js"></script>
 
 
<input id="lat-event" type="hidden" value="<?php echo $loc[0] //lat ?>"/>
<input id="lng-event" type="hidden" value="<?php echo $loc[1] //lng ?>"/>
<div class="est-hidden">
   
</div>
</div>
