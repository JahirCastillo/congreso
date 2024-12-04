/**
 * @summary     utilerias
 * @description Utilerias para jquery
 * @version     1
 * @file        utilerias.js
 * @author    Carlos Garcia Trujillo
 *                  Jose Adrian Ruiz Carmona
 * @contact     
 *
 * @requires jQuery v1.2.3 o superior,jQuery blockUI Version 2.39 o superior
 * @copyright Copyright 2012.
 * 
 *  Este archivo consta de algunas utilerias necesarias para el proyecto
 * 
 */

/** @lends <global> timestamp devuelve la fecha actual */
var timestamp = new Date().getTime();

/**
 *  Peticion ajax que recibe una respuesta del servidor en fomato de texto
 *  @param {string} purl -url a donde se hará la petición
 *  @param {objet} pparameters -conjunto de valores que se envian al servidor y se recuperarán por post
 *  @returns {string} valor -representa la respuesta del servidor en formato de texto,
 *   si no se realiza la llamada ajax por un error devolvera el error como cadena
 *  @example 
 *           //optengo el texto del imput que tiene id="uninput"
 *           var undato = $('#uninput').val();
 *           //hago la peticion ajax al servidor pasandole como parametro undato
 *           var respuesta_srv = get_value('pparametersreservaciones_temporales/usuarios/',{undato:undato});
 *  @memberof utilerias
 */
function get_value(purl, pparameters) {
    var valor = 'N/A_';
    if ($.blockUI) {
        $.blockUI.defaults.baseZ = 200000;
        $.blockUI({
            message: '<img src="./images/loading3.gif" width="50" heigth="50"><h1>Cargando...</h1>Espere un momento por favor.',
            showOverlay: false,
            centerY: false,
            css: {
                width: '350px',
                border: 'none',
                margin: 'auto',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: '0.4',
                filter: 'alpha(opacity=40)',
                color: '#fff'
            }
        });
    }

    $.ajax({
        url: base_url + 'index.php/' + purl,
        type: 'POST',
        data: pparameters,
        async: false,
        cache: false,
        dataType: 'text',
        timeout: 30000,
        error: function (a, b) {
            valor = b;
        },
        success: function (msg) {
            valor = msg;
        }
    });
    document.body.style.cursor = "wait";
    if ($.blockUI) {
        $.unblockUI();
    }

    setTimeout(function () {
        document.body.style.cursor = "default";
    }, 400);
    return valor;
}


/**
 *  Muestra una notificación(tip) en la parte superior derecha de la pagina
 *  @param {string} url_img -ruta de alguna imagen simbolica al tipo de notificación o tip a mostrar
 *  @param {objet} titulo -titulo del tip o notificación
 *  @returns   
 *  @example 
 *           var datos = $( "#form_modifica_usuario" ).serialize();//obtener los datos del formulario y serializarlos
 *                        var urll="index.php/usuarios/modificaUsuario";
 *                        var respuesta = ajax_peticion(urll,datos);
 *                        if (respuesta=='ok'){
 *                           //si la peticion ajax responde ok muestro la notificación
 *                           notificacion_tip("./images/msg/ok.png","Modificar Usuario","El usuario se modific&oacute; satisfactoriamente.");
 *                            dt_usuarios.fnDraw();//recargar los datos del datatable
 *                        }else{
 *                            notificacion_tip("./images/msg/error.png","Modificar Usuario","Error al modificar.");
 *                        }
 *  @memberof utilerias
 */
function notificacion_tip(url_img, titulo, msg) {
    $.blockUI({
        message: '<div><img width="50px" src="' + url_img + '"/><h1 style="font-size:16px;">' + titulo + '</h1></div>' + msg,
        fadeIn: 700,
        fadeOut: 700,
        timeout: 3000,
        showOverlay: false,
        centerY: false,
        css: {
            width: '350px',
            top: '10px',
            left: '',
            right: '10px',
            border: 'none',
            padding: '5px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .6,
            color: '#fff'
        }
    });
}

function muestra_espera() {
    if ($.blockUI) {
        $.blockUI.defaults.baseZ = 200000;
        $.blockUI({
            message: '<img src="./images/loading3.gif" width="50" heigth="50"><h1>Cargando...</h1>Espere un momento por favor.',
            showOverlay: false,
            centerY: false,
            css: {
                width: '350px',
                border: 'none',
                margin: 'auto',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: '0.4',
                filter: 'alpha(opacity=40)',
                color: '#fff'
            }
        });
    }
}

function oculta_espera() {
    if ($.blockUI) {
        $.unblockUI();
    }
}
function get_object(purl, pparameters) {
    var t = get_value(purl, pparameters);
    var j = eval('(' + t + ')');
    return j;
}

