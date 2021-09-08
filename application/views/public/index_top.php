<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta name="viewport" content="width=1024">
    <?php
        if (file_exists('organisation.xml')) {
            $organisation = simplexml_load_file('organisation.xml');
        } else {
            exit('Failed to open test.xml.');
        }
    ?>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <link rel="shortcut icon" href="img/icon1.png" type="image/x-icon">
    <title><?php echo $organisation->title; ?></title>

    <!-- Bootstrap -->
    <link href="bootstrap-4.0.0/dist/css/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/overwrite.css">
    <link href="css/animate.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/hui-color-style.css" rel="stylesheet" />
<!--    <link href="bootstrap-select-1.13.2/dist/css/bootstrap-select.min.css" rel="stylesheet" />-->

    <script src="jquery-3.3.1/jquery-3.3.1.min.js"></script>
    <script src="fontawesome-free-5.0.13/svg-with-js/js/fontawesome-all.min.js" type="text/javascript"></script>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="js/alasql/alasql.min.js"></script>

</head>

<style>
    .modal-header, h4, .close {
        background-color: #5cb85c;
        color:white !important;
        text-align: center;
        font-size: 30px;
    }
    .modal-footer {
        background-color: #f9f9f9;
    }
</style>
<body ng-app="myApp">




