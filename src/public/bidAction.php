<?php
include '../../vendor/autoload.php';
use Task\Bid\Model\BidModel;
use Task\Bid\DBconnection\Db;
$db = new Db();
$conn = $db->getConnection();
$bidModelObj = new BidModel($conn);
$getLatestHitTime = $bidModelObj->getLatestHitTimeAndBid();
$curTime = microtime(true);
$timeTOCheckForApiHit = 59;
if (isset($_POST['getBidFromApi']) && $_POST['getBidFromApi'] == true) {
    if (($curTime - $getLatestHitTime['time']) > $timeTOCheckForApiHit) {
    $dataRst = $bidModelObj->getLatestBid();
    $bidData = (array)json_decode($dataRst);
    $bid = (array)$bidData['data'];
    $bidModelObj->addNewRecord($bid['bidPrice'], $curTime);
    echo $bid['bidPrice'];
    } else {
        echo $getLatestHitTime['bidPrice'];
    }
}

