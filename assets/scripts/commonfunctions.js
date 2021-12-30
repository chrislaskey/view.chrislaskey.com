//Universal Vars

	var contentWindow;
	var contentWindowWidth;
	var contentContainer;
	var contentWidth;
	var contentChildren;
	var contentCurrent;
	var contentScrolling;
	var contentScrollTime;
	var mouseY;
	var mouseX;
	var isMobile;

//Init
	
	$(function(){
		
		//Set Vars
		contentWindow = $('div#window');
		contentWindowWidth = contentWindow.width();
		contentContainer = $('div#content_container');
		contentCurrent = 0;
		contentScrolling = false;
		contentScrollTime = 400;
		isMobile = false;
		
		//Set Bindings
		bind_window_resize();
		bind_empty_anchors();
		bind_external_anchors();
		bind_message_close();
		bind_form_focus();
		bind_form_hover();
		bind_arrows();
		bind_nsfw();
		//bind_mouse_position();
		bind_content_wheel_scroll();
		bind_content_keyboard_scroll();
		
	});

//Binding Functions
	function bind_empty_anchors(){
		$('a[href="#"]').unbind('click').bind('click', function(){
			return false;
		});
	}
	
	function bind_external_anchors(){
		$('a[rel="external"], a.external, a[href=^http://]')
			.each(function(e){
				$(this).attr('target', '_blank');
			});
	}
	
	function bind_window_resize(){
		$(window).bind('resize', function(){
			contentWindowWidth = contentWindow.width();
			if( !isNaN(contentWidth) ){
				set_content_container_width( contentWidth + contentWindowWidth );
			}
		});
	}
	
	function bind_message_close(){
		$('div.message').each(function(e){
			var message = $(this);
			var close = $(this).find('a.close');
			close
				.unbind('click')
				.bind('click', function(){
					message.fadeOut(200);
				});
		});
	}
	
	function bind_form_focus(){
		$('form input').each(function(e){
			$(this)
				.bind('focus', function(){
					if( !$(this).hasClass('focus') ){
						$(this).addClass('focus');
					}
				})
				.bind('blur', function(){
					if( $(this).hasClass('focus') ){
						$(this).removeClass('focus');
					}
				});
		});
	}
	
	function bind_form_hover(){
		$('form input').each(function(e){
			$(this)
				.bind('mouseenter', function(){
					if( !$(this).hasClass('hover') ){
						$(this).addClass('hover');
					}
				})
				.bind('mouseleave', function(){
					if( $(this).hasClass('hover') ){
						$(this).removeClass('hover');
					}
				});
		});
	}
	
	function bind_nsfw(){
		$('a.content_filter').unbind('click').bind('click', function(){
			var anchor = $('a.content_filter');
			var nsfw = (anchor.attr('rel') == 1) ? 0 : 1;
			
			$.ajax({
				url:'/ajax/nsfw',
				type:'POST',
				data: ({'ajax':true, 'nsfw':nsfw}),
				success: function(data, code){
					if( data == 'success' && code == 'success' ){
						var text = (nsfw == 1) ? 'NSFW' : 'SFW';
						anchor.html(text);
						anchor.attr('rel', nsfw);
						location.reload();
					}
				}
			});
			return false;
		});
	}
	
	function bind_arrows(){
		$('a.content_previous').unbind('click').bind('click', function(){
			content_scroll_previous();
			return false;
		});
		$('a.content_next').unbind('click').bind('click', function(){
			content_scroll_next();
			return false;
		});
	}
	
	function bind_mouse_position(){
		$(window).bind('mousemove', function(e){
			mouseY = e.pageY
			mouseX = e.pageX
		});
	}
	
	function bind_content_wheel_scroll(){
		$(window).mousewheel( function(event, delta){
			if( delta > 0 ){ content_scroll_previous(); } //Up
			else{ content_scroll_next(); } //Down
			return false;
		});
	}
	
	function bind_content_keyboard_scroll(){
		$(document).bind('keyup', function(e){
			switch( e.keyCode ){
				case 37:
					//Left Arrow
					content_scroll_previous();
					break;
				case 39:
					//Right Arrow
					content_scroll_next();
					break;
				default:
					break;
			}
		});
	}

