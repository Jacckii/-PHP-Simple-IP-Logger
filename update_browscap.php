<?php
// CRON as root, to be able to write in /etc/apache2
//
// sudo crontab -e
//
// # m h  dom mon dow   command
//   5 4 * * * /usr/bin/php /home/ubuntu/update_browscap.php >/dev/null 2>&1
//
// every day at 4:05 AM

$eol="\r\n";                           //set end of line - cron

$fileurl = "https://browscap.org/stream?q=PHP_BrowsCapINI";
$verurl = "https://browscap.org/version-number";
$file = "/etc/apache2/browscap.ini";

// Check if file is present
if (file_exists($file)) {
  echo 'File exists, ok.';
}
else {
  $handle = fopen($file, 'w');
  $header = ['000', '000'];
  fputcsv($handle, $header);
  fclose($handle);
}
//Find current version
$fp = fopen($file, "r+");
while (($line = stream_get_line($fp, 1024 * 1024, "\n")) !== false) {
   if(strpos($line,"Version=")===0) {
       list($temp, $curver) = explode("=",$line);
       break;
   }
   else { $curver = 0; }
}
fclose($fp);
if ($curver !== 0) {
  echo("Current browscap.ini file version: " . $curver);
}
else {
  echo("No file version found !");
}

//Get browscap.org current version
$newver = file_get_contents($verurl);
echo($eol . "New browscap.ini file version: " . $newver);
//Update if new version available
if($newver !== $curver){
  if($newver > $curver) {
    if(file_put_contents($file, file_get_contents($fileurl))) {
      echo($eol . "browscap.ini has been updated!");
    }
    else {
      echo($eol . "browscap.ini update failed!");
    }
  }
  else {
  echo($eol . "browscap.ini is up to date!"); 
  }         
}
else {
  echo($eol . "browscap.ini is up to date!"); 
}

echo($eol . "End of Cron job." . $eol);
?>
