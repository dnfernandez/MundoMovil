
//Metodos para mostrar formularios

function mostrar_busqueda(){
	document.getElementById("formBusqueda").style.display="block";
	document.getElementById("fade").style.display="block";
	document.getElementById("formLogin").style.display="none";
	document.getElementById("formRegistro").style.display="none";
	document.getElementById("formComentNoticia").style.display="none";
}

function mostrar_login(){
	document.getElementById("formLogin").style.display="block";
	document.getElementById("fade").style.display="block";
	document.getElementById("formBusqueda").style.display="none";
	document.getElementById("formRegistro").style.display="none";
	document.getElementById("formComentNoticia").style.display="none";
}

function mostrar_registro(){
	document.getElementById("formRegistro").style.display="block";
	document.getElementById("fade").style.display="block";
	document.getElementById("formBusqueda").style.display="none";
	document.getElementById("formLogin").style.display="none";
	document.getElementById("formComentNoticia").style.display="none";
}

function mostrar_enviar_mensaje(usuario){
	document.getElementById("formMensajeEnv").style.display="block";
	document.getElementById("idDestinatario").innerHTML = usuario;
	document.getElementById("idDesHid").innerHTML = "<input type='hidden' name='destinatario' value='"+usuario+"' >";
	document.getElementById("fade").style.display="block";
	document.getElementById("formLogin").style.display="none";
	document.getElementById("formRegistro").style.display="none";
	document.getElementById("formBusqueda").style.display="none";
}

function mostrar_comentario_noticia(comentario){
	document.getElementById("formComentNoticia").style.display="block";
	if(comentario){
		document.getElementById("idRespComNot").innerHTML = "<input type='hidden' name='idComRes' value='"+comentario+"' >";
	}
	document.getElementById("fade").style.display="block";
	document.getElementById("formLogin").style.display="none";
	document.getElementById("formRegistro").style.display="none";
	document.getElementById("formBusqueda").style.display="none";
}

function mostrar_respuesta_comentario(){
	document.getElementById("comentarioRespuesta").style.display="block";
}

function mostrar_panel_creacion(){
	document.getElementById("panelAdmin").style.display="block";
	document.getElementById("panelAdminMos").style.display="none";
}

function mostrar_crear_foro(){
	document.getElementById("formCrearForo").style.display="block";
	document.getElementById("fade").style.display="block";
}

function mostrar_modificar_foro(tit,desc){
	document.getElementById("formModificarForo").style.display="block";
	document.getElementById("fade").style.display="block";
	
	document.getElementById("inputModForo").value=tit;
	document.getElementById("textareaModForo").innerHTML=desc;
}

function mostrar_crear_respuesta(cita){
	document.getElementById("formCrearRespuesta").style.display="block";
	document.getElementById("fade").style.display="block";
	if(cita){
		CKEDITOR.instances['textareaRespuesta'].setData('<blockquote>'+cita+'</blockquote><p>&nbsp;</p>');
	}else{
		CKEDITOR.instances['textareaRespuesta'].setData('');
	}
}

//Metodos para ocultar formularios

function ocultar_busqueda(){
	document.getElementById("formBusqueda").style.display="none";
	document.getElementById("fade").style.display="none";
}

function ocultar_login(){
	document.getElementById("formLogin").style.display="none";
	document.getElementById("fade").style.display="none";
}

function ocultar_registro(){
	document.getElementById("formRegistro").style.display="none";
	document.getElementById("fade").style.display="none";
	document.getElementById("imgPerfil").src = "./images/perfil.jpg";
}

function ocultar_enviar_mensaje(){
	document.getElementById("formMensajeEnv").style.display="none";
	document.getElementById("fade").style.display="none";
}

function ocultar_comentario_noticia(){
	document.getElementById("formComentNoticia").style.display="none";
	document.getElementById("fade").style.display="none";
}

function ocultar_respuesta_comentario(){
	document.getElementById("comentarioRespuesta").style.display="none";
}

function ocultar_panel_creacion(){
	document.getElementById("panelAdmin").style.display="none";
	document.getElementById("panelAdminMos").style.display="block";
}

function ocultar_crear_foro(){
	document.getElementById("formCrearForo").style.display="none";
	document.getElementById("fade").style.display="none";
}

function ocultar_modificar_foro(){
	document.getElementById("formModificarForo").style.display="none";
	document.getElementById("fade").style.display="none";
}

function ocultar_crear_respuesta(){
	document.getElementById("formCrearRespuesta").style.display="none";
	document.getElementById("fade").style.display="none";
}

//Funcion que previsualiza imagen de perfil cargada en registro
function archivo(evt) {
      var files = evt.target.files;
       
      for (var i = 0, f; f = files[i]; i++) {         
           //Solo admitimos imágenes.
           if (!f.type.match('image.*')) {
                continue;
           }
       
           var reader = new FileReader();
           
           reader.onload = (function(theFile) {
               return function(e) {
               // Creamos la imagen.
					document.getElementById("imgPerfil").src = [, e.target.result,].join('');
               };
           })(f);
 
           reader.readAsDataURL(f);
       }
}
             
document.getElementById('files').addEventListener('change', archivo, false);

// Funcion que evita que los paneles de la publicidad sobrepasen o queden ocultados por el footer

$(window).scroll(function() {
   if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
		var heightObj = document.getElementById("bloqNot");
		document.getElementById("idPanelPubli").style.height=heightObj.offsetHeight+"px";
		$('#panel-inf1').addClass('fixed_class');
		$('#panel-inf1').removeClass('panel-fixed');
   }else{
		$('#panel-inf1').addClass('panel-fixed');
		$('#panel-inf1').removeClass('fixed_class');
   }
});


//Funcion de confirmación de eliminación

function preguntarEliminar(enlace){
	eliminar=confirm("¿Eliminar elemento?");
	if (eliminar){
		window.location.href = enlace;
	}
}

//Funcion que crea boton de scroll to top cuando se hace scroll vertical

if ($(window).height()>200 ) {
    $('#top-link-block').removeClass('hidden').affix({
        offset: {top:100}
    });
}





























