
(function($)
{
		  
    $.fn.draggable = function (options)
	{
		return this.each(function ()
		{
			new iDraggable( this, options );
		});
	};

			
    if ($.jQTouch)
    {
        $.jQTouch.addExtension(function (jQT){
            
			function binder (e, info)
			{
				info.page.find('.draggable').draggable();
			}
			
			$(document.body)
				.bind('pageInserted', binder);
			
			$(function()
			{
				$('body > *')
					.each(function()
					{
						binder({}, {page: $(this)});
					});
			});
			
			return {};
        });
		
		function iDraggable(el, options)
		{
			var that = this;
			
			this.numberOfTouches = 1;
			
			this.element = el;
			this.setPosition(0,0);
			this.refresh();
			el.style.webkitTransitionTimingFunction = 'cubic-bezier(0, 0, 0.2, 1)';
			this.acceleration = 0.009;
		
			el.addEventListener('touchstart', this, false);
			//moved up here because I didnt see any reason to add and remove them 
			
			window.addEventListener('unload', function ()
			{
				el.removeEventListener('touchstart', that, false);
				window.removeEventListener('unload', arguments.callee, false);
			}, false);
			
			if (options)
			{
				$.extend(this, options);	
			}
		}
		
		iDraggable.prototype = {
			handleEvent: function(e) {
				switch(e.type) {
					case 'touchstart': this.onTouchStart(e); break;
					case 'touchmove': this.onTouchMove(e); break;
					case 'touchend': this.onTouchEnd(e); break;
				}
			},
			
			setPosition: function (x,y) {
				this.positionX = x;
				this.positionY = y;
				this.element.style.webkitTransform = 'translate3d(' + x + 'px, ' + y + 'px, 0)';
				return;
			},
			
			//i combined the getter and setter in order to make this 
			//more forward compatible since that is a deprecated api
			positionX: 0,
			
			positionY: 0,
			
			refresh: function() {
				this.element.style.webkitTransitionDuration = '0';
		
				if( this.element.offsetHeight<this.element.parentNode.clientHeight )
					this.maxScrollY = 0;
				else		
					this.maxScrollY = this.element.parentNode.clientHeight - this.element.offsetHeight;
					
				if( this.element.offsetWidth<this.element.parentNode.clientWidth )
					this.maxScrollX = 0;
				else		
					this.maxScrollX = this.element.parentNode.clientWidth - this.element.offsetWidth;
			},
			
			onTouchStart: function(e) {
				if( e.targetTouches.length != this.numberOfTouches )
					return;
				
				e.preventDefault();
				
				this.refresh();
				
				var theTransform = window.getComputedStyle(this.element).webkitTransform;
				theTransform = new WebKitCSSMatrix(theTransform);
				
				if( theTransform.m42 !== this.positionY || theTransform.m41 !== this.positionX)
					this.setPosition(theTransform.m41,theTransform.m42);
				
				this.startY = e.targetTouches[0].clientY;
				this.startX = e.targetTouches[0].clientX;
				this.moved = false;
				
				//moved
				this.element.addEventListener('touchmove', this, false);
				this.element.addEventListener('touchend', this, false);
		
				//return false;
			},
			
			onTouchMove: function(e) {
				if( e.targetTouches.length != this.numberOfTouches )
					return;
				
				e.preventDefault();
				var topDeltaY = e.targetTouches[0].clientY - this.startY;
				
				var topDeltaX = e.targetTouches[0].clientX - this.startX;
				
				this.setPosition(this.positionX + topDeltaX, this.positionY + topDeltaY);
				
				this.startY = e.targetTouches[0].clientY;
				this.startX = e.targetTouches[0].clientX;
				
				this.moved = true;
			},
			
			onTouchEnd: function(e) {
				//moved
				this.element.removeEventListener('touchmove', this, false);
				this.element.removeEventListener('touchend', this, false);
				e.preventDefault();
		
				if( !this.moved ) {
					var theTarget = e.target, theEvent = document.createEvent("MouseEvents");
					if(theTarget.nodeType == 3) theTarget = theTarget.parentNode;
					theEvent.initEvent('click', true, true);
					theTarget.dispatchEvent(theEvent);
					return false;
				}
		
			}
		};
    }
})(jQuery);