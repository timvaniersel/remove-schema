<?php
/**
 * Admin settings page.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Admin;

use TimVanIersel\RemoveSchema\Contracts\Service;
use TimVanIersel\RemoveSchema\PluginContext;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the plugin settings page in the WordPress admin.
 */
final class SettingsPage implements Service {
	private const PAGE_SLUG = 'remove-schema-settings';

	/**
	 * Constructor.
	 *
	 * @param PluginContext $context Plugin context.
	 */
	public function __construct(
		private readonly PluginContext $context
	) {
	}

	/**
	 * Return the admin page slug.
	 *
	 * @return string
	 */
	public static function pageSlug(): string {
		return self::PAGE_SLUG;
	}

	/**
	 * Register WordPress hooks for the service.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_init', array( $this, 'registerSettings' ) );
		add_action( 'admin_menu', array( $this, 'registerPage' ) );
	}

	/**
	 * Register the plugin settings with the Settings API.
	 *
	 * @return void
	 */
	public function registerSettings(): void {
		register_setting(
			'remove_schema',
			$this->context->option_name(),
			array(
				'default'           => array(
					'message' => __( 'Hello from the modern boilerplate.', 'remove-schema' ),
				),
				'sanitize_callback' => array( $this, 'sanitizeOptions' ),
				'type'              => 'array',
			)
		);

		add_settings_section(
			'remove_schema_general',
			__( 'General Settings', 'remove-schema' ),
			'__return_empty_string',
			self::PAGE_SLUG
		);

		add_settings_field(
			'remove_schema_message',
			__( 'Greeting Message', 'remove-schema' ),
			array( $this, 'renderMessageField' ),
			self::PAGE_SLUG,
			'remove_schema_general'
		);
	}

	/**
	 * Register the plugin options page under the Settings menu.
	 *
	 * @return void
	 */
	public function registerPage(): void {
		add_options_page(
			__( 'Remove Schema', 'remove-schema' ),
			__( 'Remove Schema', 'remove-schema' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'renderPage' )
		);
	}

	/**
	 * Sanitize stored options.
	 *
	 * @param mixed $value Raw option value.
	 * @return array<string, string>
	 */
	public function sanitizeOptions( mixed $value ): array {
		$options = is_array( $value ) ? $value : array();

		return array(
			'message' => sanitize_text_field( $options['message'] ?? '' ),
		);
	}

	/**
	 * Render the message settings field.
	 *
	 * @return void
	 */
	public function renderMessageField(): void {
		$options = get_option( $this->context->option_name(), array() );
		$value   = is_array( $options ) ? (string) ( $options['message'] ?? '' ) : '';
		?>
		<input
			class="regular-text"
			id="remove_schema_message"
			name="<?php echo esc_attr( $this->context->option_name() ); ?>[message]"
			type="text"
			value="<?php echo esc_attr( $value ); ?>"
		/>
		<p class="description">
			<?php esc_html_e( 'Used by the starter REST route and CLI command.', 'remove-schema' ); ?>
		</p>
		<?php
	}

	/**
	 * Render the settings page HTML.
	 *
	 * @return void
	 */
	public function renderPage(): void {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Remove Schema', 'remove-schema' ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'remove_schema' );
				do_settings_sections( self::PAGE_SLUG );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
