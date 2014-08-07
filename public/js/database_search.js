$(document).ready(function() {
    
    $( "#database_search" ).keyup(function() {
        var search_string = $(this).val();
        search_string = search_string.replace(": ", ":");

        var search_parts = search_string.split(" ");
        var database_name = null;
        var host_name = null;

        for (i = 0; i < search_parts.length; i++) {

            // Do special argument logic
            if (search_parts[i].search(":") != -1) {
                var arg_parts = search_parts[i].split(":");

                switch (arg_parts[0]) {
                    case "h":
                    case "host":
                    case "s":
                    case "serv":
                    case "server":
                        host_name = arg_parts[1].replace(" ", "");
                        break;
                    case "d":
                    case "db":
                    case "data":
                    case "database":
                        database_name = arg_parts[1].replace(" ", "");
                        break;
                    default:
                        // ignore
                        break;
                }
            } else if (! database_name) {
                database_name = search_parts[i].replace(" ", "");
            }
        }

        $('.database').each(function() {
            var database = $(this);
            var show = true;

            if (database_name) {
                show = (database.data('database_name').toLowerCase().indexOf(database_name) >= 0);
            }

            if (show && host_name) {
                show = (database.data('host_name').toLowerCase().indexOf(host_name) >= 0);
            }
            
            if (show) {
                return database.show();
            } else {
                return database.hide();
            }
        });
    });
});