var swipe={
	element:document.getElementById('day-sched').getElementsByClassName('d-monday')[0],
	get nPages(){return swipe.element.parentNode.childNodes.length;},
	get wPages(){return swipe.element.offsetWidth;},
	touchInit:{x:0,y:0},
	touchPosition:{x:0,y:0},
	touchLast:{x:0,y:0},
	elementInit:{x:0,y:0},
	swipping:false,
	elementPosition:{
		get x(){return swipe.element.style.marginLeft=='' ? parseInt(getComputedStyle(swipe.element).getPropertyValue('margin-left')) : parseInt(swipe.element.style.marginLeft);},
		set x(val){swipe.element.style.marginLeft=val+'px';},
		get y(){return swipe.element.style.marginTop=='' ? parseInt(getComputedStyle(swipe.element).getPropertyValue('margin-top')) : parseInt(swipe.element.style.marginTop);},
		set y(val){swipe.element.style.marginTop=val+'px';}
	},
	start:function(event) {
		swipe.touchInit={x:event.changedTouches[0].pageX,y:event.changedTouches[0].pageY};
		swipe.touchLast=swipe.touchInit;
		swipe.elementInit={
			x:swipe.elementPosition.x,
			y:swipe.elementPosition.y
		};
	},
	move:function(event) {
		swipe.touchPosition={x:event.changedTouches[0].pageX,y:event.changedTouches[0].pageY};
		var deltaX=Math.pow(Math.abs(swipe.touchLast.x-swipe.touchPosition.x),1);
		var newPosition=swipe.elementInit.x+(swipe.touchPosition.x-swipe.touchInit.x)*deltaX;
		if(newPosition<swipe.elementInit.x-swipe.wPages) newPosition=swipe.elementInit.x-swipe.wPages;
		else if(newPosition>swipe.elementInit.x+swipe.wPages) newPosition=swipe.elementInit.x+swipe.wPages;
		if((Math.abs(swipe.touchPosition.y-swipe.touchLast.y)<=3 || swipe.swipping) && newPosition<=0 && newPosition>=-(swipe.nPages-1)*swipe.wPages) {
			swipe.elementPosition.x=newPosition;
			swipe.touchLast=swipe.touchPosition;
			if(!swipe.swipping) swipe.swipping=true;
			event.preventDefault(); 
		}
		else swipe.end();
	},
	end:function() {
		swipe.swipping=false;
		for(var i=0; i<swipe.nPages; i++) {
			var e=[-i*swipe.wPages+swipe.wPages/2,-i*swipe.wPages-swipe.wPages/2];
			if(i==0) e[0]=Infinity;
			if(i+1>=swipe.nPages) e[1]=-Infinity;
			if(swipe.elementPosition.x<e[0] && swipe.elementPosition.x>=e[1]) {
				swipe.elementPosition.x=-i*swipe.wPages;
				document.getElementById('date').innerHTML='';
				document.getElementById('date').appendChild(document.createTextNode(swipe.element.parentNode.getElementsByClassName('day')[i].getAttribute('data-date')));
				break;
			}
		}
	}
};

window.onscroll=function() {
	if(document.body.scrollTop>=260) document.getElementById('date').className='show';
	else document.getElementById('date').className='';
}

for(var i=0; i<document.getElementById('input').getElementsByTagName('select').length; i++) {
	document.getElementById('input').getElementsByTagName('select')[i].onchange=function() {
		document.getElementById('input').getElementsByTagName('form')[0].submit();
	}
}