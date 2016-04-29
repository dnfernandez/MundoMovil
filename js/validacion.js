/**
 * Funcion que permite limpiar los efectos de error de un formulario
 * @param array Es un array que contiene los id que se utilizan en el formulario
 */
function limpia_form(array) {
    if (array != null) {
        for (var elem in array) {
            $("#div-" + array[elem]).removeClass("has-error");
            document.getElementById("help-" + array[elem]).style.display = "none";
        }
    }
}

/**
 * Funcion que permite validar si el campo email es correcto
 * @param id
 * @returns {boolean}
 */
function valida_email(id) {
    valor = document.getElementById(id).value;
    if (!(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/).test(valor)) {
        $("#div-" + id).addClass("has-error");
        document.getElementById("help-" + id).innerHTML = "Debes introducir un email v&aacute;lido (usuario@ejemplo.com)";
        document.getElementById("help-" + id).style.display = "block";
        return false;
    } else {
        $("#div-" + id).removeClass("has-error");
        document.getElementById("help-" + id).style.display = "none";
        return true;
    }
}
/**
 * Funcion que permite validar el campo contraseña no esta vacia
 * @param id
 * @returns {boolean}
 */
function valida_contrasenha(id) {
    valor = document.getElementById(id).value;
    if (valor == null || valor.length == 0 || /^\s+$/.test(valor)) {
        $("#div-" + id).addClass("has-error");
        document.getElementById("help-" + id).innerHTML = "La contrase&ntildea no puede estar vac&iacute;a";
        document.getElementById("help-" + id).style.display = "block";
        return false;
    } else {
        $("#div-" + id).removeClass("has-error");
        document.getElementById("help-" + id).style.display = "none";
        return true;
    }
}
/**
 * Funcion que permite validar si las contraseñas son iguales y si cumplen una serie de requisitos
 * @param id1
 * @param id2
 * @param esp
 * @returns {boolean}
 */

function valida_contrasenhas(id1, id2) {
    var cont = /(?=.*\d)(?=.*[a-z]){6,15}/;
    var password = document.getElementById(id1).value;
    var pass2 = document.getElementById(id2).value;

    if ((password.length == 0 || pass2.length == 0)) {
        $("#div-" + id1).addClass("has-error");
        $("#div-" + id2).addClass("has-error");
        document.getElementById("help-" + id2).innerHTML = "La contrase&ntildeas no pueden estar vac&iacute;as";
        document.getElementById("help-" + id2).style.display = "block";
        return false;
    } else if (!(cont.test(password)) || (password.length < 6) || (password.length > 15)) {
        $("#div-" + id1).addClass("has-error");
        $("#div-" + id2).addClass("has-error");
        document.getElementById("help-" + id2).innerHTML = "La contrase&ntildeas  deben tener un n&uacute;mero, una letra y entre 6 y 15 caracteres";
        document.getElementById("help-" + id2).style.display = "block";
        return false;
    } else if (password != pass2) {
        $("#div-" + id1).addClass("has-error");
        $("#div-" + id2).addClass("has-error");
        document.getElementById("help-" + id2).innerHTML = "La contrase&ntildeas no coinciden";
        document.getElementById("help-" + id2).style.display = "block";
        return false;
    } else {
        $("#div-" + id1).removeClass("has-error");
        $("#div-" + id2).removeClass("has-error");
        document.getElementById("help-" + id2).style.display = "none";
        return true;
    }
}

/**
 * Funcion que permite validar si el campo nombre de usuario es correcto
 * @param id
 * @returns {boolean}
 */
function valida_nom_usuario(id) {
    valor = document.getElementById(id).value;
    if (!(/^[a-z\d_]{4,15}$/i).test(valor)) {
        $("#div-" + id).addClass("has-error");
        document.getElementById("help-" + id).innerHTML = "El nombre de usuario debe tener de 4 a 15 caracteres alfanum&eacutericos";
        document.getElementById("help-" + id).style.display = "block";
        return false;
    } else {
        $("#div-" + id).removeClass("has-error");
        document.getElementById("help-" + id).style.display = "none";
        return true;
    }
}

/**
 * Funcion que permite validar si un campo alfanumerico es correcto
 * @param id
 * @returns {boolean}
 */
function valida_alfanumerico(id) {
    valor = document.getElementById(id).value;
    if ((!(/^[a-z\d_ ]{1,50}$/i).test(valor)) || /^\s*$/.test(valor)) {
        $("#div-" + id).addClass("has-error");
        document.getElementById("help-" + id).innerHTML = "Debe contener caracteres alfanum&eacutericos (m&aacuteximo 50) ";
        document.getElementById("help-" + id).style.display = "block";
        return false;
    } else {
        $("#div-" + id).removeClass("has-error");
        document.getElementById("help-" + id).style.display = "none";
        return true;
    }
}

