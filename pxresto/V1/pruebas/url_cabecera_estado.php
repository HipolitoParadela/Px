<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
hola
<?php
    function url_exists($url)
    {
        $file_headers = @get_headers($url);
        if (strpos($file_headers[0], "200 OK") == false) {
            $exists = false;
            echo "<h2>" . $url . " no existe o dio error al solicitar.</h2>";
        } else {
            echo "<h2>" . $url . " existe y sus cabeceras son:</h2><pre>";
            print_r($file_headers);
            echo "</pre>";
            $exists = true;
        }
        return $exists;
    }
    if (isset($url)) {
        echo url_exists($url);
    }

    url_exists("http://192.168.1.9/");

    ?>



</body>
</html>