/**
 *  abre una url en una nueva ventana o pestaña 
 *  @param {string} purl -url ala que se redirecionará
 *  @example 
 *           redirect_to("http://www.google.com.mx/");
 *                                   
 *  @memberof 
 */
function redirect_to(purl) {
    setTimeout(function () {
        window.location.href = base_url + 'index.php/' + purl;
    },
        0);
}

/**
 *  abre una url en una nueva ventana o pestaña 
 *  @param {string} purl -url que se abrirá en una nueva ventana del navegador
 *  @example 
 *           open_in_new("http://www.google.com.mx/");
 *                                   
 *  @memberof utilerias
 */
function open_in_new(purl) {
    window.open(base_url + 'index.php/' + purl, "_new");
}

/**
 *  Quita espacios al principio y fin de la cadena
 *  @param {string} inputString -cadena ala que se le quitarán espacios en blanco
 *  @returns {string} inputString -cadena sin espacios al final ni al principio
 *  @example 
 *           var nombre = '    Adrian    ';
 *           var sinespacios=trim(nombre);
 *           //resultado sinespacios='Adrian';
 *                                   
 *  @memberof utilerias
 */
function trim(inputString) {
    return $.trim(inputString);
}

var nav4 = window.Event ? true : false;
function IsNumber(evt) {
    // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
    var key = nav4 ? evt.which : evt.keyCode;
    return (key <= 13 || (key >= 48 && key <= 57));
}
function IsNumberFloat(evt) {
    // Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
    var key = nav4 ? evt.which : evt.keyCode;
    return (key <= 13 || (key >= 48 && key <= 57 || key == 46));
}

/* obtener la fila seleccionada */
function fnGetSelected(oTableLocal) {
    return oTableLocal.$('tr.row_selected');
}
/* obtener la fila seleccionada */
function fnGetSelectedUI(oTableLocal) {
    return oTableLocal.$('tr.ui-state-active');
}


function mensaje(o, titulo, url_img, text1, text2, ancho, es_modal) {
    var stmodal = true;
    var h = 400;
    if ((es_modal == true) || (es_modal == false)) {
        stmodal = es_modal;
    }
    if (!isNaN(ancho)) {
        h = ancho;
    }

    $("#dialog:ui-dialog").dialog("destroy");
    o.attr('title', '');
    o.html('<p><span style="float:left; margin:0 7px 0px 0;"><img src="'
        + url_img + '"/></span>'
        + text1 + '</p><p style="font-size: 13px;">' + text2 + '</p>');
    o.attr('title', titulo);
    $("#ui-dialog-title-mensaje").html(titulo);
    o.dialog({
        modal: stmodal,
        width: h,
        buttons: {
            Aceptar: function () {
                o.attr('title', '');
                o.html('');
                $(this).dialog("close");

            }
        },
        close: function () {
            o.attr('title', '');
            o.html('');
        }
    }).dialog("open");
}


/*Funcion que toma como parametro un textarea(textarea)
 * al cual se le calculará el maximo de caracteres permitidos(num_car_max)
 * ademas de un el nombre del div donde se mostraran los mansajes para saber el resto de chars(div_mostrar)
 */
function contar_chars_restantes(textarea, num_car_max, div_mostrar) {
    if (textarea.value.length >= num_car_max) {
        textarea.value = textarea.value.substring(0, num_car_max);
    }
    var resto = num_car_max - textarea.value.length;
    //imprimimos los caracteres restantes en el span
    var letras = document.getElementById(div_mostrar);
    letras.innerHTML = resto + "/" + num_car_max + " caracteres";
}


//busca caracteres que no sean espacio en blanco en una cadena  
function vacio(q) {
    for (var i = 0; i < q.length; i++) {
        if (q.charAt(i) != " ") {
            return true
        }
    }
    return false
}

//valida que el campo no este vacio y no tenga solo espacios en blanco  
function validaInt(o, n, tips) {
    if (isNaN(o.val())) {
        o.addClass("ui-state-error");
        updateTips("El campo '" + n + "' no parece ser un n&uacute;mero.Por favor proporciona un n&uacute;", tips);
        return false;
    } else {
        return true;
    }

}
//valida que el campo no este vacio y no tenga solo espacios en blanco  
function campoVacio(o, n, tips) {
    var cadena = o.val();
    if (vacio(cadena) == false) {
        o.addClass("ui-state-error");
        updateTips("El campo '" + n + "' es requerido.", tips);
        return false;
    } else {
        return true;
    }

}

