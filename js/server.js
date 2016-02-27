var http = require('http')
var url = require("url");

var port = process.env.PORT || 1337;

function start(route, handle) {
    function onRequest(request, response) {
        var pathname = url.parse(request.url).pathname;
        console.log("Request for " + pathname + " received.");
        route(handle, pathname, response, request);
    }

    http.createServer(onRequest).listen(port);
}

exports.start = start;