/**
 * Funcion que permite validar si un campo titulo es correcto
 * @param id
 * @returns {boolean}
 */
function valida_titulo(id) {
    valor = document.getElementById(id).value;
    if ((!(/^([a-zA-Z\d_ !?¿¡:.,]){1,150}$/i).test(valor)) || /^\s*$/.test(valor)) {
        $("#div-" + id).addClass("has-error");
        document.getElementById("help-" + id).innerHTML = "Debe contener caracteres alfanum&eacutericos (m&aacuteximo 150) ";
        document.getElementById("help-" + id).style.display = "block";
        return false;
    } else {
        $("#div-" + id).removeClass("has-error");
        document.getElementById("help-" + id).style.display = "none";
        return true;
    }
}

/**
 * Funcion que permite validar si un campo texto es correcto
 * @param id
 * @returns {boolean}
 */
function valida_texto(id, opc) {
    opc = opc || 0;
    if (opc != 0) {
        valor = CKEDITOR.instances[id].getData();
    } else {
        valor = document.getElementById(id).value;
    }
    if (/^\s*$/.test(valor)) {
        $("#div-" + id).addClass("has-error");
        document.getElementById("help-" + id).innerHTML = "El campo no puede estar vac&iacute;o";
        document.getElementById("help-" + id).style.display = "block";
        return false;
    } else {
        $("#div-" + id).removeClass("has-error");
        document.getElementById("help-" + id).style.display = "none";
        return true;
    }
}

/**
 * Funcion que permite validar si un campo palabras clave es correcto
 * @param id
 * @returns {boolean}
 */
function valida_clave(id) {
    valor = document.getElementById(id).value;
    if (/^\s*$/.test(valor) || (!(/^([a-z][a-z\d_]+[ ]*)+$/i).test(valor))) {
        $("#div-" + id).addClass("has-error");
        document.getElementById("help-" + id).innerHTML = "Introduzca palabras separadas por espacios";
        document.getElementById("help-" + id).style.display = "block";
        return false;
    } else {
        $("#div-" + id).removeClass("has-error");
        document.getElementById("help-" + id).style.display = "none";
        return true;
    }
}

/**
 * Funcion que permite validar si un campo imagen es correcto
 * @param id
 * @returns {boolean}
 */

function valida_imagen(id) {
    valor = document.getElementById(id).value;
    if (!/^.+\.(jpe?g|gif|png)$/i.test(valor)) {
        $("#div-" + id).addClass("has-error");
        document.getElementById("help-" + id).innerHTML = "Introduzca una imagen con extensi&oacute;n jpg, jpeg, gif o png";
        document.getElementById("help-" + id).style.display = "block";
        return false;
    } else {
        $("#div-" + id).removeClass("has-error");
        document.getElementById("help-" + id).style.display = "none";
        return true;
    }
}

/*****************************************************************************************************************************************************
 * **************************************************** VALIDACIONES DE FORMULARIOS ******************************************************************
 * **************************************************************************************************************************************************/

/**
 * Funcion que permite validar el formulario de un login
 * @param form Es un array que contiene en primer lugar el id del formulario
 * en segundo lugar el id del campo email y finalmente el id del campo contrasenha.
 * A mayores encripta la contraseña en md5
 */

function validaLogin(form) {
    var bool = true;
    if (!valida_email(form[1])) {
        bool = false;
    }
    if (!valida_contrasenha(form[2])) {
        bool = false;
    }
    if (bool) {
        document.forms[form[0]].elements[form[2]].value = (hex_md5(document.forms[form[0]].elements[form[2]].value));
        document.forms[form[0]].submit();
    }
}

/**
 * Funcion que permite validar el formulario de un registro
 * @param form Es un array que contiene en primer lugar el id del formulario
 * en segundo lugar el id del campo nom_usuario, en tercer lugar el id del campo email
 * en cuarto lugar el id del campo ubicacion y finalmente los ids de los campos contrasenha.
 * A mayores encripta la contraseña en md5
 */

function validaRegistro(form) {
    var bool = true;
    if (!valida_nom_usuario(form[1])) {
        bool = false;
    }
    if (!valida_email(form[2])) {
        bool = false;
    }
    if (!valida_alfanumerico(form[3])) {
        bool = false;
    }
    if (!valida_contrasenhas(form[4], form[5])) {
        bool = false;
    }
    if (bool) {
        document.forms[form[0]].elements[form[4]].value = (hex_md5(document.forms[form[0]].elements[form[4]].value));
        document.forms[form[0]].elements[form[5]].value = (hex_md5(document.forms[form[0]].elements[form[5]].value));
        document.getElementById(form[6]).disabled = true;
        document.getElementById(form[2]).innerHTML = "Registrando...";
        document.forms[form[0]].submit();
    }
}
/**
 * Funcion que permite validar el formulario de envio de email para
 * recuperar una contrasenha.
 * @param form Es un array que contiene el id del formulario, el id
 * del campo email y el id del boton de envio del formulario.
 */