function passSeguro(o, n, tips) {
    var minuscula = false
    var mayuscula = false
    var numero = false
    var caracter = false
    var cadena = o.val();

    for (i = 0; i < cadena.length; i++) {
        //si el codigo ASCII es el de las minusculas, pone a true el flag de minusculas  
        if (cadena.charCodeAt(i) >= 97 && cadena.charCodeAt(i) <= 122) {
            minuscula = true
            //si el codigo ASCII es el de las mayusculas, pone a true el flag de mayusculas  
        } else if (cadena.charCodeAt(i) >= 65 && cadena.charCodeAt(i) <= 90) {
            mayuscula = true
            //si el codigo ASCII es el de loss numeros, pone a true el flag de numeros  
        } else if (cadena.charCodeAt(i) >= 48 && cadena.charCodeAt(i) <= 57) {
            numero = true
            //si no es ninguno de los anteriores, a true el flag de caracter simbolico  
        } else
            caracter = true
    }

    if (caracter == true && numero == true && minuscula == true && mayuscula == true) {
        return true;    //cambiar false por true para hacer el submit  
    } else {
        o.addClass("ui-state-error");
        updateTips(n, tips);
        return false;
    }
}

function esCampoVacio(o) {
    var cadena = o.val();
    if (vacio(cadena) == false) {
        return false;
    } else {
        return true;
    }

}

/**
 *funcion que verifica la longitud de un campo
 * @param {Object} texto Texto que sera mostrado
 * @param {Object} o Es el objeto al cual se le aplicara el test de logitud
 * @param {Object} n nombre del objeto
 * @param {Object} min Longitud minima en caracteres del objeto o
 * @param {Object} max Longitud axima en caracteres del objeto o
 * @param {Object} tips Elemento al cual se le enviaran mensajes
 */
function verificaLongitud(o, n, min, max, tips) {
    if (o.val().length > max || o.val().length < min) {
        o.addClass("ui-state-error");
        updateTips("El tamaño del campo '" + n + "' debe estar entre " +
            min + " y " + max + ".", tips);
        return false;
    } else {
        return true;
    }
}

/**
 *funcion que valida un campo del formulario basado en expresiones regulares
 * @param {Object} o Texto que sera mostrado
 * @param {Object} regexp Es el objeto al cual se le aplicara el test de logitud
 * @param {Object} n nombre del objeto
 * @param {Object} tips Elemento del dom al cual se le enviaran mensajes para mostrar al usuario
 */
function validaCampoExpReg(o, regexp, n, tips) {
    if (!(regexp.test(o.val()))) {
        o.addClass("ui-state-error");
        updateTips(n, tips);
        return false;
    } else {
        return true;
    }
}

function sleep(millisegundos) {
    var inicio = new Date().getTime();
    while ((new Date().getTime() - inicio) < millisegundos) {
    }
}

function notify_block(title, subtitle, msg, img) {
    var html = '<div class="not_div">';
    html += '<div class="not_title" style="padding-left: 10px;font-size: 18px; border-top-left-radius: 5px;border-top-right-radius: 5px;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;background-color: rgb(189, 189, 189); color: #444444;">' + title + '</div>';
    if (img != false) {
        html += '<div class="not_img" style="width: 60px;float: left;padding-top: 28px;padding-right: 10px;margin-top: -25px;"><img src="images/not/' + img + '.png" width="50" height="50"></div>';
    }
    html += '<div class="not_info"><div class="not_subtitle" style="text-align: left;font-size: 14px;margin-top: 2px;">' + subtitle + '</div>';
    html += '<div class="not_message" style="font-size: 12px;text-align: justify;padding: 10px;padding-top: 2px;">' + msg + '</div>';
    html += '</div></div>';
    $.blockUI({
        message: html,
        fadeIn: 700,
        fadeOut: 700,
        timeout: 2000,
        showOverlay: false,
        centerY: false,
        css: {
            width: '350px',
            top: '10px',
            left: '',
            right: '10px',
            border: 'none',
            padding: '5px',
            opacity: .8,
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            color: '#fff'
        }
    });
}

function mensaje_center(title, subtitle, msg, img) {
    var html = '<div class="not_div">';
    html += '<div class="not_title" style="padding-left: 10px;font-size: 18px; border-top-left-radius: 5px;border-top-right-radius: 5px;border-bottom-right-radius: 5px;border-bottom-left-radius: 5px;background-color: rgb(189, 189, 189); color: #444444;">' + title + '</div>';
    if (img != false) {
        html += '<div class="not_img" style="width: 60px;float: left;padding-top: 28px;padding-right: 10px;margin-top: -25px;"><img src="images/not/' + img + '.png" width="50" height="50"></div>';
    }
    html += '<div class="not_info"><div class="not_subtitle" style="text-align: left;font-size: 14px;margin-top: 2px;">' + subtitle + '</div>';
    html += '<div class="not_message" style="font-size: 12px;text-align: justify;padding: 10px;padding-top: 2px;">' + msg + '</div>';
    html += '</div></div>';
    $.blockUI({
        message: html,
        fadeIn: 700,
        fadeOut: 700,
        timeout: 3500,
        showOverlay: false,
        centerY: false,
        css: {
            width: '350px',
            border: 'none',
            padding: '5px',
            backgroundColor: '#444444',
            '-webkit-border-radius': '8px',
            '-moz-border-radius': '8px',
            opacity: .9,
            color: '#fff'
        }
    });
}


