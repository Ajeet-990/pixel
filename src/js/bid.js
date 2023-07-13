var intervals;
$(document).ready(function(){
    let index = 0;
    function start()
    {
        var MILISECONDS_TO_CALL_API_AUTOMATICALLY = 60000;
        var MILISECONDS_TO_UPDATE_BID_PRICE = 10000;
        setInterval(showBid, MILISECONDS_TO_CALL_API_AUTOMATICALLY);
        setInterval(updateBidInBrowser, MILISECONDS_TO_UPDATE_BID_PRICE);
    }
    function sleep(milliseconds) {  
        return new Promise(resolve => setTimeout(resolve, milliseconds));  
     }  
    function updateBidInBrowser() {
        $.ajax({
            type: "POST",
            url: "src/public/bidAction.php",
            data: {updateBidPrice : true},
            success: function(response){
                $('#latestBid').text(response)
            }
        });
    }
    async function showBid() {
        await sleep(2000); 
        $.ajax({
            type: "POST",
            url: "src/public/bidAction.php",
            data: {getBidFromApi : true},
            success: function(response){
                var div = document.getElementById('#showBidData');
                div.innerHTML += '<p> '+index+'] $'+response+'</p>';
            }
        });
        index += 1
    }
    start();
});

