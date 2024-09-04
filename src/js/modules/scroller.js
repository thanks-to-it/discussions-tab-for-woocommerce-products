const scroller = {
	init: function () {
		jQuery( 'body' ).on( 'alg_dtwp_comments_loaded', function () {
			setTimeout( scroller.scrollByAnchor, 200 );
		} );
		jQuery( 'document' ).ready( function () {
			setTimeout( scroller.scrollByAnchor, 200 );
		} );
		window.addEventListener( 'hashchange', scroller.scrollByAnchor );
	},
	scrollByAnchor: function () {
		let currentURL = window.location.href;
		let target = jQuery( 'a[href*="' + currentURL + '"]' );
		if ( window.location.hash.length && target.length ) {
			const element = target.closest( '.comment' )[ 0 ];
			const offset = 145;
			var comments = document.querySelectorAll( '.comment.' + alg_dtwp.commentTypeID );
			comments.forEach( function ( item ) {
				item.classList.remove( 'alg-dtwp-anchor-source' );
			} );
			const topPos = element.getBoundingClientRect().top + window.pageYOffset - offset;
			window.scrollTo( {
				top: topPos,
				behavior: 'smooth'
			} );
			setTimeout( function () {
				element.classList.add( 'alg-dtwp-anchor-source' );
			}, 500 );
		}
	}
}
module.exports = scroller;