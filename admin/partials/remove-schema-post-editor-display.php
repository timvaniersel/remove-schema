<?php

/**
* Provide a admin area view for the plugin
*
* This file is used to markup the admin-facing aspects of the plugin.
*
* @link       https://plugin.nl/
* @since      1.0.0
*
* @package    Remove_Schema
* @subpackage Remove_Schema/admin/partials
*/
?>

<?php

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
//Grab all options
$options = get_post_meta(get_the_ID(), 'remove_schema_page_specific', true);
if (empty($options)) {
  $options = array();
  $options['keep_schema'] = false;
  $options['rm_jsonld'] = false;
  $options['yoast_jsonld'] = false;
  $options['woocommerce_jsonld'] = false;
  $options['schema_pro'] = false;
  $options['microdata'] = false;
  $options['rdfa'] = false;
}
$keep_schema = $options['keep_schema'];
$rm_jsonld = $options['rm_jsonld'];
$yoast_jsonld = $options['yoast_jsonld'];
$woocommerce_jsonld = $options['woocommerce_jsonld'];
$schema_pro = $options['schema_pro'];
$microdata = $options['microdata'];
$rdfa = $options['rdfa'];

function pluginnl_plugin_is_active( $pluginpath ){
 if ( is_multisite() ) {
    if(is_plugin_active_for_network( $pluginpath ) || is_plugin_active( $pluginpath )){
      $active = true;
    }else{
      $active = false;
    }
  } else {
    $active =  is_plugin_active( $pluginpath ) ? true : false;
  }
  return $active;
}
?>
<input class="hidden" style="display:none;" type="text" id="<?php echo esc_attr($this->plugin_name); ?>-fake-field" name="<?php echo esc_attr($this->plugin_name); ?>[fake_field]" value="1" />

<input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-keep-schema" name="<?php echo esc_attr($this->plugin_name); ?>[keep_schema]" value="1" <?php checked($keep_schema, 1); ?> />
<b><?php esc_attr_e('Turn off remove schema on this page', $this->plugin_name); ?></b></br>

<?php if ($keep_schema): ?>
  <span style="opacity: 0.5">
<?php endif; ?>
<input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-rm-json-ld" name="<?php echo esc_attr($this->plugin_name); ?>[rm_jsonld]" value="1" <?php checked($rm_jsonld, 1); ?> />
<?php esc_attr_e('Remove all JSON-LD', $this->plugin_name); ?></br>

<?php if (pluginnl_plugin_is_active( 'wordpress-seo/wp-seo.php') || pluginnl_plugin_is_active('wordpress-seo-premium/wp-seo-premium.php') ) { ?>
<input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-yoast-json-ld" name="<?php echo esc_attr($this->plugin_name); ?>[yoast_jsonld]" value="1" <?php checked($yoast_jsonld, 1); ?> />
<?php esc_attr_e('Remove Yoast JSON-LD', $this->plugin_name); ?></br>
<?php } ?>

<?php if ( pluginnl_plugin_is_active( 'woocommerce/woocommerce.php' ) ) { ?>
<input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-woocommerce-json-ld" name="<?php echo esc_attr($this->plugin_name); ?>[woocommerce_jsonld]" value="1" <?php checked($woocommerce_jsonld, 1); ?> />
<?php esc_attr_e('Remove WooCommerce JSON-LD', $this->plugin_name); ?></br>
<?php } ?>

<?php if ( pluginnl_plugin_is_active( 'wp-schema-pro/wp-schema-pro.php' ) ) { ?>
<input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-schema-pro" name="<?php echo esc_attr($this->plugin_name); ?>[schema_pro]" value="1" <?php checked($schema_pro, 1); ?> />
<?php esc_attr_e('Remove Schema pro JSON-LD', $this->plugin_name); ?></br>
<?php } ?>

<input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-microdata" name="<?php echo esc_attr($this->plugin_name); ?>[microdata]" value="1" <?php checked($microdata, 1); ?> />
<?php esc_attr_e('Remove all Microdata', $this->plugin_name); ?></br>

<input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-rdfa" name="<?php echo esc_attr($this->plugin_name); ?>[rdfa]" value="1" <?php checked($rdfa, 1); ?> />
<?php esc_attr_e('Remove all RDFa', $this->plugin_name); ?></br>
<br>
<?php if ($keep_schema): ?>
  </span>
<?php endif; ?>
