<?php
namespace Task\Bid\Model;

class BidModel
{
    private $conn;
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getLatestBid() : string
    {
        $headers = ['Token:ab4086ecd47c568d5ba5739d4078988f'];
        $url = "https://dev.pixelsoftwares.com/api.php";
        $fields = ['symbol' => "BTCUSDT"];
        $fields1 = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields1);
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        return ($result);
    }
    
    public function getLatestHitTimeAndBid() : array
    {
        $curTime = time();
        $nexTime = $curTime + 60;
        $limit = 1;
        $getCountStmt = $this->conn->prepare('SELECT * FROM bid_api ORDER BY ID DESC LIMIT ?');
        $getCountStmt->bind_param('i', $limit);
        $getCountStmt->execute();
        $countResult = $getCountStmt->get_result()->fetch_assoc();
        return $countResult;
    }

    public function checkRecord()
    {
        $checkRecord = $this->conn->prepare('SELECT id FROM bid_api');
        $checkRecord->execute();
        $Result = $checkRecord->get_result()->fetch_all(MYSQLI_ASSOC);
        return $Result;
    }

    public function updateHitCount(int $nextHitCount, $time) : bool
    {
        $updatHitCount = $this->conn->prepare('update bid_api set api_hit_count = ? where time = ?');
        $updatHitCount->bind_param('ii', $nextHitCount, $time);
        $updatHitCount->execute();
        if ($updatHitCount->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function addNewRecord(float $bidPrice, float $curTime) : bool
    {
        $first = 1;
        $addStmt = $this->conn->prepare("INSERT INTO `bid_api` (`bidPrice`, `time`, `api_hit_count`) VALUES (?, ?, ?)");
        $addStmt->bind_param("ddi", $bidPrice, $curTime, $first);
        $result = $addStmt->execute();
        return $result;
    }


}