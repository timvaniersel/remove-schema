<?php
/**
 * Post editor schema removal settings.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema\Admin;

use TimVanIersel\RemoveSchema\Contracts\Service;
use TimVanIersel\RemoveSchema\Options\SchemaOptions;
use TimVanIersel\RemoveSchema\PluginContext;
use WP_Post;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds legacy page-specific Remove Schema options to post edit screens.
 */
final class PostEditor implements Service {
	private const NONCE_ACTION = 'remove_schema_save_post_options';
	private const NONCE_NAME   = 'remove_schema_nonce';

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
	 * Register WordPress hooks for the service.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'add_meta_boxes', array( $this, 'addMetaBoxes' ) );
		add_action( 'save_post', array( $this, 'savePost' ) );
	}

	/**
	 * Add the Remove Schema meta box to all post types.
	 *
	 * @return void
	 */
	public function addMetaBoxes(): void {
		foreach ( get_post_types() as $post_type ) {
			add_meta_box(
				'remove-schema',
				__( 'Remove schema', 'remove-schema' ),
				array( $this, 'renderMetaBox' ),
				$post_type,
				'side'
			);
		}
	}

	/**
	 * Render the page-specific options meta box.
	 *
	 * @param WP_Post $post Current post.
	 * @return void
	 */
	public function renderMetaBox( WP_Post $post ): void {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );

		$options = SchemaOptions::normalize_post_options(
			get_post_meta( $post->ID, SchemaOptions::POST_META_KEY, true )
		);
		?>
		<input class="hidden" type="hidden" name="<?php echo esc_attr( $this->context->option_name() ); ?>[fake_field]" value="1" />

		<p>
			<label>
				<input type="checkbox" name="<?php echo esc_attr( $this->context->option_name() ); ?>[keep_schema]" value="1" <?php checked( $options['keep_schema'], 1 ); ?> />
				<strong><?php esc_html_e( 'Turn off remove schema on this page', 'remove-schema' ); ?></strong>
			</label>
		</p>

		<div class="remove-schema-post-options<?php echo ! empty( $options['keep_schema'] ) ? ' is-disabled' : ''; ?>">
			<?php $this->renderPostCheckbox( 'rm_jsonld', __( 'Remove all JSON-LD', 'remove-schema' ), $options ); ?>
			<?php if ( $this->isPluginActive( 'wordpress-seo/wp-seo.php' ) || $this->isPluginActive( 'wordpress-seo-premium/wp-seo-premium.php' ) ) : ?>
				<?php $this->renderPostCheckbox( 'yoast_jsonld', __( 'Remove Yoast JSON-LD', 'remove-schema' ), $options ); ?>
			<?php endif; ?>
			<?php if ( $this->isPluginActive( 'woocommerce/woocommerce.php' ) ) : ?>
				<?php $this->renderPostCheckbox( 'woocommerce_jsonld', __( 'Remove WooCommerce JSON-LD', 'remove-schema' ), $options ); ?>
			<?php endif; ?>
			<?php if ( $this->isPluginActive( 'wp-schema-pro/wp-schema-pro.php' ) ) : ?>
				<?php $this->renderPostCheckbox( 'schema_pro', __( 'Remove Schema pro JSON-LD', 'remove-schema' ), $options ); ?>
			<?php endif; ?>
			<?php $this->renderPostCheckbox( 'microdata', __( 'Remove all Microdata', 'remove-schema' ), $options ); ?>
			<?php $this->renderPostCheckbox( 'rdfa', __( 'Remove all RDFa', 'remove-schema' ), $options ); ?>
		</div>
		<?php
	}

	/**
	 * Save page-specific options.
	 *
	 * @param int $post_id Post ID.
	 * @return void
	 */
	public function savePost( int $post_id ): void {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( ! isset( $_POST[ self::NONCE_NAME ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE_NAME ] ) ), self::NONCE_ACTION ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$raw_options = array();
		$option_name = $this->context->option_name();

		if ( isset( $_POST[ $option_name ] ) && is_array( $_POST[ $option_name ] ) ) {
			$raw_options = array_map(
				'sanitize_text_field',
				wp_unslash( $_POST[ $option_name ] ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			);
		}

		$options = SchemaOptions::sanitize_post_options( $raw_options );

		update_post_meta( $post_id, SchemaOptions::POST_META_KEY, $options );
	}

	/**
	 * Render one post meta checkbox.
	 *
	 * @param string             $key     Option key.
	 * @param string             $label   Field label.
	 * @param array<string, int> $options Current options.
	 * @return void
	 */
	private function renderPostCheckbox( string $key, string $label, array $options ): void {
		?>
		<p>
			<label>
				<input type="checkbox" name="<?php echo esc_attr( $this->context->option_name() ); ?>[<?php echo esc_attr( $key ); ?>]" value="1" <?php checked( $options[ $key ], 1 ); ?> />
				<?php echo esc_html( $label ); ?>
			</label>
		</p>
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
