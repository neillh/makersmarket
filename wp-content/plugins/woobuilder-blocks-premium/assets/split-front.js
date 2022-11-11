function wooBkSplitTestsRun() {
	var
		stestWraps = document.querySelectorAll( '[woob-stest-name]:not([woob-stest])' ),
		testsCount = stestWraps.length;

	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) === ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) === 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}

	if ( testsCount ) {
		var
			logImpressions = {
				send: false,
				data: {},
			},
			pid = stestWraps[0].getAttribute( 'woob-stest-name' ).split( '--' )[0],
			stests = getCookie( 'woobk-stest-conv-' + pid );

		stests = stests ? JSON.parse( stests ) : {};

		for ( i = 0; i < stestWraps.length; ++ i ) {
			var
				$t      = stestWraps[i],
				$tests  = $t.querySelectorAll( '[woob-stest-id]' ),
				test = $t.getAttribute( 'woob-stest-name' ).split( '--' ),
				testActive = stests[test];
			test = test[ test.length - 1 ]

			if ( "undefined" !== typeof stests[ test.replace( / /g, '+' ) ] ) {
				testActive = stests[ test.replace( / /g, '+' ) ];
			}

			if ( "undefined" === typeof stests[ test.replace( / /g, '+' ) ] ) {
				testActive = Math.floor( $tests.length * Math.random() );
				logImpressions.data[test] = testActive;
				logImpressions.send = true;
			}

			$t.setAttribute( 'woob-stest', testActive );
		}

		if ( logImpressions.send ) {
			var request = new XMLHttpRequest();
			request.open( 'POST', wbkSplitTesting.restApiUrl, true );
			request.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8' );
			request.send( 'pid=' + pid + '&tests_data=' + JSON.stringify( logImpressions.data ) );
		}
	}
}

document.addEventListener( 'DOMContentLoaded', function ( $ ) {
	wooBkSplitTestsRun();
} );
