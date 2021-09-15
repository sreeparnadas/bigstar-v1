<?php
$servername = "localhost";
$username = "gamepane_bigstar_root";
$password = 'PC9L*0ys._$y';
$dbname = "gamepane_bigstar_db";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$argv = $conn->query("SELECT * FROM `next_game_draws` where id=1");
foreach($argv as $row)
{
     $nextDrawId = $row['next_draw_id'];    //next draw er id
     $drawId = $row['last_draw_id'];        // eta id of last draw, jetar result generate korte hobe ekhon (starting from 23)
     $lastDrawSerialNumber = $row['last_draw_serial_number'];       //eta jei draw er result generate hobe taar serial number (starting from 1)
}

$sql = "UPDATE draw_master SET active = IF(draw_master_id=$nextDrawId, 1,0)";
$sql2= "call insert_2d_game_result_details($drawId);";
if (mysqli_query($conn, $sql)) {
    //echo "Draw time updated</br>";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

if (mysqli_query($conn, $sql2)) {
    //echo "Result added</br>";
} else {
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}


$count_draw = $conn->query("SELECT count(*) as total FROM `draw_master`");
foreach($count_draw as $row)
{
     $total_draw = $row['total'];
}

if($lastDrawSerialNumber==$total_draw){
    $nextDrawId = 23;
    $lastDrawSerialNumber = 1;
}
else{
    $nextDrawId = $nextDrawId+1;
    $lastDrawSerialNumber = $lastDrawSerialNumber + 1;
}
    
if($drawId==$total_draw)
    $drawId = 23;
else
    $drawId = $drawId+1;

$sql3 = "UPDATE next_game_draws SET next_draw_id = $nextDrawId,last_draw_id = $drawId";

if (mysqli_query($conn, $sql3)) {
    //echo "Next Draw time updated</br>";
} else {
    echo "Error: " . $sql3 . "<br>" . mysqli_error($conn);
}

mysqli_close($conn);
?> 