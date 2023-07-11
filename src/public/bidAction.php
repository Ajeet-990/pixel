<?php
include '../../vendor/autoload.php';
use Task\Bid\Model\BidModel;
use Task\Bid\DBconnection\Db;
$db = new Db();
$conn = $db->getConnection();
$bidModelObj = new BidModel($conn);
$dataRst = $bidModelObj->getLatestBid();
$bidData = (array)json_decode($dataRst);
$bid = (array)$bidData['data'];
$curTime = time();
$bidModelObj->addNewRecord($bid['bidPrice'], $curTime);
echo $bid['bidPrice'];