function validaRecuperarContrasenha(form) {
    var bool = true;
    if (!valida_email(form[1])) {
        bool = false;
    }

    if (bool) {
        document.getElementById(form[2]).disabled = true;
        document.getElementById(form[2]).innerHTML = "Enviando...";
        document.forms[form[0]].submit();
    }
}

/**
 * Funcion que permite validar el formulario para restablecer
 * una contraseña.
 * @param form Array con id formulario, id campo contraseha1,
 * id campo contraseha2, id boton envio formulario.
 */
function validaRestablecerContrasenha(form) {
    var bool = true;
    if (!valida_contrasenhas(form[1], form[2])) {
        bool = false;
    }

    if (bool) {
        document.forms[form[0]].elements[form[1]].value = (hex_md5(document.forms[form[0]].elements[form[1]].value));
        document.forms[form[0]].elements[form[2]].value = (hex_md5(document.forms[form[0]].elements[form[2]].value));
        document.getElementById(form[3]).disabled = true;
        document.forms[form[0]].submit();
    }
}

/**
 * Funcion que permite validar el formulario de modificar perfil
 * @param form Array que contiene id formulario, id campo ubicacion,
 * id campo contraseha1, id campo contraseha2,
 * id boton envio formulario.
 * Si la contraseñas estan vacias no las valida, ya que se entiende
 * que no quiere modificar su contraseña
 */
function validaModificarPerfil(form) {
    var bool = true;

    if (!valida_alfanumerico(form[1])) {
        bool = false;
    }

    if (document.forms[form[0]].elements[form[2]].value.length != 0 || document.forms[form[0]].elements[form[3]].value.length != 0) {
        if (!valida_contrasenhas(form[2], form[3])) {
            bool = false;
        }
    }

    if (bool) {
        if (document.forms[form[0]].elements[form[2]].value.length != 0 && document.forms[form[0]].elements[form[3]].value.length != 0) {
            document.forms[form[0]].elements[form[2]].value = (hex_md5(document.forms[form[0]].elements[form[2]].value));
            document.forms[form[0]].elements[form[3]].value = (hex_md5(document.forms[form[0]].elements[form[3]].value));
        }
        document.getElementById(form[4]).disabled = true;
        document.forms[form[0]].submit();
    }
}

/**
 * Funcion que permite validar el formulario de crear noticia
 * @param form Array que contiene todos los id's necesarios
 * que estan dentro del formulario para crear la noticia
 */
function validaCrearNoticia(form) {
    var bool = true;

    if (!valida_titulo(form[1])) {
        bool = false;
    }

    if (!valida_texto(form[2])) {
        bool = false;
    }

    if (!valida_clave(form[3])) {
        bool = false;
    }

    if (!valida_texto(form[4], 1)) {
        bool = false;
    }

    if (!valida_imagen(form[5])) {
        bool = false;
    }

    if (bool) {
        document.getElementById(form[6]).disabled = true;
        document.getElementById(form[6]).innerHTML = "Guardando...";
        document.forms[form[0]].submit();
    }
}

/**
 * Funcion que permite validar el formulario de modificar noticia
 * @param form Array que contiene todos los id's necesarios
 * que estan dentro del formulario para modificar la noticia
 */
function validaModificarNoticia(form) {
    var bool = true;

    if (!valida_titulo(form[1])) {
        bool = false;
    }

    if (!valida_texto(form[2])) {
        bool = false;
    }

    if (!valida_clave(form[3])) {
        bool = false;
    }


    if (!valida_texto(form[4], 1)) {
        bool = false;
    }

    if (document.forms[form[0]].elements[form[5]].value.length != 0) {
        if (!valida_imagen(form[5])) {
            bool = false;
        }
    }

    if (bool) {
        document.getElementById(form[6]).disabled = true;
        document.getElementById(form[6]).innerHTML = "Guardando...";
        document.forms[form[0]].submit();
    }
}

/**
 * Funcion que permite validar el formulario para crear
 * un comentario.
 * @param form Array con id formulario, id campo texto,
 * id boton envio formulario.
 */
function validaComentario(form) {
    var bool = true;
    if (!valida_texto(form[1])) {
        bool = false;
    }

    if (bool) {
        document.getElementById(form[2]).disabled = true;
        document.getElementById(form[2]).innerHTML = "Enviando...";
        document.forms[form[0]].submit();
    }
}