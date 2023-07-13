<?php
use Task\Bid\DBconnection\Db;
use Task\Bid\Controller\BidController;
use Task\Bid\Model\BidModel;

require_once __DIR__ . '/../../vendor/autoload.php';
$db = new Db();
$conn = $db->getConnection();
$bidModelObj = new BidModel($conn);
$pControllerObj = new BidController($bidModelObj);

$curTime = microtime(true);
$secondsTocheck = BidController::SECONDS_TO_CHECK_TRAFIC;
$numberOfUsers = BidController::NUMBER_OF_USERS_TO_ALLOW;
$increment = BidController::COUNT_INCREMENT;
$checkRecordNum = $bidModelObj->checkRecord();
if (count($checkRecordNum) > 0) {
    $countResult = $bidModelObj->getLatestHitTimeAndBid();
    $nextHitCount = $countResult['api_hit_count'] + $increment;
    $cookie_name = "bidPrice";
    $cookie_value = $countResult['bidPrice'];
    setcookie($cookie_name, $cookie_value, $countResult['time'] + 60, "/");
    if (($countResult['time'] + $secondsTocheck) >= $curTime) {
    if ($countResult['api_hit_count'] < $numberOfUsers) {
        $pControllerObj->getLandingPage($countResult['bidPrice']);
        $bidModelObj->updateHitCount($nextHitCount, $countResult['time']);
    } else {
        $pControllerObj->getLandingPage($_COOKIE[$cookie_name]);
        $bidModelObj->updateHitCount($nextHitCount, $countResult['time']);
    }
    } else {
        $pControllerObj->getLandingPage($countResult['bidPrice']);
    } 
    
} else {
    $dataRst = $bidModelObj->getLatestBid();
    $bidData = (array)json_decode($dataRst);
    $bid = (array)$bidData['data'];
    $bidModelObj->addNewRecord($bid['bidPrice'], $curTime);
    $pControllerObj->getLandingPage($bid['bidPrice']);
}
