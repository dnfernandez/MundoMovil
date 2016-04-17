//Metodos para mostrar formularios

function mostrar_busqueda() {
    document.getElementById("formBusqueda").style.display = "block";
    document.getElementById("fade").style.display = "block";
    document.getElementById("formLogin").style.display = "none";
    document.getElementById("formRegistro").style.display = "none";
    document.getElementById("formComentNoticia").style.display = "none";
    document.getElementById("formComentTutorial").style.display = "none";
}

function mostrar_login() {
    document.getElementById("formLogin").style.display = "block";
    document.getElementById("fade").style.display = "block";
    document.getElementById("formBusqueda").style.display = "none";
    document.getElementById("formRegistro").style.display = "none";
    document.getElementById("formComentNoticia").style.display = "none";
    document.getElementById("formComentTutorial").style.display = "none";
}

function mostrar_registro() {
    document.getElementById("formRegistro").style.display = "block";
    document.getElementById("fade").style.display = "block";
    document.getElementById("formBusqueda").style.display = "none";
    document.getElementById("formLogin").style.display = "none";
    document.getElementById("formComentNoticia").style.display = "none";
    document.getElementById("formComentTutorial").style.display = "none";
}

function mostrar_enviar_mensaje(id_usuario, usuario) {
    document.getElementById("formMensajeEnv").style.display = "block";
    document.getElementById("idDestinatario").innerHTML = usuario;
    document.getElementById("idDesHid").innerHTML = "<input type='hidden' name='id_usuario_dest' value='" + id_usuario + "' >";
    document.getElementById("fade").style.display = "block";
    document.getElementById("formLogin").style.display = "none";
    document.getElementById("formRegistro").style.display = "none";
    document.getElementById("formBusqueda").style.display = "none";
}

function mostrar_comentario_noticia(comentario) {
    document.getElementById("formComentNoticia").style.display = "block";
    if (comentario) {
        document.getElementById("idRespComNot").innerHTML = "<input type='hidden' name='idComRes' value='" + comentario + "' >";
    }
    document.getElementById("fade").style.display = "block";
}

function mostrar_comentario_tutorial(comentario) {
    document.getElementById("formComentTutorial").style.display = "block";
    if (comentario) {
        document.getElementById("idRespComTut").innerHTML = "<input type='hidden' name='idComRes' value='" + comentario + "' >";
    }
    document.getElementById("fade").style.display = "block";
}

function mostrar_respuesta_comentario(id_comentario) {
    document.getElementById("comentarioRespuesta" + id_comentario).style.display = "block";
}

function mostrar_panel_creacion() {
    document.getElementById("panelAdmin").style.display = "block";
    document.getElementById("panelAdminMos").style.display = "none";
}

function mostrar_crear_foro() {
    document.getElementById("formCrearForo").style.display = "block";
    document.getElementById("fade").style.display = "block";
}

function mostrar_modificar_foro(tit, desc) {
    document.getElementById("formModificarForo").style.display = "block";
    document.getElementById("fade").style.display = "block";

    document.getElementById("inputModForo").value = tit;
    document.getElementById("textareaModForo").innerHTML = desc;
}

function mostrar_crear_respuesta(cita) {
    document.getElementById("formCrearRespuesta").style.display = "block";
    document.getElementById("fade").style.display = "block";
    if (cita) {
        CKEDITOR.instances['textareaRespuesta'].setData('<blockquote>' + cita + '</blockquote><p>&nbsp;</p>');
    } else {
        CKEDITOR.instances['textareaRespuesta'].setData('');
    }
}

//Metodos para ocultar formularios

function ocultar_busqueda() {
    document.getElementById("formBusqueda").style.display = "none";
    document.getElementById("fade").style.display = "none";
}

function ocultar_login() {
    document.getElementById("formLogin").style.display = "none";
    document.getElementById("fade").style.display = "none";
}

function ocultar_registro() {
    document.getElementById("formRegistro").style.display = "none";
    document.getElementById("fade").style.display = "none";
    document.getElementById("imgPerfil").src = "./images/perfil.jpg";
}

