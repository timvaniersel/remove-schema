import './style.scss';

document.addEventListener( 'DOMContentLoaded', () => {
	const tabWrapper = document.querySelector( '.nav-tab-wrapper' );
	const tabBoxes = document.querySelectorAll( '.remove-schema-metaboxes' );

	if ( ! tabWrapper || ! tabBoxes.length ) {
		return;
	}

	const activateTab = ( hash ) => {
		const target = document.querySelector( hash );

		if ( ! target ) {
			return;
		}

		document
			.querySelectorAll( '.nav-tab' )
			.forEach( ( tab ) => tab.classList.remove( 'nav-tab-active' ) );
		tabBoxes.forEach( ( box ) => box.classList.add( 'hidden' ) );

		target.classList.remove( 'hidden' );
		document
			.querySelector( `.nav-tab[href="${ hash }"]` )
			?.classList.add( 'nav-tab-active' );
	};

	if ( window.location.hash ) {
		activateTab( window.location.hash );
	}

	tabWrapper.addEventListener( 'click', ( event ) => {
		const link = event.target.closest( '.nav-tab' );

		if ( ! link ) {
			return;
		}

		event.preventDefault();

		const hash = link.getAttribute( 'href' );

		if ( ! hash ) {
			return;
		}

		activateTab( hash );

		if ( window.history.pushState ) {
			window.history.pushState( null, '', hash );
		} else {
			window.location.hash = hash;
		}
	} );
} );
