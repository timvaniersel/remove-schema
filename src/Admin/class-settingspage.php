<?php
/**
 * Admin settings page.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Admin;

use TimVanIersel\RemoveSchema\Contracts\Service;
use TimVanIersel\RemoveSchema\Options\SchemaOptions;
use TimVanIersel\RemoveSchema\PluginContext;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the plugin settings page in the WordPress admin.
 */
final class SettingsPage implements Service {
	private const PAGE_SLUG = 'remove-schema';

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
		add_filter( 'plugin_action_links_' . $this->context->plugin_basename(), array( $this, 'addActionLinks' ) );
	}

	/**
	 * Register the plugin settings with the Settings API.
	 *
	 * @return void
	 */
	public function registerSettings(): void {
		register_setting(
			self::PAGE_SLUG,
			$this->context->option_name(),
			array(
				'default'           => SchemaOptions::normalize_site_options( array() ),
				'sanitize_callback' => array( $this, 'sanitizeOptions' ),
				'type'              => 'array',
			)
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
	 * Add a Settings link to the plugins list row.
	 *
	 * @param array<int|string, string> $links Existing plugin action links.
	 * @return array<int|string, string>
	 */
	public function addActionLinks( array $links ): array {
		$settings_link = sprintf(
			'<a href="%1$s">%2$s</a>',
			esc_url( admin_url( 'options-general.php?page=' . self::PAGE_SLUG ) ),
			esc_html__( 'Settings', 'remove-schema' )
		);

		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Sanitize stored options.
	 *
	 * @param mixed $value Raw option value.
	 * @return array<string, int>
	 */
	public function sanitizeOptions( mixed $value ): array {
		return SchemaOptions::sanitize_site_options( $value );
	}

	/**
	 * Render the settings page HTML.
	 *
	 * @return void
	 */
	public function renderPage(): void {
		$options = SchemaOptions::normalize_site_options( get_option( $this->context->option_name(), array() ) );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Remove Schema', 'remove-schema' ); ?></h1>

			<p>
				<?php esc_html_e( 'Select the Schema that you want to remove from your website. For more information about Schema visit', 'remove-schema' ); ?>
				<a href="https://schema.org" target="_blank" rel="noopener noreferrer">Schema.org</a>.
			</p>

			<h2 class="nav-tab-wrapper">
				<a href="#plugin-theme" class="nav-tab nav-tab-active"><?php esc_html_e( 'Plugin/Theme schema removal', 'remove-schema' ); ?></a>
				<a href="#aggressive" class="nav-tab"><?php esc_html_e( 'Aggressive schema removal', 'remove-schema' ); ?></a>
			</h2>

			<form action="options.php" method="post">
				<?php
				settings_fields( self::PAGE_SLUG );
				do_settings_sections( self::PAGE_SLUG );
				?>

				<div id="plugin-theme" class="wrap columns-2 remove-schema-metaboxes">
					<h2><?php esc_html_e( 'Plugin/Theme schema removal', 'remove-schema' ); ?></h2>

					<?php if ( $this->isPluginActive( 'wordpress-seo/wp-seo.php' ) || $this->isPluginActive( 'wordpress-seo-premium/wp-seo-premium.php' ) ) : ?>
						<?php $this->renderCheckbox( 'yoast_jsonld', __( 'Remove Yoast JSON-LD', 'remove-schema' ), $options ); ?>
					<?php endif; ?>

					<?php if ( $this->isPluginActive( 'woocommerce/woocommerce.php' ) ) : ?>
						<?php $this->renderCheckbox( 'woocommerce_jsonld', __( 'Remove WooCommerce JSON-LD', 'remove-schema' ), $options ); ?>
						<?php $this->renderCheckbox( 'woocommerce_mail_jsonld', __( 'Remove WooCommerce JSON-LD in Emails', 'remove-schema' ), $options ); ?>
					<?php endif; ?>

					<?php if ( $this->isPluginActive( 'wp-schema-pro/wp-schema-pro.php' ) ) : ?>
						<?php $this->renderCheckbox( 'schema_pro', __( 'Remove Schema pro JSON-LD', 'remove-schema' ), $options ); ?>
					<?php endif; ?>

					<?php $this->renderCheckbox( 'generatepress_schema', __( 'Remove GeneratePress schema', 'remove-schema' ), $options ); ?>
					<?php $this->renderCheckbox( 'remove_hentry_schema', __( 'Remove Hentry schema', 'remove-schema' ), $options ); ?>
				</div>

				<div id="aggressive" class="wrap columns-2 remove-schema-metaboxes hidden">
					<h2 class="text-red"><?php esc_html_e( 'Aggressive schema removal', 'remove-schema' ); ?></h2>
					<p><?php esc_html_e( 'Use when other options are not working. Because this may cause problems and can slow down your website when you do not use caching.', 'remove-schema' ); ?></p>

					<?php $this->renderCheckbox( 'rm_jsonld', __( 'Remove all JSON-LD', 'remove-schema' ), $options ); ?>
					<?php $this->renderCheckbox( 'microdata', __( 'Remove all Microdata', 'remove-schema' ), $options ); ?>
					<?php $this->renderCheckbox( 'rdfa', __( 'Remove all RDFa', 'remove-schema' ), $options ); ?>
				</div>

				<?php submit_button( __( 'Save all changes', 'remove-schema' ) ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render one settings checkbox.
	 *
	 * @param string             $key     Option key.
	 * @param string             $label   Field label.
	 * @param array<string, int> $options Current options.
	 * @return void
	 */
	private function renderCheckbox( string $key, string $label, array $options ): void {
		?>
		<fieldset>
			<legend class="screen-reader-text"><span><?php echo esc_html( $label ); ?></span></legend>
			<label for="<?php echo esc_attr( $this->context->option_name() . '-' . $key ); ?>">
				<input
					type="checkbox"
					id="<?php echo esc_attr( $this->context->option_name() . '-' . $key ); ?>"
					name="<?php echo esc_attr( $this->context->option_name() ); ?>[<?php echo esc_attr( $key ); ?>]"
					value="1"
					<?php checked( $options[ $key ], 1 ); ?>
				/>
				<span><?php echo esc_html( $label ); ?></span>
			</label>
		</fieldset>
		<?php
	}

	/**
	 * Check whether a plugin is active on single-site or multisite installs.
	 *
	 * @param string $plugin_path Plugin path relative to the plugins directory.
	 * @return bool
	 */
	private function isPluginActive( string $plugin_path ): bool {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		if ( is_multisite() && is_plugin_active_for_network( $plugin_path ) ) {
			return true;
		}

		return is_plugin_active( $plugin_path );
	}
}
