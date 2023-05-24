
if($('#phaser-example').length) {

var num1 = "0" ,num2 = "0",num3 = "0";
var text1,text2,text3;

var game = new Phaser.Game(1005, 600, Phaser.AUTO, 'phaser-example', { preload: preload, create: create, render: render });

function preload() {

    game.load.image('grid', app.url.base+'assets/img/paysage.png');
    game.load.image('atari', app.url.base+'assets/img/gateau.png');
    game.load.image('perso-1', app.url.base+'assets/img/perso-1.png');
    game.load.image('perso-2', app.url.base+'assets/img/perso-2.png');
    game.load.image('perso-3', app.url.base+'assets/img/perso-3.png');
    game.load.image('box', app.url.base+'assets/img/box.png');

}

var result = 'Drag a sprite';

function create() {

    // game.add.sprite(0, 0, 'grid');
	game.stage.backgroundColor = "#f6f6f6";

    var group = game.add.group();

    group.inputEnableChildren = true;
	
	
	var style = { font: "65px Arial", fill: "#ff0044", align: "center" };

	text1 = game.add.text(175, 300,  num1, style);
    text1.anchor.set(0.5);

    text2 = game.add.text(510, game.world.centerY,  num2, style);
    text2.anchor.set(0.5);

	text3 = game.add.text(830, game.world.centerY, num3, style);
    text3.anchor.set(0.5);
	
	width = 100;
	for(var i = 0; i < 9; i++) {

		var atari = group.create(width, 10, 'atari');
		//  Enable input and allow for dragging
		atari.inputEnabled = true;
		atari.input.enableDrag();
		atari.events.onDragStart.add(onDragStart, this);
		atari.events.onDragStop.add(onDragStop, this, text1, text2, text3);
		
		
		width += 105;
	}
	
	var atari = group.create(5, 400, 'perso-1');
	var atari = group.create(100, 400, 'box');
	var atari = group.create(340, 400, 'perso-2');
	var atari = group.create(435, 400, 'box');
	var atari = group.create(670, 400, 'perso-3');
	var atari = group.create(755, 400, 'box');

}

function onDown(sprite, pointer) {

    result = "Down " + sprite.key;

    console.log('down', sprite.key);

}

function onDragStart(sprite, pointer) {

    result = "Glissage d'objet " + sprite.key;

}

function onDragStop(sprite, pointer) {
    result = sprite.key + " dropped at x:" + pointer.x + " y: " + pointer.y;

    if (pointer.y > 400)
    {
		if(pointer.x > 100 && pointer.x <= 435){
			num1++;
			text1.setText(num1);
		}
		else if(pointer.x > 435 && pointer.x <= 755){
			num2++;
			text2.setText(num2);
		}
		else if(pointer.x > 755){
			num3++;
			text3.setText(num3);
		}
		
        sprite.input.enabled = false;

        // sprite.sendToBack();
    }

}

function render() {

    // game.debug.text(result, 10, 20);

}
}
