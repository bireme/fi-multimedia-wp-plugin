<?php
function multimedia_page_admin() {

    $config = get_option('multimedia_config');

    ?>
    <div class="wrap">
            <div id="icon-options-general" class="icon32"></div>
            <h2><?php _e('FI-Multimedia Plugin Options', 'multimedia'); ?></h2>

            <form method="post" action="options.php">

                <?php settings_fields('multimedia-settings-group'); ?>

                <table class="form-table">
                    <tbody>
                        <tr valign="top">
                            <th scope="row"><?php _e('Plugin page', 'multimedia'); ?>:</th>
                            <td><input type="text" name="multimedia_config[plugin_slug]" value="<?php echo ($config['plugin_slug'] != '' ? $config['plugin_slug'] : 'multimedia'); ?>" class="regular-text code"></td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"><?php _e('Filter query', 'multimedia'); ?>:</th>
                            <td><input type="text" name="multimedia_config[initial_filter]" value='<?php echo $config['initial_filter'] ?>' class="regular-text code"></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Search form', 'multimedia'); ?>:</th>
                            <td>
                                <input type="checkbox" name="multimedia_config[show_form]" value="1" <?php if ( $config['show_form'] == '1' ): echo ' checked="checked"'; endif;?> >
                                <?php _e('Show search form', 'multimedia'); ?>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Disqus shortname', 'multimedia'); ?>:</th>
                            <td><input type="text" name="multimedia_config[disqus_shortname]" value="<?php echo $config['disqus_shortname'] ?>" class="regular-text code"></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('AddThis profile ID', 'multimedia'); ?>:</th>
                            <td><input type="text" name="multimedia_config[addthis_profile_id]" value="<?php echo $config['addthis_profile_id'] ?>" class="regular-text code"></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php _e('Google Analytics code', 'multimedia'); ?>:</th>
                            <td><input type="text" name="multimedia_config[google_analytics_code]" value="<?php echo $config['google_analytics_code'] ?>" class="regular-text code"></td>
                        </tr>
                    </tbody>
                </table>

                <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                </p>

            </form>
        </div>

        <?php
}
?>
