//site_url='http://127.0.0.1/bigstar/index.php/';
// var app = angular.module("myApp", ["ui.bootstrap"]);
var url=location.href;
var urlAux = url.split('/');
		//FOR SERVER
//var base_url=urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/';
//var site_url=urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/index.php/';

//FOR LOCAL 
var base_url=urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/'+urlAux[3]+'/';
var site_url=urlAux[0]+'/'+urlAux[1]+'/'+urlAux[2]+'/'+urlAux[3]+'/index.php/';
var project_url=base_url;

var app = angular.module("myApp", ["ngRoute","angular-md5","timer","angular-barcode"]);
app.config(function($routeProvider) {
    $routeProvider
        .when("/", {
            templateUrl : site_url+"base/angular_view_login",
            controller : "loginCtrl"
        }).when("/play", {
            templateUrl : site_url+"Play/angular_view_play",
            controller : "playCtrl"
        }).when("/cpanel", {
            templateUrl : site_url+"Admin/angular_view_welcome",
            controller : "adminCtrl"
        }).when("/stockist", {
            templateUrl : site_url+"Stockist/angular_view_stockist",
            controller : "stockistCtrl"
        }).when("/terminal", {
            templateUrl : site_url+"Terminal/angular_view_terminal",
            controller : "terminalCtrl"
        }).when("/stlim", {
            templateUrl : site_url+"StockistLimit/angular_view_limit",
            controller : "stockistLimitCtrl"
         }).when("/trlim", {
            templateUrl : site_url+"TerminalLimit/angular_view_limit",
            controller : "terminalLimitCtrl"
        }).when("/payout", {
            templateUrl : site_url+"payoutSettings/angular_view_set_payout",
            controller : "payoutSettingsCtrl"
        }).when("/manualresult", {
            templateUrl : site_url+"ManualResult/angular_view_set_manual_result",
            controller : "manualResultCtrl"
        }).when("/reportterm", {
            templateUrl : site_url+"ReportTerminal/angular_view_terminal_report",
            controller : "reportTerminalCtrl"
        }).when("/custSalesReportCtrl", {
            templateUrl : site_url+"CustomerSalesReport/angular_view_customer_sale_report",
            controller : "custSalesReportCtrl"
         }).when("/barcodereport", {
            templateUrl : site_url+"BarcodeReport/angular_view_terminal_report",
            controller : "barcodeReportCtrl"
        }).when("/termsession", {
            templateUrl : site_url+"SessionCloseTerminal/angular_view_close_session",
            controller : "sessionCloseTerm"
         });
});

app.directive('a', function() {
    return {
        restrict: 'E',
        link: function(scope, elem, attrs) {
            if(attrs.ngClick || attrs.href === '' || attrs.href === '#'){
                elem.on('click', function(e){
                    e.preventDefault();
                });
            }
        }
    };
});

//it will allow integer values
app.directive('numbersOnly', function () {
    return {
        require: 'ngModel',
        link: function (scope, element, attr, ngModelCtrl) {
            function fromUser(text) {
                if (text) {
                    var transformedInput = text.replace(/[^0-9-]/g, '');
                    if (transformedInput !== text) {
                        ngModelCtrl.$setViewValue(transformedInput);
                        ngModelCtrl.$render();
                    }
                    return transformedInput;
                }
                return undefined;
            }
            ngModelCtrl.$parsers.push(fromUser);
        }
    };
});


app.controller("londonCtrl", function ($scope,$http) {
    $scope.msg = "I love London";
    //$http.get("person.php").then(function(response) {
    $http.get(site_url+"base/get_persons").then(function(response) {
        $scope.myData = response.data.records;
    });
    $scope.removeItem = function (x) {
        // $scope.myData.splice(x, 1);
        var r_id='row_id_'+x;
        $('#'+r_id).remove();
    };
    $scope.orderByMe = function(x) {
        $scope.myOrderBy = x;
    };
});

app.controller("mainController", function ($scope) {
    $scope.msg = "I love Paris";
    wow = new WOW({}).init();
});

app.filter('capitalize', function() {
    return function(input) {
        return (!!input) ? input.split(' ').map(function(wrd){return wrd.charAt(0).toUpperCase() + wrd.substr(1).toLowerCase();}).join(' ') : '';
    }
});
app.run(function($rootScope){
    $rootScope.CurrentDate = Date;
});
////Directive for input maxlength//
app.directive('myMaxlength', function() {
    return {
        require: 'ngModel',
        link: function (scope, element, attrs, ngModelCtrl) {
            var maxlength = Number(attrs.myMaxlength);
            function fromUser(text) {
                if (text.length > maxlength) {
                    var transformedInput = text.substring(0, maxlength);
                    ngModelCtrl.$setViewValue(transformedInput);
                    ngModelCtrl.$render();
                    return transformedInput;
                }
                return text;
            }
            ngModelCtrl.$parsers.push(fromUser);
        }
    };
});