/**
 *@brief Returns a valid URL considering whether relative or absolute url
 *@param {String} purl URL to valid
 *@return {String} URL valid
 *@example fixUrl("http://www.someurl.com/") -> http://www.someurl.com/ 
 *@example fixUrl("someFragmentURL") -> http://www.baseurl.com/someFragmentURL 
 **/
function fixUrl(purl) {
    if ((purl.startsWith('http://')) || (purl.startsWith('https://')) || (typeof (base_url) == "undefined")) {
        return purl;
    } else {
        return base_url + purl;
    }
}
function redirectByPost(purl, pparameters, in_new_tab) {
    var url = fixUrl(purl);
    pparameters = (typeof pparameters == 'undefined') ? {} : pparameters;
    in_new_tab = (typeof in_new_tab == 'undefined') ? true : in_new_tab;
    var form = document.createElement("form");
    $(form).attr("id", "reg-form").attr("name", "reg-form").attr("action", url).attr("method", "post").attr("enctype", "multipart/form-data");
    if (in_new_tab) {
        $(form).attr("target", "_blank");
    }
    $.each(pparameters, function (key) {
        $(form).append('<input type="text" name="' + key + '" value="' + this + '" />');
    });
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
    return false;
}
function getObject(purl, pparameters, callbackfunction) {
    if (callbackfunction) {
        getValue(purl, pparameters, function (s) {
            var obj = (new Function('return ' + s))();
            callbackfunction(obj);
        });
    } else {
        var t = getValue(purl, pparameters);
        return (new Function('return ' + t))();
    }
}

function getValue(purl, pparameters, callbackfunction) {
    var outputValue = 'N/A_';
    if (callbackfunction) {
        asinc = true;
    } else {
        asinc = false;
    }
    $.ajax({
        url: fixUrl(purl),
        type: 'POST',
        data: pparameters,
        async: asinc,
        cache: false,
        dataType: 'text',
        timeout: 30000,
        error: function (a, b) {
            outputValue = b;
        },
        success: function (msg) {
            outputValue = msg;
            if (callbackfunction) {
                callbackfunction(outputValue);
            }
        }
    });
    return outputValue;
}
function fixUrl(purl) {
    if ((purl.startsWith('http://')) || (purl.startsWith('https://')) || (typeof (base_url) == "undefined")) {
        return purl;
    } else {
        return base_url + 'index.php/' + purl;
    }
}
function exportToExcel(fileName, htmls) {
    var uri = "data:application/vnd.ms-excel;charset=UTF-8;base64,";
    var template =
        '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> <meta charset="utf-8" />  <!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>';
    var base64 = function (s) {
        return window.btoa(unescape(encodeURIComponent(s)));
    };

    var format = function (s, c) {
        return s.replace(/{(\w+)}/g, function (m, p) {
            return c[p];
        });
    };

    var ctx = {
        worksheet: "Worksheet",
        table: htmls,
    };

    var link = document.createElement("a");
    document.body.appendChild(link);
    link.download = fileName + ".xls";
    link.href = uri + base64(format(template, ctx));
    link.click();
    link.remove();
}

function crearOverlay() {
    const overlay = document.createElement('div');
    overlay.id = 'overlay';
    overlay.style.position = 'fixed';
    overlay.style.top = '0';
    overlay.style.left = '0';
    overlay.style.width = '100%';
    overlay.style.height = '100%';
    overlay.style.backgroundColor = 'rgba(255, 255, 255, 0.8)';
    overlay.style.zIndex = '9999';
    overlay.style.display = 'flex';
    overlay.style.justifyContent = 'center';
    overlay.style.alignItems = 'center';

    const spinner = document.createElement('div');
    spinner.className = 'spinner-border text-info';
    spinner.setAttribute('role', 'status');
    spinner.innerHTML = '<span class="visually-hidden">Cargando...</span>';
    overlay.appendChild(spinner);
    document.body.appendChild(overlay);
}

function mostrarCargando() {
    const overlay = document.getElementById('overlay');
    if (overlay) {
        overlay.style.display = 'flex'; // Muestra el overlay
    } else {
        crearOverlay(); // Crea y muestra si no existe
        mostrarCargando(); // Vuelve a llamar para mostrar
    }
}

function ocultarCargando() {
    const overlay = document.getElementById('overlay');
    if (overlay) {
        overlay.style.display = 'none'; // Oculta el overlay
    }
}
function refreshPage() {
    location.reload();
}

function activaTooltip() {
    let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
}