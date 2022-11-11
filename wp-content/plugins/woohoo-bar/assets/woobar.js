!function () {
	function setCookie(cname, cvalue) {
		var d = new Date();
		d.setTime(d.getTime() + (7*24*3600*1000));
		var expires = "expires="+ d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}

	document.querySelectorAll( '.woohoo-bar-close-btn' ).forEach( function ( btn ) {
		var $wrap = CaxtonUtils.closest( btn, function ( el ) { return el.classList.contains( 'woohoo-bar-wrap' ) } );
		$wrap.querySelector( '.woohoo-bar' ).appendChild( btn );
		$wrap.addEventListener( 'click', function( e ) {
			if ( e.target.classList.contains( 'woohoo-bar-close-btn' ) ) {
				e.currentTarget.style.display = 'none';
				document.querySelectorAll( '[data-bar="' + e.currentTarget.id + '"]' ).forEach( function( el ) {
					el.style.display = 'none';
				} )
			}
		} )
	} );

	document.querySelectorAll( '.woohoo-bar-wrap' ).forEach( function ( bar ) {
		var clsList = bar.classList;
		if ( clsList.contains( 'woohoo-bar-' ) || clsList.contains( 'woohoo-bar-top-fixed' ) ) {
			document.body.insertBefore(bar ,document.body.children[0]);
		} else {
			document.body.appendChild( bar );
		}

		if ( -1 < clsList.value.indexOf( '-fixed' ) ) {
			var spacer = document.createElement( "DIV" );
			spacer.style.height = bar.offsetHeight + 'px';
			spacer.setAttribute( 'class', 'woohoob-bar-spacer' );
			spacer.setAttribute( 'data-bar', bar.id );
			bar.parentNode.insertBefore( spacer, bar );
			bar.classList.add( 'woohoo-bar-fix' );
		}
	} );

	var countDownFinishCallbacks = {
		hide: function ( counter ) {
			alert( 'Time to hide the counter bar.' );
			var bar = CaxtonUtils.closest( counter, function ( el ) { return el.classList.contains( 'woohoo-bar-wrap' ) } );
			bar.style.display = 'none';
			console.log( counter );
		},
	};

	function counterFinished( counter ) {
		var finish = counter.getAttribute( 'data-finish' );
		if ( countDownFinishCallbacks[finish] ) {
			countDownFinishCallbacks[finish]( counter );
		}
	}

	// region WooHoo Countdown
	document.querySelectorAll( '.woohoo-bar-countdown-counter' ).forEach( function ( salesCounter ) {
		var
			date      = salesCounter.getAttribute( 'data-time-end' ),
			timeParts = ['days', 'hours', 'minutes', 'seconds'],
			timeEls   = {},
			timeNow, diff;

		if ( ! date ) {
			var duration = parseInt( salesCounter.getAttribute( 'data-time-duration' ) );
			date = getCookie( 'woobarTime' + duration );
			if ( ! date ) {
				date = Math.floor( Date.now() / 1000 ) + duration;
				setCookie( 'woobarTime' + duration, date );
			}
		}

		for ( var i = 0; i < timeParts.length; i ++ ) {
			timeEls[timeParts[i]] = {
				circ: salesCounter.querySelector( '.woohoo-bar-timr-arc-' + timeParts[i] ),
				num : salesCounter.querySelector( '.woohoo-bar-timr-number-' + timeParts[i] ),
			};
		}

		timeEls['days'].max = 31;
		timeEls['hours'].max = 24;
		timeEls['minutes'].max = 60;
		timeEls['seconds'].max = 60;

		timeNow = Math.floor( Date.now() / 1000 );
		diff    = Math.max( 0, date - timeNow );

		if ( diff < 1 ) {
			counterFinished( salesCounter );
		} else {
			var thisInterval = setInterval( function () {
				timeNow = Math.floor( Date.now() / 1000 );
				diff    = Math.max( 0, date - timeNow );

				timeEls['days'].val = Math.floor( diff / (60 * 60 * 24) );
				timeEls['hours'].val = Math.floor( diff % (60 * 60 * 24) / (60 * 60) );
				timeEls['minutes'].val = Math.floor( diff % (60 * 60) / 60 );
				timeEls['seconds'].val = Math.floor( diff % 60 );

				for ( var j = 0; j < timeParts.length; j ++ ) {
					var els = timeEls[timeParts[j]];
					if ( els.circ ) els.circ.setAttribute( 'stroke-dasharray', els.val * 100 / els.max + ',100' );
					if ( els.num ) els.num.innerHTML = els.val;
				}

				if ( ! diff ) {
					counterFinished( salesCounter );
					clearInterval( thisInterval );
				}
			}, 1000 );

		}
	} );
	// endregion WooHoo Countdown

}();