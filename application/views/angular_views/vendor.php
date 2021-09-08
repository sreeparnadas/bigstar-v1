    <style type="text/css">
        #vendor-div{
            margin-top: 50px;
        }


        #mySidenav a[ng-click]{
            cursor: pointer;
            position: absolute;
            left: -20px;
            transition: 0.3s;
            padding: 15px;
            width: 140px;
            text-decoration: none;
            font-size: 15px;
            color: white;
            border-radius: 0 5px 5px 0;
            background-color: #ac2925;
        }

        #mySidenav a[ng-click]:hover {
            left: 0;
        }

        #mySidenav a:hover {
            left: 0;
        }
        #new-vendor {
            top: 20px;
            background-color: #4CAF50;
        }

        #update-vendor {
            top: 78px;
            background-color: #2196F3;
        }

        #show-vendor{
            top: 136px;
            background-color: #f44336;
        }
        #main-working-div h1{
            color: darkblue;
        }
    </style>
    <div class="container-fluid">
        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="vendor-div">

                    {{msg}}<br>
                    {{tab}}
                <!-- Nav tabs -->
                <ul class="nav nav-tabs nav-justified indigo" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#" role="tab" ng-click="setTab(1)"><i class="fa fa-user" ></i> Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(2)"><i class="fa fa-heart"></i> Follow</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#" role="tab" ng-click="setTab(3)"><i class="fa fa-envelope"></i> Mail</a>
                    </li>
                </ul>
                <!-- Tab panels -->
                <div class="tab-content">
                    <!--Panel 1-->
                    <div ng-show="isSet(1)" id="panel5" ">
                        <br>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil odit magnam minima, soluta doloribus reiciendis molestiae placeat unde eos molestias. Quisquam aperiam, pariatur. Tempora, placeat ratione porro voluptate odit minima.</p>
                    </div>
                    <!--/.Panel 1-->
                    <!--Panel 2-->
                    <div  ng-show="isSet(2)" id="panel6" >
                        <br>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil odit magnam minima, soluta doloribus reiciendis molestiae placeat unde eos molestias. Quisquam aperiam, pariatur. Tempora, placeat ratione porro voluptate odit minima.</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil odit magnam minima, soluta doloribus reiciendis molestiae placeat unde eos molestias. Quisquam aperiam, pariatur. Tempora, placeat ratione porro voluptate odit minima.</p>
                    </div>
                    <!--/.Panel 2-->
                    <!--Panel 3-->
                    <div  ng-show="isSet(3)" id="panel7" >
                        <br>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nihil odit magnam minima, soluta doloribus reiciendis molestiae placeat unde eos molestias. Quisquam aperiam, pariatur. Tempora, placeat ratione porro voluptate odit minima.</p>
                    </div>
                    <!--/.Panel 3-->
                </div>
            </div>

        </div>
    </div>