//General Functions
	function content_scroll_previous(){
		if( contentScrolling == false ){
			
			contentScrolling = true;
			var previous = ( contentCurrent == 0 ) ? (contentChildrenSize-1) : contentCurrent-1;
			
			contentWindow.scrollTo(
				contentChildren.eq(previous),
				{
					axis:'x',
					duration:contentScrollTime,
					easing:'easeOutSine',
					onAfter: function(){
						contentScrolling = false;
						contentCurrent = previous;
						update_content_progress();
					}
				}
			);
		}
	}
	
	function content_scroll_next(){
		if( contentScrolling == false ){
			
			contentScrolling = true;
			var next = ( contentCurrent != (contentChildrenSize-1) ) ? contentCurrent+1 : 0;
			
			contentWindow.scrollTo(
				contentChildren.eq(next),
				{
					axis:'x',
					duration:contentScrollTime,
					easing:'easeOutSine',
					onAfter: function(){
						contentScrolling = false;
						contentCurrent = next;
						update_content_progress();
					}
				}
			);
		}
	}
	
	function content_jump_to(eq){
		if( contentScrolling == false ){
			
			eq = parseInt(eq);
			
			if( !isNaN(eq) && eq <= (contentChildren.size()-1) ){
				
				contentScrolling = true;
				
				contentWindow.scrollTo(
					contentChildren.eq(eq),
					{
						axis:'x',
						duration:contentScrollTime,
						easing:'easeOutSine',
						onAfter: function(){
							contentScrolling = false;
							contentCurrent = eq;
							update_content_progress();
						}
					}
				);
				
			}
		}
	}
	
	function update_content_progress(){
		$('input.content_progress').val( contentCurrent+1 );
	}
	
//Interface Functions
	function set_content_container_width(raw_width){
		width = parseInt(raw_width);
		//width = ( isMobile === false ) ? width + contentWindowWidth : width;
		width = width + contentWindowWidth;
		if( width != NaN ){
			contentContainer.css('width', width+'px');
		}
	}
	
	function set_mobile(value){
		if( value === true ){ isMobile = true; }
		if( value === false ){ isMobile = false; }
	}
	
	function set_content_children( children ){
		if( children != null ){
			contentChildren = children;
			contentChildrenSize = contentChildren.size();
			$('div.content_total span').html( contentChildrenSize );
		}
	}
	
	function set_content_scroll_time(raw_time){
		var time = parseInt(raw_time);
		if( time != NaN ){
			contentScrollTime = time;
		}
	}

//Other Functions
	function explode (delimiter, string, limit) {
		// http://kevin.vanzonneveld.net
		// +	 original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +	 improved by: kenneth
		// +	 improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +	 improved by: d3x
		// +	 bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// *	 example 1: explode(' ', 'Kevin van Zonneveld');
		// *	 returns 1: {0: 'Kevin', 1: 'van', 2: 'Zonneveld'}
		// *	 example 2: explode('=', 'a=bc=d', 2);
		// *	 returns 2: ['a', 'bc=d']

		var emptyArray = { 0: '' };

		// third argument is not required
		if ( arguments.length < 2 ||
			typeof arguments[0] == 'undefined' ||
			typeof arguments[1] == 'undefined' ) {
			return null;
		}

		if ( delimiter === '' ||
			delimiter === false ||
			delimiter === null ) {
			return false;
		}

		if ( typeof delimiter == 'function' ||
			typeof delimiter == 'object' ||
			typeof string == 'function' ||
			typeof string == 'object' ) {
			return emptyArray;
		}

		if ( delimiter === true ) {
			delimiter = '1';
		}

		if (!limit) {
			return string.toString().split(delimiter.toString());
		} else {
			// support for limit argument
			var splitted = string.toString().split(delimiter.toString());
			var partA = splitted.splice(0, limit - 1);
			var partB = splitted.join(delimiter.toString());
			partA.push(partB);
			return partA;
		}
	}
	
	function implode (glue, pieces) {
		// http://kevin.vanzonneveld.net
		// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +   improved by: Waldo Malqui Silva
		// +   improved by: Itsacon (http://www.itsacon.net/)
		// +   bugfixed by: Brett Zamir (http://brett-zamir.me)
		// *	 example 1: implode(' ', ['Kevin', 'van', 'Zonneveld']);
		// *	 returns 1: 'Kevin van Zonneveld'
		// *	 example 2: implode(' ', {first:'Kevin', last: 'van Zonneveld'});
		// *	 returns 2: 'Kevin van Zonneveld'

		var i = '', retVal='', tGlue='';
		if (arguments.length === 1) {
			pieces = glue;
			glue = '';
		}
		if (typeof(pieces) === 'object') {
			if (pieces instanceof Array) {
				return pieces.join(glue);
			}
			else {
				for (i in pieces) {
					retVal += tGlue + pieces[i];
					tGlue = glue;
				}
				return retVal;
			}
		}
		else {
			return pieces;
		}
	}