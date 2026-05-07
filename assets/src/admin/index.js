import './style.scss';

document.addEventListener( 'DOMContentLoaded', () => {
	const field = document.getElementById( 'remove_schema_message' );

	if ( ! field ) {
		return;
	}

	field.classList.add( 'remove-schema-admin__field' );
} );
