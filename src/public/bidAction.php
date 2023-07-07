<?php
include '../../vendor/autoload.php';
use Task\Bid\Model\BidModel;
use Task\Bid\DBconnection\Db;
$db = new Db();
$conn = $db->getConnection();
$pModelObj = new BidModel($conn);
$dataRst = $pModelObj->getLatestBid();
$bidData = (array)json_decode($dataRst);
$bid = (array)$bidData['data'];
echo $bid['bidPrice'];

