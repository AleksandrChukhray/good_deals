<?php
echo "<script> alert(' Комментарий добавлен! ');</script>";
$RecordID = GETAsStrOrDef("id", "0");
Redirect("/market/cardtovar");
?>