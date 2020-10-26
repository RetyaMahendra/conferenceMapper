<?php
header('Content-Type: application/json');
//header("Content-Type: text/plain");
$conference = $_REQUEST['conference'];
$id = $_REQUEST['id'];


$db_host = 'db';
$db_username = 'user';
$db_password = 'test';
$db_name = 'myDb';
$db_table = 'jitsiapi';
//$db_port = 6603;
$digits = ceil(log10($id));

// Set blank variable if conference contains spaces or is missing "@conference."
if (preg_match('/\s/',$conference) > 0) {
    $conference = '';
} else {
    $conference = $conference;
}
if (strpos($conference, '@conference.') !== false) {
    $conference = $conference;
} else {
    $conference = '';
}

// Create connection
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


//Check that ID is numeric & 6 digits in length
if (is_numeric($id) && $digits = 6) {

$sql = $conn->prepare("SELECT id, conference FROM $db_table WHERE id=?");
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
     echo "{\"message\":\"Successfully retrieved conference mapping\",\"id\":" . $row["id"]. ",\"conference\":\"" . $row["conference"]."\"}";

  }
} else {
  echo "{\"message\":\"No conference mapping was found\",\"id\":$id,\"conference\":false}";
}

} else {

$sql = $conn->prepare("SELECT id, conference FROM $db_table WHERE conference=?");
$sql->bind_param("s", $conference);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
 echo "{\"message\":\"Successfully retrieved conference mapping\",\"id\":" . $row["id"]. ",\"conference\":\"" . $row["conference"]."\"}";

}
} else {

if ($conference == NULL) { 
echo "{\"message\":\"No conference or id provided\",\"conference\":false,\"id\":false}";
} else {

// Insert Conference
$sql = $conn->prepare("INSERT INTO $db_table (conference) VALUES (?)");
$sql->bind_param("s", $conference);
$sql->execute();
$result = $sql->execute();

// Return resulting data
$sql = $conn->prepare("SELECT id, conference FROM $db_table WHERE conference=?");
$sql->bind_param("s", $conference);
$sql->execute();
$result = $sql->get_result();

while($row = $result->fetch_assoc()) {
 echo "{\"message\":\"Successfully retrieved conference mapping\",\"id\":" . $row["id"]. ",\"conference\":\"" . $row["conference"]."\"}";
}
}
}
}
$conn->close();
?>
