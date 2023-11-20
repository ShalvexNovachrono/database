let counter = 60;

setInterval(function() {
    if (counter <= 0) {
        getQuickLoginCode();
        counter = 60;
    } else {
        counter--;
        document.getElementById("timer").innerText = counter;
    }
}, 1000);


function getQuickLoginCode() {
    $.ajax({
		url:'../pages/qlcode-worker.php',
      data: {qrcode: ""},
		method: 'post',
		success: function(data) {
			$('#qlcode').html(data);
		}
    })
}
getQuickLoginCode();