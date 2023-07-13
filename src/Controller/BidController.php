<?php
namespace Task\Bid\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class BidController
{
    private $_loader;
    private $_twig;
    private $bidModelObj;
    //I am allowing 5 users per secoud to access the web page. If more than 5 users access the page within a second then other user will get service from the cookie.
    const SECONDS_TO_CHECK_TRAFIC = 1;
    const NUMBER_OF_USERS_TO_ALLOW = 5;
    const COUNT_INCREMENT = 1;

    public function __construct($bidModelObj)
    {
        $this->_loader = new FilesystemLoader(__DIR__ . '/../View');
        $this->_twig = new Environment($this->_loader);
        $this->bidModelObj = $bidModelObj;
    }

    public function getLandingPage()
    {
        $curTime = microtime(true);
        $secondsTocheck = BidController::SECONDS_TO_CHECK_TRAFIC;
        $numberOfUsers = BidController::NUMBER_OF_USERS_TO_ALLOW;
        $increment = BidController::COUNT_INCREMENT;
        $checkRecordNum = $this->bidModelObj->checkRecord();
        if (count($checkRecordNum) > 0) {
            $countResult = $this->bidModelObj->getLatestHitTimeAndBid();
            $nextHitCount = $countResult['api_hit_count'] + $increment;
            $cookie_name = "bidPrice";
            $cookie_value = $countResult['bidPrice'];
            setcookie($cookie_name, $cookie_value, $countResult['time'] + 60, "/");
            if (($countResult['time'] + $secondsTocheck) >= $curTime) {
            if ($countResult['api_hit_count'] < $numberOfUsers) {
                echo $this->_twig->render('showBidDetails.html.twig', ['latestBid' => $countResult['bidPrice']]);
                $this->bidModelObj->updateHitCount($nextHitCount, $countResult['time']);
            } else {
                echo $this->_twig->render('showBidDetails.html.twig', ['latestBid' => $_COOKIE[$cookie_name]]);

                $this->bidModelObj->updateHitCount($nextHitCount, $countResult['time']);
            }
            } else {
                echo $this->_twig->render('showBidDetails.html.twig', ['latestBid' => $countResult['bidPrice']]);
            } 
            
        } else {
            $dataRst = $this->bidModelObj->getLatestBid();
            $bidData = (array)json_decode($dataRst);
            $bid = (array)$bidData['data'];
            $this->bidModelObj->addNewRecord($bid['bidPrice'], $curTime);
            echo $this->_twig->render('showBidDetails.html.twig', ['latestBid' => $bid['bidPrice']]);
        }
    }

}