var intervals;
$(document).ready(function(){
    let index = 0;
    function start()
    {
        showBid();
        intervals = setInterval(showBid, 60000);
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

