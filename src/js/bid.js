var intervals;
$(document).ready(function(){
    let index = 0;
    function start()
    {
        var MILISECONDS_TO_CALL_API_AUTOMATICALLY = 60000;
        intervals = setInterval(showBid, MILISECONDS_TO_CALL_API_AUTOMATICALLY);
    }
    function showBid() {
        $.ajax({
            type: "POST",
            url: "src/public/bidAction.php",
            data: {startbutton : true},
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

