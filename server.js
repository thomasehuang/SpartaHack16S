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

// var http = require('http')
// var port = process.env.PORT || 1337;
// http.createServer(function(req, res) {
//     res.writeHead(200, {
//         'Content-Type': 'text/plain'
//     });
//     res.end('Hello World\n');
// }).listen(port);