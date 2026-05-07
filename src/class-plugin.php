<?php
/**
 * Main plugin runtime.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

namespace TimVanIersel\RemoveSchema;

use TimVanIersel\RemoveSchema\Admin\AdminAssets;
use TimVanIersel\RemoveSchema\Admin\SettingsPage;
use TimVanIersel\RemoveSchema\Api\Routes;
use TimVanIersel\RemoveSchema\Blocks\BlockRegistrar;
use TimVanIersel\RemoveSchema\Cli\CliRegistrar;
use TimVanIersel\RemoveSchema\Contracts\Service;
use TimVanIersel\RemoveSchema\Infrastructure\AssetLocator;
use TimVanIersel\RemoveSchema\Modules\ScriptModuleRegistrar;
use TimVanIersel\RemoveSchema\PublicSite\PublicAssets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class responsible for bootstrapping all services.
 */
final class Plugin {
	/**
	 * Plugin context value object.
	 *
	 * @var PluginContext
	 */
	private PluginContext $context;

	/**
	 * Asset locator instance.
	 *
	 * @var AssetLocator
	 */
	private AssetLocator $assets;

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->context = new PluginContext(
			REMOVE_SCHEMA_FILE,
			REMOVE_SCHEMA_PATH,
			REMOVE_SCHEMA_URL,
			REMOVE_SCHEMA_VERSION,
			'remove-schema',
			'remove-schema'
		);
		$this->assets  = new AssetLocator( $this->context );
	}

	/**
	 * Bootstrap the plugin.
	 *
	 * @return void
	 */
	public static function boot(): void {
		$plugin = new self();

		foreach ( $plugin->services() as $service ) {
			$service->register();
		}
	}

	/**
	 * Build the service list explicitly.
	 *
	 * @return array<int, Service>
	 */
	private function services(): array {
		return array(
			new SettingsPage( $this->context ),
			new AdminAssets( $this->assets ),
			new PublicAssets( $this->assets ),
			new Routes( $this->context ),
			new CliRegistrar( $this->context ),
			new BlockRegistrar( $this->context, $this->assets ),
			new ScriptModuleRegistrar( $this->context, $this->assets ),
		);
	}
}
