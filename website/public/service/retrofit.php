<?php
$DB_NAME = "simaptapkl";
$DB_USER = "root";
$DB_PASS = "";
$DB_SERVER_LOC = "localhost";
$conn = mysqli_connect($DB_SERVER_LOC, $DB_USER, $DB_PASS, $DB_NAME);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $parameterValue = $_GET["parameterName"];
    switch($parameterValue){
        case "read":
            $sql = "SELECT*FROM nama";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $data = array();
    
                while ($frm = mysqli_fetch_assoc($result)) {
                    array_push($data, $frm);
                }
    
                echo json_encode(array("namas"=>$data));  //data full
                exit();
            }
            break;
    }
}else{
    $parameterValue = $_POST["parameterName"];
    switch($parameterValue){
        case "create":
            $text = $_POST['text'];
            $sql = "INSERT INTO nama(nama) VALUES('$text')";
    
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $respon['message'] = "1";
    
                echo json_encode($respon);
                exit();
            }
            break;
        case "update":
            $text = $_POST['text'];
            $id = $_POST['id'];
            $sql = "UPDATE nama SET nama = '$text' WHERE id = '$id'";
    
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $respon['message'] = "1";
    
                echo json_encode($respon);
                exit();
            }
            break;
        case "delete":
            $id = $_POST['id'];
            $sql = "DELETE FROM nama WHERE id = '$id'";
    
            $result = mysqli_query($conn, $sql);
            if ($result) {
                $respon['message'] = "1";
    
                echo json_encode($respon);
                exit();
            }
            break;
    }
}
