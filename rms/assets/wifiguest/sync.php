<?php 
require_once('guest/s/default/params.php');

try {
  $dbh = new PDO('mysql:dbname='.$mysql_db.';host=localhost', $mysql_user, $mysql_pass);
} catch (PDOException $e){
  echo "Erreur !: " . $e->getMessage() . "<br/>";
  die();
}

$sql = "SELECT value FROM params WHERE name = 'RMS_last_id' LIMIT 1";
$query = $dbh->prepare($sql);
$query->execute();
$res = $query->fetch(PDO::FETCH_ASSOC);
if (isset($res['value'])) {
  $localLastId = $res['value'];
  $currLastID = $res['value'];
  $sql = "SELECT * FROM creds WHERE id > " . $currLastID;
  $query = $dbh->prepare($sql);
  $query->execute();
  $res = $query->fetchAll(PDO::FETCH_ASSOC);
} else {
  $sql = "SELECT * FROM creds";
  $query = $dbh->prepare($sql);
  $query->execute();
  $res = $query->fetchAll(PDO::FETCH_ASSOC);
}
if (isset($res) && !empty($res)) {
  $jdata = json_encode($res);
} else {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "$RMSUrl/customers/getLastId/true/$appName");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('custApiKey: ' . $apiKey, 'appName: ' . $appName));
  #curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
  #curl_setopt($ch, CURLOPT_PORT,  443);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);
  if (is_numeric($response)) {
    $lastId = $response;
    if ($lastId < $localLastId) {
      $sql = "UPDATE params SET value = $lastId WHERE name = 'RMS_last_id'";
      $query = $dbh->prepare($sql);
      $query->execute();
    }
  } else {
    echo "ERROR: RMS Server response: ";
    var_dump($server_output);
    echo "RMS last id : ";
    var_dump($response);
    die();
  }
  die ("No data to send\n");
}
  
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "$RMSUrl/customers/record");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, array('data' => $jdata));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('custApiKey: ' . $apiKey, 'appName: ' . $appName));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
#curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
#curl_setopt($ch, CURLOPT_PORT,  443);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$server_output = curl_exec($ch);
$ret = json_decode($server_output, true);
curl_close($ch);
if (isset($ret['lastID'])) {
   if (is_numeric($ret['lastID'])) {
    $lastID = $ret['lastID'];
  } else {
    $sql = "SELECT MAX(id) FROM CREDS";
    $query = $dbh->prepare($sql);
    $query->execute();
    $res = $query->fetch(PDO::FETCH_ASSOC);
    if (isset($res['id'])) {
      $lastID = $res['id'];
    } else {
      die ("error check lastID in table params");
    }
  }
} else {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "$RMSUrl/customers/getLastId/true/$appName");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('custApiKey: ' . $apiKey, 'appName: ' . $appName));
  #curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
  #curl_setopt($ch, CURLOPT_PORT,  443);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_TIMEOUT, 30);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);
  if (is_numeric($response)) {
    $lastID = $response;
  } else {
    echo "ERROR: RMS Server response: ";
    var_dump($server_output);
    echo "RMS last id : ";
    var_dump($response);
    die();
  }
}
$sql = "SELECT value FROM params WHERE name = 'RMS_last_id' LIMIT 1";
$query = $dbh->prepare($sql);
$query->execute();
$res = $query->fetch(PDO::FETCH_ASSOC);
if (isset($res['value'])) {
  $sql = "UPDATE params SET value = $lastID WHERE name = 'RMS_last_id'";
} else {
  $sql = "INSERT INTO params VALUES (NULL, 'RMS_last_id', $lastID)";
}
$query = $dbh->prepare($sql);
$query->execute();

?>