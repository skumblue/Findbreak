<div id="content-login">
    <?php  
    if(!isset($_SESSION["userid"]))
    {
    ?>
        <div class="mensaje-publicarevent mjscoment" >Para publicar un anuncio debes logearte :)</div>
        <div class="content-inputlog">
            <div class="titulo-login1">
                <div class="msj-log1 mjscoment">Inicia sesión</div>
                <div class="msj-log2">o <a class="login-hover-com popup-registrate" href="#">crea una cuenta</a></div>
            </div>
            <input type="text" placeholder="Correo electronico" id="mail2">
            <input type="password" placeholder="Contraseña" id="pass2">
            <a href="#" class="botonblue" id="boton-login2">Entrar</a>
            <div id="nocoinciden" class="error-username">El mail o clave no coinciden</div>
            <div class="titulo-login2">
                <div class="msj-log3 mjscoment">O también puedes</div>
            </div>
            <a class="loginface-top login-face login-fb" href="#<?php //echo $loginUrl; ?>">
                <div id="loginbtn-fb"></div>
                <div class="txtfb">Ingresar con Facebook</div>
            </a>
            <a href="#" id="forgot-pass" class="popup-forgot">¿ Olvidó su contraseña ?</a>
        </div>
        
    
   <?php     
   }else
        {
            header("location:http://www.nowsup.com/publicar");
            
        }
?>                                    
</div>