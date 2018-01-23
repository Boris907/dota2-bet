var express = require("express");
var bodyParser = require("body-parser");
var app = express();
var port = process.env.PORT || 80;



app.use(bodyParser.json());
app.use(bodyParser.urlencoded({
    extended: true
}));
app.post("/lobby", function (req, res) {
    var user_name = req.body.username;

    console.log(user_name);
    res.send(user_name);
});
app.listen(port);
console.log('Listening:' + port);