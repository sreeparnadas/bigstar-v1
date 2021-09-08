<header id="header">
    <nav class="navbar navbar-fixed-top" role="banner">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html"><?php echo $person_name;?></a>
            </div>
            <div class="collapse navbar-collapse navbar-right">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#!">Home</a></li>
                    <li><a href="#feature">Feature</a></li>
                    <li><a href="#gallery">Gallery</a></li>
                    <li><a href="#pricing">Price & Plan</a></li>
                    <li><a href="#our-team">Our Team</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li>
                        <button onclick="document.getElementById('id01').style.display='block'" style="width:auto;">Login</button>
                    </li>
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" target="_self" href="#">City <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#!paris">Paris</a></li>
                            <li><a href="#!london">London</a></li>
                            <li><a href="#!kolkata">Kolkata</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div><!--/.container-->
    </nav><!--/nav-->
</header><!--/header-->