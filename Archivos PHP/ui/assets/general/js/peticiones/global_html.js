/**
 * Created by Eddie on 21/09/2017.
 */

/**
 * Convierte la primera letra de string en mayuscyla
 * @param string
 * @returns {string}
 */
function html_ucwords(string) {
    var arrayWords;
    var returnString = "";
    var len;
    arrayWords = string.split(" ");
    len = arrayWords.length;
    for (i = 0; i < len; i++) {
        if (i != (len - 1)) {
            returnString = returnString + ucFirst(arrayWords[i]) + " ";
        }
        else {
            returnString = returnString + ucFirst(arrayWords[i]);
        }
    }
    return returnString;
}
function ucFirst(string) {
    return string.substr(0, 1).toUpperCase() + string.substr(1, string.length).toLowerCase();
}

/**
 *
 * @param data
 * @returns {html}
 */
function html_foreach(data) {
    var data_ = "";
    $.each(data, function (key, value) {
        data_ = data_
            + "<b>" + html_br_length(html_ucwords(key), 10) + "</b>: " + value + "<br>";
    });
    return data_;

}


function html_foreach_input_group(data,comodin_array) {
    var data_ = "";
    $.each(data, function (key, value) {
        data_ = data_ + "<b>" + html_br_length(html_ucwords(key).replace("_", " "), 10) + "</b>: " + value + "<br>";
            if (comodin_array === "") {
             var ret_html =  html_input(key, "hidden", value);
            }else{
             var  ret_html = html_input(comodin_array + "[" + key + "]", "hidden", value);
            }
            data_ = data_ + ret_html;

    });
    return data_;

}

/**
 * Set Select HTML
 * @param data
 * @param value
 * @param texto
 * @returns {string}
 */
function html_set_select(data, value, texto) {
    var ge_data = '';
    for (var i = 0; i < data.length; i++) {
        ge_data = ge_data
            + '<option value="' + data[i][value] + '">'
            + data[i][texto]
            + '</option>\n';
    }
    return ge_data + "</select>";
}


/**
 * Set Title HTML
 * @param value
 * @param size
 * @returns {string}
 */
function html_set_title(value, size) {
    return "<h" + size + ">" + value + "</h" + size + ">";
}

/**
 * BR MaxLength
 * @value
 * @maxsize
 */
function html_br_length(value, maxsize) {
    if (value.length >= maxsize) {
        var salt = "<br>"
    } else {
        var salt = "";
    }
    return value + salt;

}

/**
 *
 * @param data
 * @returns {string}
 */
function html_table_noheader(data) {
    var va_html = '<table class="table table-striped"><tbody>';
    $.each(data, function (key, value) {
        va_html = va_html
            + "<tr>"
            + "<td><b>" + key + "</b></td>"
            + "<td>" + value + "</td>"
            + "</tr>";
    });
    return va_html + "</tbody></table>";
}


/**
 * Create Input
 * @param name_set
 * @param type_set
 * @param value_set
 * @returns {string}
 */

function html_input_group(name_set, type_set, value_set) {
    return '<label>' + value_set + '</label>'
        + '<input type="' + type_set + '" name="' + name_set + '" value="' + value_set + '">';
}


function html_input(name_set, type_set, value_set) {
        return '<input type="' + type_set + '" name="' + name_set + '" value="' + value_set + '">';
}


/**
 * Create Objet Doom
 * @param objet
 * @param type
 * @param value
 * @returns {dom}
 */

function dom_create_objet(objet, type, value) {
    var x = document.createElement(objet);
    x.setAttribute("type", type);
    x.setAttribute("value", value);
    return document.body.appendChild(x);
}