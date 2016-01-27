<!DOCTYPE html>

<html>

<?php

require_once('Backend.php');

?>

<head>

<script src='http://code.jquery.com/jquery-2.2.0.min.js'></script>
<link rel="stylesheet" type="text/css" href="Include/Master.css" />

<?php if(isset($PreloadJS)) { echo "<script type='text/javascript' src='Scripts/Base/" . $PreloadJS . "'></script>"; } ?>

<title><?php echo $PageTitle; ?></title>

<meta charset="UTF-8">
<meta name='viewport' content='width=device-width'>
</head>

<?php

echo "<body id='" . $BodyID . "' vocab='http://purl.org/dc/terms/'>";

?>
