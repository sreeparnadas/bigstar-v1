<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<script src="jquery-3.3.1/jquery-ui.min.js"></script>
<script src="angularjs/angularjs_1.6.4_angular.min.js"></script>
<script src="angularjs/angularjs_1.6.4_angular-route.js"></script>
<script src="angularjs/autocomplete.js"></script>
<script src="angularjs/angular-md5.js"></script>
<script src="angularjs/angular-timer.min.js"></script>
<script src="angularjs/popper.js"></script>
<script src = "node_modules/moment/min/moment.min.js"></script>


<!--Barcode-->
<script src="node_modules/angular-barcode/dist/angular-barcode.js"></script>





<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="bootstrap-4.0.0/dist/js/bootstrap.min.js"></script>
<script src="bootstrap-4.0.0/js/src/collapse.js"></script>
<script src="js/parallax.min.js"></script>
<script src="js/wow.min.js"></script>
<script src="js/jquery.easing.min.js"></script>
<script type="text/javascript" src="js/fliplightbox.min.js"></script>
<script src="js/functions.js"></script>




<script src="js/md5/md5_js.js"></script>

<script>
    wow = new WOW({}).init();
</script>


<script>
    // Get the modal
    var modal = document.getElementById('id01');

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
<!--<script src="js/general_script.js"></script>-->
<script type="text/javascript">
    var data = [{a:1,b:10}, {a:2,b:20}, {a:1,b:30}];
    var res = alasql('SELECT a, SUM(b) AS b FROM ? GROUP BY a',[data]);
    console.log(res);
</script>


<script src="js/login_script.js"></script>

<script src="angularjs_controller/myAppController.js"></script>
<!--<script src="angularjs_controller/kolkataController.js"></script>-->
<script src="angularjs_controller/playController.js"></script>
<script src="angularjs_controller/loginController.js"></script>
<script src="angularjs_controller/adminController.js"></script>
<script src="angularjs_controller/stockistController.js"></script>
<script src="angularjs_controller/terminalController.js"></script>
<script src="angularjs_controller/stockistLimitController.js"></script>
<script src="angularjs_controller/terminalLimitController.js"></script>
<script src="angularjs_controller/terminalLimitController.js"></script>
<script src="angularjs_controller/terminalLimitController.js"></script>
<script src="angularjs_controller/payoutSettingsController.js"></script>
<script src="angularjs_controller/manualResultController.js"></script>
<script src="angularjs_controller/reportTerminalController.js"></script>
<script src="angularjs_controller/custSalesReportController.js"></script>
<script src="angularjs_controller/barcodeReportController.js"></script>
<script src="angularjs_controller/sessionColseTerminal.js"></script>

<!--<script src="bootstrap-select-1.13.2/dist/js/bootstrap-select.min.js"></script>-->



</body>
</html>