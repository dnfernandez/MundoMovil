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
        $("#div-" + id).removeClass("has-success");
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
        $("#div-" + id).removeClass("has-success");
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
 * @returns {boolean}
 */

function valida_contrasenhas(id1, id2) {
    var cont = /(?=.*\d)(?=.*[a-z]){6,15}/;
    var password = document.getElementById(id1).value;
    var pass2 = document.getElementById(id2).value;

    if (password.length == 0 || pass2.length == 0) {
        $("#div-" + id1).removeClass("has-success");
        $("#div-" + id1).addClass("has-error");
        $("#div-" + id2).removeClass("has-success");
        $("#div-" + id2).addClass("has-error");
        document.getElementById("help-" + id2).innerHTML = "La contrase&ntildeas no pueden estar vac&iacute;as";
        document.getElementById("help-" + id2).style.display = "block";
        return false;
    } else if (!(cont.test(password)) || (password.length < 6) || (password.length > 15)) {
        $("#div-" + id1).removeClass("has-success");
        $("#div-" + id1).addClass("has-error");
        $("#div-" + id2).removeClass("has-success");
        $("#div-" + id2).addClass("has-error");
        document.getElementById("help-" + id2).innerHTML = "La contrase&ntildeas  deben tener un n&uacute;mero, una letra y entre 6 y 15 caracteres";
        document.getElementById("help-" + id2).style.display = "block";
        return false;
    } else if (password != pass2) {
        $("#div-" + id1).removeClass("has-success");
        $("#div-" + id1).addClass("has-error");
        $("#div-" + id2).removeClass("has-success");
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
        $("#div-" + id).removeClass("has-success");
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
    if (!(/^[a-z\d_]{1,50}$/i).test(valor)) {
        $("#div-" + id).removeClass("has-success");
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
        document.getElementById(form[6]).disabled=true;
        document.forms[form[0]].submit();
    }
}