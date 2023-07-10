<?php
namespace Task\Bid\Controller;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class BidController
{
    private $_loader;
    private $_twig;
    private $bidModelObj;
    //I am allowing 5 users per secoud to access the web page. If more than 5 users access the page within a second then other user will get high traffic message.
    const SECONDS_TO_CHECK_TRAFIC = 60;
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
        $curTime = time();
        $secondsTocheck = BidController::SECONDS_TO_CHECK_TRAFIC;
        $numberOfUsers = BidController::NUMBER_OF_USERS_TO_ALLOW;
        $increment = BidController::COUNT_INCREMENT;
        $latestBid = (array)json_decode($this->bidModelObj->getLatestBid());
        $data = (array)$latestBid['data'];
        $countResult = $this->bidModelObj->getLatestHitTime();
        $nextHitCount = $countResult['api_hit_count'] + $increment;
        if (($countResult['time'] + $secondsTocheck) >= $curTime) {
            if ($countResult['api_hit_count'] < $numberOfUsers) {
                echo $this->_twig->render('showBidDetails.html.twig', ['latestBid' => $data['bidPrice']]);
                $this->bidModelObj->updateHitCount($nextHitCount, $countResult['time']);
            } else {
                $this->bidModelObj->updateHitCount($nextHitCount, $countResult['time']);
                echo "<h2>Too high traffic. Please wait for some time</h2>";
            }
        } else {
            echo $this->_twig->render('showBidDetails.html.twig', ['latestBid' => $data['bidPrice']]);
            $this->bidModelObj->addNewRecord($curTime);
        }
    }

}