$(document).ready(function(){ 
    let index = 0;
    let MILISECONDS_TO_CALL_API_AUTOMATICALLY = 60000;
    let canCallRequest=true;
    function start()
    {
        window.onfocus = function () {
            canCallRequest= true; 
            setInterval(showBid, MILISECONDS_TO_CALL_API_AUTOMATICALLY);
          }; 
          
          window.onblur = function () {
            canCallRequest = false; 
          }; 
    }

    function showBid() {
        if(!canCallRequest) return;
        $.ajax({
            type: "POST",
            url: "src/public/bidAction.php",
            data: {getBidFromApi : true},
            success: function(response){
                $('#latestBid').text(response)
                var div = document.getElementById('#showBidData');
                div.innerHTML += '<p> '+index+'] $'+response+'</p>';
            }
        });
        index += 1
    }
    start();
});