app.directive('navigatable', function() {
    return function(scope, element, attr) {

        element.on('keypress.mynavigation', 'input[type="text"]', handleNavigation);


        function handleNavigation(e) {

            var arrow = {left: 37, up: 38, right: 39, down: 40};

            // select all on focus
            element.find('input').keydown(function(e) {

                // shortcut for key other than arrow keys
                if ($.inArray(e.which, [arrow.left, arrow.up, arrow.right, arrow.down]) < 0) {
                    return;
                }

                var input = e.target;
                var td = $(e.target).closest('td');
                var moveTo = null;

                switch (e.which) {

                    case arrow.left:
                    {
                        if (input.selectionStart == 0) {
                            moveTo = td.prev('td:has(input,textarea)');
                        }
                        break;
                    }
                    case arrow.right:
                    {
                        if (input.selectionEnd == input.value.length) {
                            moveTo = td.next('td:has(input,textarea)');
                        }
                        break;
                    }

                    case arrow.up:
                    case arrow.down:
                    {

                        var tr = td.closest('tr');
                        var pos = td[0].cellIndex;

                        var moveToRow = null;
                        if (e.which == arrow.down) {
                            moveToRow = tr.next('tr');
                        }
                        else if (e.which == arrow.up) {
                            moveToRow = tr.prev('tr');
                        }

                        if (moveToRow.length) {
                            moveTo = $(moveToRow[0].cells[pos]);
                        }

                        break;
                    }

                }

                if (moveTo && moveTo.length) {

                    e.preventDefault();

                    moveTo.find('input,textarea').each(function(i, input) {
                        input.focus();
                        input.select();
                    });

                }

            });


            var key = e.keyCode ? e.keyCode : e.which;
            if (key === 13) {
                var focusedElement = $(e.target);
                var nextElement = focusedElement.parent().next();
                if (nextElement.find('input').length > 0) {
                    nextElement.find('input').focus();
                } else {
                    nextElement = nextElement.parent().next().find('input').first();
                    nextElement.focus();
                }
            }
        }
    };
});

app.filter('formatDuration', function () {
    return function (input) {
        var totalHours, totalMinutes, totalSeconds, hours, minutes, seconds, result='';

        totalSeconds = input / 1000;
        totalMinutes = totalSeconds / 60;
        totalHours = totalMinutes / 60;

        seconds = Math.floor(totalSeconds) % 60;
        minutes = Math.floor(totalMinutes) % 60;
        hours = Math.floor(totalHours) % 60;

        if (hours !== 0) {
            result += hours+':';

            if (minutes.toString().length == 1) {
                minutes = '0'+minutes;
            }
        }

        result += minutes+':';

        if (seconds.toString().length == 1) {
            seconds = '0'+seconds;
        }

        result += seconds;

        return result;
    };
});

app.directive('hideZero', function() {
    return {
        require: 'ngModel',
        restrict: 'A',
        link: function (scope, element, attrs, ngModel) {
            ngModel.$formatters.push(function (inputValue) {
                if (inputValue == 0) {
                    return '';
                }
                return inputValue;
            });
            ngModel.$parsers.push(function (inputValue) {
                if (inputValue == 0) {
                    ngModel.$setViewValue('');
                    ngModel.$render();
                }
                return inputValue;
            });
        }
    };
})

app.run(function($rootScope){
    $rootScope.roundNumber=function(number, decimalPlaces){
        return parseFloat(parseFloat(number).toFixed(decimalPlaces));
    };
});


app.run(function($rootScope,$window){
    $rootScope.goToFrontPage=function(){
        $window.location.href = '#!';
        $window.location.reload()
    };
});





app.run(function($rootScope,$timeout) {
    $rootScope.huiPrintDiv = function(printDetails,userCSSFile, numberOfCopies) {
    	
        var divContents=$('#'+printDetails).html();
        
      
        var printWindow = window.open('', '', 'height=400,width=800');

        printWindow.document.write('<!DOCTYPE html>');
        printWindow.document.write('\n<html>');
        printWindow.document.write('\n<head>');
        printWindow.document.write('\n<title>');
        //printWindow.document.write(docTitle);
        printWindow.document.write('</title>');
        printWindow.document.write('\n<link href="'+project_url+'bootstrap-4.0.0/dist/css/bootstrap.min.css" type="text/css" rel="stylesheet" media="all">\n');
        printWindow.document.write('\n<link href="'+project_url+'css/print_style/basic_print.css" type="text/css" rel="stylesheet" media="all">\n');
        printWindow.document.write('\n<script src="angularjs/angularjs_1.6.4_angular.min.js"></script>\n');
        printWindow.document.write('\n<link href="'+project_url+'css/print_style/');
        printWindow.document.write(userCSSFile);
        printWindow.document.write('?v='+ Math.random()+'" rel="stylesheet" type="text/css" media="all"/>');


        printWindow.document.write('\n</head>');
        printWindow.document.write('\n<body>');
        printWindow.document.write(divContents);
        if(numberOfCopies==2) {
            printWindow.document.write('\n<hr>');
            printWindow.document.write(divContents);
        }
        printWindow.document.write('\n</body>');
        printWindow.document.write('\n</html>');
        printWindow.document.close();
        printWindow.focus(); // necessary for IE >= 10
        $timeout(function() {
		  printWindow.print();
		}, 1000);
        //printWindow.print();
        //printWindow.close();
    };
});





















