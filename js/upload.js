$(document).ready(function(){
    //mensajes transacciones
    formdata = false;
    formdata = new FormData();
    function limpiar(){
        $('.titunoticia').val('');
        $('.contentnoticia').val('');
         formdata = false;
         formdata = new FormData();
         $('.images').val('');
        $('.coverfile').css('background-image','none');
         $('.titunoticia').focus();
    }
    function msjError(msj){				
        $('#calmsj').html('<div class="iconerror sprites" ></div> '+msj);
        setTimeout('$("#covermsj").fadeOut(500);',4000);
    }
    function loader(msj){
        $("#covermsj").fadeIn(0);	
        $("#covermsj > .innermsj").fadeIn(0);
        $('#calmsj').html(msj);
    }
    function msjSucess(msj){
        $('#calmsj').html('<div class="iconsuccess sprites"></div> '+msj);
        setTimeout('$("#covermsj").fadeOut(500);',4000);
        
        limpiar();
    }
    //fin mensajes transacciones
    mouseOverAll = false; 	
    $('#caloader').live('mouseenter', function(){
        mouseOverAll = true; 
    }).live('mouseleave', function(){ 
        mouseOverAll = false; 
    });
    function revisarTag(nombre){
        error = true;
        $('.tag-elegir').each(function(){
            este = $(this);
            if(este.html() == nombre){
                if(este.hasClass('tag-noselected')){
                    este.removeClass('tag-noselected');
                    este.addClass('tag-selected');
                }
                tags();
                error = false;
            } 
         })
         return error;
    }
    //hash-event
    $('#nom-event').keyup(function(){
        nombre = $(this).val();
        if(trim(nombre) == ""){
            $('#hash-event').val('');
            $('.hashtag-corr').hide();
            return false;
        }
        $.post('/findbreak/function/event-response.php', {'comprobarHashTag':1, 'conEspacios':nombre}, 
        function(data){
            if(data.re == 1){
                $('#hash-event').val(data.limpio);
                hashCorrecto();
            }else{
                $('#hash-event').val(data.limpio);
                hashInCorrecto()
            }
        }, "json");
        
    })
    function hashCorrecto(){
         $('.hashtag-incorr').hide();
         $('.error-hashtag').hide();
         $('.hashtag-corr').show();
    }
    function hashInCorrecto(){
        $('.hashtag-corr').hide();
        $('.error-hashtag').html('Este hashtag ya está en uso');
        $('.hashtag-incorr').show();
        $('.error-hashtag').show();
    }
    $('#hash-event').keyup(function(){
        nombre = $(this).val();
        nombreSinEsp = nombre.replace(/ /g,"");
        $('#hash-event').val(nombreSinEsp)
        if(trim(nombreSinEsp) == ""){
            //mostrar mensaje de que no puede estar vacio

            return false;
        }
        $.post('/findbreak/function/event-response.php', {'comprobarHashTag2':1, 'sinEspacios':nombreSinEsp}, 
        function(data){
            if(data.re == 1){
                $('#hash-event').val(data.limpio);
                 hashCorrecto()
            }else{
                $('#hash-event').val(data.limpio);
                 hashInCorrecto()
            }
        }, "json");
       
    })
    $('#guardarevento').click(function(){
         guardar = true;
         $('.obligatorio').each(function(){
             valor = $(this).val();
             error = $(this).parent().find('.error-obligatorio');
             if(trim(valor) == ""){//si está vacío mostrar msj
                    guardar = false;
                    $(this).focus();
                     $('html, body').animate({
                         'scrollTop': $(this).offset().top - 90 + "px" 
                     },
                     {
                        duration:500,
                        easing:"swing"
                     }
                     );
                     error.fadeIn(200); 
                     return false;
             }else{
                      error.fadeOut(200); 
                 }
             
         })
         if(guardar){
            loader('Guardando Evento...');
            document.formularioevento.submit();
         }
         
    }); 
    $('#modificarevento').click(function(){
         guardar = true;
         $('.obligatorio').each(function(){
             valor = $(this).val();
             error = $(this).parent().find('.error-obligatorio');
             if(trim(valor) == ""){//si está vacío mostrar msj
                    guardar = false;
                    $(this).focus();
                     $('html, body').animate({
                         'scrollTop': $(this).offset().top - 90 + "px" 
                     },
                     {
                        duration:500,
                        easing:"swing"
                     }
                     );
                     error.fadeIn(200); 
                     return false;
             }else{
                      error.fadeOut(200); 
                 }
             
         })
         if(guardar){
            loader('Guardando Evento...');
            document.formularioevento.submit();
         }
         
    }); 
    $('#nuevo-tag-btn').click(function(){
        nuevotag = $('#nuevo-tag').val();
        if(trim(nuevotag) == ''){
            return false;
        }
        if(!revisarTag(nuevotag)){
            alert('La palabra que agregaste ya existe, por ende se agregó automáticamente')
            return false;
        }
        $.ajax({
                      type:"POST",
                      dataType:"html",
                      url:"/findbreak/function/event-response.php",
                      data:"nuevotag=1&nombre="+nuevotag,
                      success:function(data)
                      {
                          if(data == 1){
                            $('.content-tags').prepend('<div class="tag-elegir tag-selected">'+nuevotag+'</div>');
                            tags();
                          }
                      }
                  }); 
    })
    $('.mostrar-agre-tag').click(function(){
        $('.divmostrar-agre-tag').toggle();
        $('#nuevo-tag').focus();
        return false;
    })
    $('body').delegate('.tag-elegir','click',function(){
        este = $(this);
        if(este.hasClass('tag-noselected')){
            este.removeClass('tag-noselected');
            este.addClass('tag-selected');
        }else{
            este.removeClass('tag-selected');
            este.addClass('tag-noselected');
        }
        tags();
    });
     function tags(){
         tagsElegidos = '';
         $('.tag-elegir').each(function(){
            if($(this).hasClass('tag-selected')){
                tagsElegidos+= $(this).html()+' ';
            }
         })
         $('#tags-hidden').val(tagsElegidos)
     }
    $('#date-event').datepick({ 
        multiSelect: 999, monthsToShow: 1, dateFormat: 'yyyy-mm-dd'
    });
    
    function mostrarImagenSubida(source, este){
        var    img  = document.createElement('img');
        img.src = source;
        
        este.css('background-image','url('+source+')');
        este.css('background-size','cover');
        este.css('background-position','0px 0px');
    }
    $('body').delegate('#images-galerias','change',function(evt){
            var i = 0, len = this.files.length, img, reader, file;
            var este = $(this).parent();
                 //for( ; i < len; i++){
                    file = this.files[0];
                    //Una pequeña validación para subir imágenes
                    if(!!file.type.match(/image.*/)){
                        //Si el navegador soporta el objeto FileReader
                        if(window.FileReader){
                            reader = new FileReader();
                            //Llamamos a este evento cuando la lectura del archivo es completa
                            //Después agregamos la imagen en una lista
                            reader.onloadend = function(e){

                                mostrarImagenSubida(e.target.result, este);
                            };
                            //Comienza a leer el archivo
                            //Cuando termina el evento onloadend es llamado
                            reader.readAsDataURL(file);
                        }
                    }
         });
    
      
      
  usernameCorrecto = false;
  function comprobarCampos(){
          error = false;
          $('.item-publicar input').each(function(){
              valor = $(this).val();
              if(trim(valor) == ""){
                  error = true;
              }
          })
          return error;
      }
  $('#coverall').delegate('#guardarusuario','click',function()
  {
       if(comprobarCampos()){
           $('.todosloscampos .content-mensaje').html('Debes completar todos los campos');
           $('.todosloscampos').show();
           return false;
       }else{
           $('.todosloscampos').hide();
       }
       if(usernameCorrecto == false){
           return false;
       }
        var nomeuser = $('#nombre-usuario').val();
        var username = $('#user-name').val();
        var correousuario = $('#correo-usuario').val();
        var claveusuario = $('#clave-usuario').val();
//        alert("adads"); return;
                        $.ajax({
                                 dataType:"JSON",
                                 url : '/findbreak/function/users-response.php',
                                 type : 'POST',
                                 data : "guardaruser=1&nomuser="+nomeuser+"&username="+username+"&correousuario="+correousuario+"&claveusuario="+claveusuario, 
                                 success : function(res){                      
                                     //modificar la foto con el mail
                                     if(res == 1){
                                          $.ajax({
                                                    type: "POST",
                                                    dataType: "JSON",
                                                    url: "/findbreak/function/login-response.php",
                                                    data: "login=1&mail="+correousuario+"&pass="+claveusuario,
                                                    success : function (data)
                                                    {  
                                                        if(data.exito)
                                                            { 
                                                                if(data.usertype == 1){
                                                                  window.location.reload();//es usuario y recargo la página donde esté
                                                                }   
                                                            }
                                                    }
                                                })
                                     }else
                                        if(res == -5){
                                            $('.todosloscampos .content-mensaje').html("Lo sentimos, esta cuenta ya existe. ¿Te gustaría reclamar esta dirección de correo electrónico?");
                                             $('.todosloscampos').show();
                                         }
                                  }//success                
                              });
      
  })

      $('body').delegate('#user-name','keyup',function(e){
          username = $(this).val();
          if(username == ""){
              $('.username-incorr').hide();
              $('.username-corr').hide();
              return false;
          }
          $.post("/findbreak/function/users-response.php", {'comprobar-username':1,'username':username}, function(data){
                if(data == 1){//se puede
                    $('.username-corr').show();
                    $('.username-incorr').hide();
                    $('.error-username').hide();
                    usernameCorrecto = true;
                }else{
                    usernameCorrecto = false;
                    $('.todosloscampos').hide();
                    $('.username-incorr').show();
                    $('.username-corr').hide();
                    $('.error-username').show();
                    $('.error-username').html('Este nombre de usuario ya existe')
                } 
            }, "html");
      })
      
      
  //Aplicamos la subida de imágenes al evento change del input file
    $('body').delegate('#images-evento-upd','change',function(evt){
       
//        alert('a'); 
//        return false;
            var i = 0, len = this.files.length, img, reader, file;
            var este = $(this).parent();
                 //for( ; i < len; i++){
                    file = this.files[0];
                    //Una pequeña validación para subir imágenes
                    if(!!file.type.match(/image.*/)){
                        //Si el navegador soporta el objeto FileReader
                        if(window.FileReader){
                            reader = new FileReader();
                            //Llamamos a este evento cuando la lectura del archivo es completa
                            //Después agregamos la imagen en una lista
                            reader.onloadend = function(e){

                                mostrarImagenSubida(e.target.result, este);
                            };
                            //Comienza a leer el archivo
                            //Cuando termina el evento onloadend es llamado
                            reader.readAsDataURL(file);
                        }
                        //Si existe una instancia de FormData
                        if(formdata){
                            //Usamos el método append, cuyos parámetros son:
                                //name : El nombre del campo
                                //value: El valor del campo (puede ser de tipo Blob, File e incluso string)
                            formdata.append('images-evento-upd', file);
                              
                                var url = '../'+$(this).parent().attr('data-url');
                                var idEvento = $('#idevent').val();
                                var nombre = $(this).parent().attr('data-nombre');
                                var urlSinImg = $(this).parent().attr('data-urlsin');
//                                alert(url)
//                                alert(idEvento)
//                                alert(nombre)
//                                return false;
                                if(formdata){
                                    $.ajax({
                                       url : '/findbreak/function/uploadfoto.php',
                                       type : 'POST',
                                       data : formdata,
                                       processData : false, 
                                       beforeSend: function(){
                                                     loader('Subiendo foto nueva...');  
                                                   },
                                       contentType : false, 
                                       dataType: "JSON",
                                       success : function(res){
//                                                alert(res.exito); return false;
                                                if(res.exito){
                                                  
                                                    var fotoGr = res.nombrefotoGr;
                                                    $.ajax({
                                                        url : '/findbreak/function/event-response.php',
                                                        type : 'POST',
                                                        data : 'modificarFotos=1&idEvento='+idEvento+"&urlBorrar="+url+"&nombreBorrar="+nombre+"&fotoGr="+fotoGr,
                                                        success : function(res){
                                                                 
                                                            if(res == 1){
                                                                 este.attr('data-urlsin',urlSinImg);
                                                                 este.attr('data-url',urlSinImg+'/'+fotoGr);
                                                                 este.attr('data-nombre',fotoGr);
                                                                 msjSucess('Foto modificada con éxito');
                                                            }else{
                                                                 msjError('Error, error en guardar la foto cod: 2');
                                                            }
                                                        }                
                                                    });
                                                }else{
                                                    msjError('Error, vuelva a cargar la foto cod: 1');
                                                }
                                       }                
                                    });
                                }
                        }
                       
                    }
        });
              //Aplicamos la subida de imágenes al evento change del input file
    $('body').delegate('#images-evento-nueva','change',function(evt){
            var i = 0, len = this.files.length, img, reader, file;
            var este = $(this).parent();
                 //for( ; i < len; i++){
                    file = this.files[0];
                    //Una pequeña validación para subir imágenes
                    if(!!file.type.match(/image.*/)){
                        //Si el navegador soporta el objeto FileReader
                        if(window.FileReader){
                            reader = new FileReader();
                            //Llamamos a este evento cuando la lectura del archivo es completa
                            //Después agregamos la imagen en una lista
                            reader.onloadend = function(e){

                                mostrarImagenSubida(e.target.result, este);
                            };
                            //Comienza a leer el archivo
                            //Cuando termina el evento onloadend es llamado
                            reader.readAsDataURL(file);
                        }
                        //Si existe una instancia de FormData
                        if(formdata){
                            //Usamos el método append, cuyos parámetros son:
                                //name : El nombre del campo
                                //value: El valor del campo (puede ser de tipo Blob, File e incluso string)
                            formdata.append('images-evento-nueva', file);
                                
                                if(formdata){
                                     var idEvento = $('#idevent').val();
                                    $.ajax({
                                       url : '/findbreak/function/uploadfotonueva.php',
                                       type : 'POST',
                                       data : formdata,
                                       processData : false, 
                                       beforeSend: function(){
                                                     loader('Subiendo foto nueva...');  
                                                   },
                                       contentType : false, 
                                       dataType: "JSON",
                                       success : function(res){
//                                                   alert(res.exito); return false;
                                                if(res.exito){
                                                  
                                                    var fotoGr = res.nombrefotoGr;
                                                    $.ajax({
                                                        url : '/findbreak/function/event-response.php',
                                                        type : 'POST',
                                                        dataType:"json",
                                                        data : 'nuevaFoto=1&idEvento='+idEvento+"&fotoGr="+fotoGr,
                                                        success : function(res){
                                                                 
                                                            if(res.re == 1){
                                                                 este.attr('data-urlsin',res.urlSinImg);
                                                                 este.attr('data-url',res.urlSinImg+'/'+fotoGr);
                                                                 este.attr('data-nombre',fotoGr);
                                                                 msjSucess('Foto agregada con éxito');
                                                            }else{
                                                                 msjError('Error, error en guardar la foto cod: 2');
                                                            }
                                                        }                
                                                    });
                                                }else{
                                                    msjError('Error, vuelva a cargar la foto cod: 1');
                                                }
                                       }                
                                    });
                                }
                        }
                       
                    }
          // }
            
            //Por último hacemos uso del método proporcionado por jQuery para hacer la petición ajax
            //Como datos a enviar, el objeto FormData que contiene la información de las imágenes
            
        });
   function trim(cadena){
        // USO: Devuelve un string como el parámetro cadena pero quitando los espacios en blanco de los bordes.
        var retorno=cadena.replace(/^\s+/g,'');
        retorno=retorno.replace(/\s+$/g,'');
        return retorno;
        }
  
})

