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
    const SECONDS_TO_CHECK_TRAFIC = 60;
    const NUMBER_OF_USERS_TO_ALLOW = 5;
    const COUNT_INCREMENT = 1;

    public function __construct($bidModelObj)
    {
        $this->_loader = new FilesystemLoader(__DIR__ . '/../View');
        $this->_twig = new Environment($this->_loader);
        $this->bidModelObj = $bidModelObj;
    }

    public function getLandingPage(float $bidPrice)
    {
        echo $this->_twig->render('showBidDetails.html.twig', ['latestBid' => $bidPrice]);
    }

}