function supports_html5_storage() {
    try {
        return 'localStorage' in window && window['localStorage'] !== null;
    } catch (e) {
        return false;
    }
}

var myCodeMirror = CodeMirror.fromTextArea($('#sql')[0], {
    mode: "text/x-mysql",
    lineNumbers: true,
    tabSize: 4,
    indentUnit: 4,
    indentWithTabs: true,
    matchBrackets: true,
    lineWrapping: true,
    autofocus: true
});

if (supports_html5_storage()) {
    set_previous_cursor();
}

function set_previous_cursor() {
    if (localStorage["codemirror_cursor_line"] && localStorage["codemirror_cursor_ch"]) {
        myCodeMirror.setCursor({
            line: parseInt(localStorage["codemirror_cursor_line"]),
            ch: parseInt(localStorage["codemirror_cursor_ch"])
        });
    }

    $('#query_form').submit(function() {
        var cm_cursor = myCodeMirror.getCursor();
        localStorage["codemirror_cursor_line"] = cm_cursor.line;
        localStorage["codemirror_cursor_ch"] = cm_cursor.ch;
    });
}

$(window).keydown(function(e) {
    if (e.keyCode == 116 || (e.keyCode == 13 && e.shiftKey)) {
        e.preventDefault();
        $('#query_form').submit();
    };
});