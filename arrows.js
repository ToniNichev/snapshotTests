// Draw arrow

	function drawArow(ctx, x1,y1, x2, y2, lineWidth, arrowDiamer, arowAngle, color, fillColor) {
		var angle = Math.PI / arowAngle;

		var dist=Math.sqrt((x2-x1)*(x2-x1)+(y2-y1)*(y2-y1));
		var ratio=(dist - arrowDiamer) / dist;
		var ratio2=(dist - arrowDiamer / 3) / dist;
		var tox, toy,fromx,fromy;

	    fromx=x1+(x2-x1)*(1-ratio2);
    	fromy=y1+(y2-y1)*(1-ratio2);

		tox=Math.round(x1+(x2-x1)*ratio);
		toy=Math.round(y1+(y2-y1)*ratio);

		ctx.beginPath()
		ctx.lineWidth = lineWidth
		ctx.strokeStyle = color
		ctx.moveTo(fromx,fromy)
		ctx.lineTo(tox,toy)
		ctx.stroke()

		// calculate the angle of the line
		var lineangle=Math.atan2(y2-y1,x2-x1);
		// h is the line length of a side of the arrow head
		var h=Math.abs(arrowDiamer / Math.cos(angle));

		var angle1=lineangle+Math.PI+angle;
		var topx=x2+Math.cos(angle1)*h;
		var topy=y2+Math.sin(angle1)*h;
		var angle2=lineangle+Math.PI-angle;
		var botx=x2+Math.cos(angle2)*h;
		var boty=y2+Math.sin(angle2)*h;

		ctx.beginPath();
		ctx.moveTo(topx,topy);
		ctx.lineTo(botx,boty);

		ctx.lineTo(x2,y2);
		ctx.lineTo(topx,topy);

		if(fillColor == null) {
			ctx.stroke();

		}
		else {
			ctx.fillStyle = fillColor;
			ctx.fill();
		}

		ctx.beginPath();
        ctx.arc(x1, y1, 4, 0, 2 * Math.PI)
        ctx.stroke();
	}

    for(var i in picInfo) {
        var picData = picInfo[i];
        if(typeof picData.url == 'undefined')
            break;
        var curentPicObject = picInfo[i];
        // draw the image
        curentPicObject.canvas = document.getElementById("canvas-" + i);
        curentPicObject.pic = new Image();
        curentPicObject.pic.name = i;
        curentPicObject.pic.src = "screenshots/scr" + i + ".png";
        curentPicObject.pic.onload = function(e) {
            var pic = e.target;
            var id = parseInt(pic.name);
            var picObject = picInfo[id];
            var canvas = picInfo[id].canvas;
            var canvasContext = canvas.getContext("2d");
    
    
           canvasContext.drawImage(pic,0,0);
           // draw markers   
           if(picObject.diffs.length > 0) {
    
                function drawPointer(x, y) {
                    var x2 = x - 10;
                    var y2 = y;
    
                    var x1 = x2 - 50;
                    var y1 = y2 - 50;
    
                    drawArow(canvasContext, 
                               x1,          // x1
                               y1,          // y1
                               x2,         // x2
                               y2,         // y2 
                               3,           // lineWidth 
                               15,           // arrowDiamer 
                               10,           // arowAngle 
                               '#FF0000',   // color 
                               '#FF0000',   // fillColor
                    );   
    
                }
    
                for(var c=0; c < picObject.diffs.length; c = c + 2 ) {
                    var x = picObject.diffs[c];
                    var y = picObject.diffs[c + 1];
                    drawPointer(x, y);    
                }            
           }
        };
    }