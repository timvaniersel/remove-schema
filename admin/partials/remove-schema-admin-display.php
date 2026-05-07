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

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">

  <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

  <p><?php _e('Select the Schema that you want to remove from your website. For more information about Schema visit', $this->plugin_name);?> <a target="_blank" href="https://schema.org">Schema.org</a>.</p>

  <h2 class="nav-tab-wrapper">
            <a href="#plugin-theme" class="nav-tab nav-tab-active"><?php _e('Plugin/Theme schema removal', $this->plugin_name);?></a>
            <a href="#aggressive" class="nav-tab"><?php _e('Aggressive schema removal', $this->plugin_name);?></a>
	</h2>
  <form method="post" name="remove_schema_options" action="options.php">

    <?php
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    //Grab all options
    $options = get_option($this->plugin_name);

    // Schema
    $rm_jsonld = $options['rm_jsonld'];
    $yoast_jsonld = $options['yoast_jsonld'];
    $woocommerce_jsonld = $options['woocommerce_jsonld'];
    $woocommerce_mail_jsonld = $options['woocommerce_mail_jsonld'];
    $schema_pro = $options['schema_pro'];
    $microdata = $options['microdata'];
    $rdfa = $options['rdfa'];
    $generatepress_schema = $options['generatepress_schema'];
    $remove_hentry_schema = $options['remove_hentry_schema'];

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

    settings_fields($this->plugin_name);
    do_settings_sections($this->plugin_name);
    ?>

    <div id="plugin-theme" class="wrap columns-2 remove-schema-metaboxes">

    	<h2><?php esc_attr_e( 'Plugin/Theme schema removal', $this->plugin_name ); ?></h2>

          <!-- remove Yoast JSONLD -->
          <?php if (pluginnl_plugin_is_active( 'wordpress-seo/wp-seo.php') || pluginnl_plugin_is_active('wordpress-seo-premium/wp-seo-premium.php') ) { ?>
          <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Remove Yoast JSON-LD', $this->plugin_name); ?></span></legend>
            <label for="<?php echo esc_attr($this->plugin_name); ?>-yoast-json-ld">
              <input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-yoast-json-ld" name="<?php echo esc_attr($this->plugin_name); ?>[yoast_jsonld]" value="1" <?php checked($yoast_jsonld, 1); ?> />
              <span><?php esc_attr_e('Remove Yoast JSON-LD', $this->plugin_name); ?></span>
            </label>
          </fieldset>
          <?php } ?>

          <!-- remove WooCommerce JSONLD -->
          <?php if ( pluginnl_plugin_is_active( 'woocommerce/woocommerce.php' ) ) { ?>
          <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Remove WooCommerce JsonLD', $this->plugin_name); ?></span></legend>
            <label for="<?php echo esc_attr($this->plugin_name); ?>-yoast-json-ld">
              <input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-woocommerce-json-ld" name="<?php echo esc_attr($this->plugin_name); ?>[woocommerce_jsonld]" value="1" <?php checked($woocommerce_jsonld, 1); ?> />
              <span><?php esc_attr_e('Remove WooCommerce JSON-LD', $this->plugin_name); ?></span>
            </label>
          </fieldset>
          <?php } ?>

          <!-- remove  JSONLD in WooCommerce emails -->
          <?php if ( pluginnl_plugin_is_active( 'woocommerce/woocommerce.php' ) ) { ?>
          <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Remove WooCommerce JsonLD in Emails', $this->plugin_name); ?></span></legend>
            <label for="<?php echo esc_attr($this->plugin_name); ?>-woocommerce-mail-json-ld">
              <input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-woocommerce-mail-json-ld" name="<?php echo esc_attr($this->plugin_name); ?>[woocommerce_mail_jsonld]" value="1" <?php checked($woocommerce_mail_jsonld, 1); ?> />
              <span><?php esc_attr_e('Remove WooCommerce JSON-LD in Emails', $this->plugin_name); ?></span>
            </label>
          </fieldset>
          <?php } ?>

          <!-- Remove schema pro schema -->
          <?php if ( pluginnl_plugin_is_active( 'wp-schema-pro/wp-schema-pro.php' ) ) { ?>
          <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Remove Schema pro JSON-LD', $this->plugin_name); ?></span></legend>
            <label for="<?php echo esc_attr($this->plugin_name); ?>-schema-pro">
              <input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-schema-pro" name="<?php echo esc_attr($this->plugin_name); ?>[schema_pro]" value="1" <?php checked($schema_pro, 1); ?> />
              <span><?php esc_attr_e('Remove Schema pro JSON-LD', $this->plugin_name); ?></span>
            </label>
          </fieldset>
          <?php } ?>

          <!-- Remove schema GeneratePress -->
          <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Remove GeneratePress schema', $this->plugin_name); ?></span></legend>
            <label for="<?php echo esc_attr($this->plugin_name); ?>-generatepress">
              <input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-generatepress" name="<?php echo esc_attr($this->plugin_name); ?>[generatepress_schema]" value="1" <?php checked($generatepress_schema, 1); ?> />
              <span><?php esc_attr_e('Remove GeneratePress schema', $this->plugin_name); ?></span>
            </label>
          </fieldset>


          <!-- Remove schema hentry -->
          <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Remove hentry schema', $this->plugin_name); ?></span></legend>
            <label for="<?php echo esc_attr($this->plugin_name); ?>-hentry-schema">
              <input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-hentry-schema" name="<?php echo esc_attr($this->plugin_name); ?>[remove_hentry_schema]" value="1" <?php checked($remove_hentry_schema, 1); ?> />
              <span><?php esc_attr_e('Remove Hentry schema', $this->plugin_name); ?></span>
            </label>
          </fieldset>


    </div>

    <div id="aggressive" class="wrap columns-2 remove-schema-metaboxes hidden">

      <h2 class="text-red"><?php esc_attr_e( 'Aggressive schema removal', $this->plugin_name ); ?></h2>
      <p>Use when other options are not working. Because this may cause problems. And will certanly slow down you're website when you don't use any caching plugins.</p>
      <!-- remove all JSONLD -->
      <fieldset>
        <legend class="screen-reader-text"><span><?php _e('Remove all JSON-LD', $this->plugin_name);?></span></legend>
        <label for="<?php echo esc_attr($this->plugin_name); ?>-rm-json-ld">
          <input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-rm-json-ld" name="<?php echo esc_attr($this->plugin_name); ?>[rm_jsonld]" value="1" <?php checked($rm_jsonld, 1); ?> />
          <span><?php esc_attr_e('Remove all JSON-LD', $this->plugin_name); ?></span>
        </label>

      </fieldset>

      <!-- Remove all Microdata -->
      <fieldset>
        <legend class="screen-reader-text"><span><?php _e('Remove all Microdata', $this->plugin_name); ?></span></legend>
        <label for="<?php echo esc_attr($this->plugin_name); ?>-microdata">
          <input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-microdata" name="<?php echo esc_attr($this->plugin_name); ?>[microdata]" value="1" <?php checked($microdata, 1); ?> />
          <span><?php esc_attr_e('Remove all Microdata', $this->plugin_name); ?></span>
        </label>
      </fieldset>

      <!-- Remove all RDFa -->
      <fieldset>
        <legend class="screen-reader-text"><span><?php _e('Remove all RDFa', $this->plugin_name); ?></span></legend>
        <label for="<?php echo esc_attr($this->plugin_name); ?>-rdfa">
          <input type="checkbox" id="<?php echo esc_attr($this->plugin_name); ?>-rdfa" name="<?php echo esc_attr($this->plugin_name); ?>[rdfa]" value="1" <?php checked($rdfa, 1); ?> />
          <span><?php esc_attr_e('Remove all RDFa', $this->plugin_name); ?></span>
        </label>
      </fieldset>

    </div>

    <?php submit_button(__('Save all changes', $this->plugin_name), 'primary','submit', TRUE); ?>

  </form>

</div>
