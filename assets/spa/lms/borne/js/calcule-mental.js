var num1;
var num2;
var numCorrect = 0;
var timeCountQuestion = 0;
var intIdTimer = -1;

function startGame() {
	
	newQuestion();
	$(".calcul-mental-commencer").fadeOut(100, function() {
		
		$(".logo-game").removeClass('hidden');
		$(".time-reste").removeClass('hidden');
		$(".game").removeClass('hidden');
		$("#timer").show();
		$('.show-reponse').show();
	});
}

$('.show-reponse').click(function(e) {
	checkAnswer();
});

$('.new-question').click(function(e) {
	
	e.preventDefault();
	newQuestion();
});

function checkAnswer() {
  $('.show-reponse').hide();
  $('.result').show();
  $('.result').text(num1 * num2);
  $('.new-question').show();
  clearInterval(intIdTimer);
}
$(document).ready(function() {
});
function timer() {
  var secs = Math.floor(timeCountQuestion/4)%60;
  var mins = Math.floor(timeCountQuestion/240);
  $("#timer").text(mins + ":" + formatTime(secs));
  timeCountQuestion++;
}

function newQuestion() {
	$('.new-question').hide();
	$('.result').hide();
	timeCountQuestion = 0;
	intIdTimer = setInterval(timer, 250);
	num1 = Math.round(Math.random() * 10 + 1);
	num2 = Math.round(Math.random() * 5 + 1);
	$('.show-reponse').show();
	$(".game .operation").text(num1 + "x" + num2 + "=");
	$('.show-reponse').show();
	$('.reponse').hide();
	$("#answer").show();
}

function endGame() {
  $("#game").hide();
  $("#timer").hide();
  showPrize();
  //$("#timerBar").hide();
  $("#timer").removeClass("red-text");
  clearInterval(intId);
  $("#play").fadeIn(1000, function() {})

}

function formatTime(toFormat) {
  if (toFormat < 10) {
    return "0" + toFormat;
  }
  return toFormat;
}

function showPrize() {
  $("#prize").show();
  $("#prize").text("Votre score : "+numCorrect);
}