<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        if (file_exists('organisation.xml')) {
            $organisation = simplexml_load_file('organisation.xml');
        } else {
            exit('Failed to open test.xml.');
        }
    ?>
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $organisation->title; ?></title>

    <!-- Bootstrap -->
    <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/animate.css">
    <link rel="stylesheet" href="css/overwrite.css">
    <link href="css/animate.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" />

    <script src="jquery-3.3.1/jquery-3.3.1.min.js"></script>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="js/alasql/alasql.min.js"></script>

</head>

<style>
        body{
            background-image: url("img/orange-background.jpg");
            background-repeat: no-repeat;
            background-size: cover;
        }
        #image-div{
            padding-left: 180px;
            padding-top: 0px;
        }
        .my-panel{
            background-color: #741700;
            border-radius: 10px;
            margin-left: 80px;
            margin-right: 80px;

        }
</style>
<body ng-app="myApp">

<br><br>
<div class="container-fluid">
    <div class="row" id="main-div">
        <div class="col-lg-3 col-xs-3 col-md-3 col-sm-3"></div>
        <div class="col-lg-6 col-xs-6 col-md-6 col-sm-6">
            <div class="row" id="image-div">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <img class="img-responsive" src="img/icon1.png">
                </div>
            </div>
            <div class="panel my-panel">
                <div class="panel-body">
                    <form class="form-horizontal" role="form">
                        <div class="form-group">
                            <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
                            <div class="controls col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <input type="text" class="form-control " id="usrname" placeholder="Card No">
                            </div>
                            <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
                        </div>
                        <div class="form-group">
                            <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
                            <div class="controls col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <input type="password" class="form-control" id="user-password" placeholder="Pin No">
                            </div>
                            <div class="controls col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
                        </div>
                        <div class="form-group">
                            <div class="controls col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
                            <div class="controls col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left: 50px">
                                <button id="submit-login" type="submit" class="btn btn-primary">Login</button>
                            </div>
                            <div class="controls col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-xs-3 col-md-3 col-sm-3"></div>
    </div>
</div>











<script src="js/login_script.js"></script>
<script src="js/md5/md5_js.js"></script>
<script src="jquery-3.3.1/jquery-ui.min.js"></script>
<script src="js/general_script.js"></script>





</body>
</html>