function ocultar_enviar_mensaje() {
    document.getElementById("formMensajeEnv").style.display = "none";
    document.getElementById("fade").style.display = "none";
}

function ocultar_comentario_noticia() {
    document.getElementById("formComentNoticia").style.display = "none";
    document.getElementById("fade").style.display = "none";
}

function ocultar_comentario_tutorial() {
    document.getElementById("formComentTutorial").style.display = "none";
    document.getElementById("fade").style.display = "none";
}

function ocultar_respuesta_comentario(id_comentario) {
    document.getElementById("comentarioRespuesta" + id_comentario).style.display = "none";
}

function ocultar_panel_creacion() {
    document.getElementById("panelAdmin").style.display = "none";
    document.getElementById("panelAdminMos").style.display = "block";
}

function ocultar_crear_foro() {
    document.getElementById("formCrearForo").style.display = "none";
    document.getElementById("fade").style.display = "none";
}

function ocultar_modificar_foro() {
    document.getElementById("formModificarForo").style.display = "none";
    document.getElementById("fade").style.display = "none";
}

function ocultar_crear_respuesta() {
    document.getElementById("formCrearRespuesta").style.display = "none";
    document.getElementById("fade").style.display = "none";
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

        reader.onload = (function (theFile) {
            return function (e) {
                // Creamos la imagen.
                document.getElementById("imgPerfil").src = [, e.target.result,].join('');
            };
        })(f);

        reader.readAsDataURL(f);
    }
}

document.getElementById('files').addEventListener('change', archivo, false);

// Funcion que evita que los paneles de la publicidad sobrepasen o queden ocultados por el footer

$(window).scroll(function () {
    if ($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
        var heightObj = document.getElementById("bloqNot");
        var panel = document.getElementById("idPanelPubli");
        if (heightObj.offsetHeight > 600) {
            panel.style.height = heightObj.offsetHeight + "px";
            $('#panel-inf1').addClass('fixed_class');
            $('#panel-inf1').removeClass('panel-fixed');
        }
    } else {
        $('#panel-inf1').addClass('panel-fixed');
        $('#panel-inf1').removeClass('fixed_class');
    }
});


//Funcion de confirmación de eliminación

function preguntarEliminar(enlace) {
    eliminar = confirm("¿Eliminar elemento?");
    if (eliminar) {
        window.location.href = enlace;
    }
}

/**
 * Permite crear boton de scroll to top cuando se hace scroll vertical
 */
if ($(window).height() > 200) {
    $('#top-link-block').removeClass('hidden').affix({
        offset: {top: 100}
    });
}

/**
 * Hace scroll hasta la posicion exacta que se le
 * pasa a traves del nombre de ventana.
 * Luego la pone a null
 */

window.onload = function () {
    var pos = window.name || 0;
    window.scrollTo(0, pos);
    window.name = null;
}

/**
 * Calcula la posicion de un elemento y la almacena
 * en el nombre de ventana.
 */

function recarga() {
    var bodyRect = document.body.getBoundingClientRect();
    var v = document.getElementById('up').getBoundingClientRect();
    var offset = v.top - bodyRect.top - 100;
    window.name = offset;
}

/**
 * Almacena en el nombre de ventana la posicion actual en la pagina
 */
function recarga2() {
    window.name = self.pageYOffset || (document.documentElement.scrollTop + document.body.scrollTop);
}

/**
 * Funcion que permite definir el action del formulario de buqueda
 * para poder filtrar por noticias, foros o tutoriales
 */

function defineActionBusqueda() {
    var formulario = document.getElementById('idformBusq');
    var noticias = document.getElementById('opciones_1');
    var tutoriales = document.getElementById('opciones_2');
    var foros = document.getElementById('opciones_3');

    if (foros.checked) {
        formulario.action = "pregunta/filtro";
    } else if (tutoriales.checked) {
        formulario.action = "tutorial/filtro";
    } else if (noticias.checked) {
        formulario.action = "noticia/filtro";
    }

}


$(document).ready(function () {
    // Interceptamos el evento submit
    $('#formPanelOcul,#formPanelMos').submit(function () {
        // Enviamos el formulario usando AJAX
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
        })
        return false;
    });
})
