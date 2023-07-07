<?php
use Task\Bid\DBconnection\Db;
use Task\Bid\Controller\BidController;
use Task\Bid\Model\BidModel;

require_once __DIR__ . '/../../vendor/autoload.php';
$db = new Db();
$conn = $db->getConnection();
$pModelObj = new BidModel($conn);
$pControllerObj = new BidController($pModelObj);

$pControllerObj->getLandingPage();