<?php
use Task\Bid\DBconnection\Db;
use Task\Bid\Controller\BidController;
use Task\Bid\Model\BidModel;

require_once __DIR__ . '/../../vendor/autoload.php';
$db = new Db();
$conn = $db->getConnection();
$bidModelObj = new BidModel($conn);
$pControllerObj = new BidController($bidModelObj);

$curTime = time();
$secondsTocheck = BidController::SECONDS_TO_CHECK_TRAFIC;
$numberOfUsers = BidController::NUMBER_OF_USERS_TO_ALLOW;
$increment = BidController::COUNT_INCREMENT;
$countResult = $bidModelObj->getLatestHitTimeAndBid();
$nextHitCount = $countResult['api_hit_count'] + $increment;
if (($countResult['time'] + $secondsTocheck) >= $curTime) {
    if ($countResult['api_hit_count'] < $numberOfUsers) {
        $pControllerObj->getLandingPage($countResult['bidPrice']);
        $bidModelObj->updateHitCount($nextHitCount, $countResult['time']);
    } else {
        $bidModelObj->updateHitCount($nextHitCount, $countResult['time']);
        echo "<h2>Too high traffic. Please wait for some time</h2>";
    }
} else {
    $pControllerObj->getLandingPage($countResult['bidPrice']);
}
