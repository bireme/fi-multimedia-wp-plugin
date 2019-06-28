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
                        <tr valign="top">
                            <th scope="row"><?php _e('Sidebar order', 'multimedia');?>:</th>

                            <?php
                              if(!isset($config['available_filter'])){
                                $config['available_filter'] = 'Collection;Subjects;Media type';
                                $order = explode(';', $config['available_filter'] );

                              }else {
                                $order = explode(';', $config['available_filter'] );
                            }

                            ?>

                            <td>


                              <table border=0>
                                <tr>
                                <td >
                                    <p align="right"><?php _e('Available', 'multimedia');?><br>
                                      <ul id="sortable1" class="droptrue">
                                      <?php
                                      if(!in_array('Collection', $order) && !in_array('Collection ', $order) ){
                                      	echo '<li class="ui-state-default" id="Collection">'.translate('Collection','multimedia').'</li>';
                                      }
                                      if(!in_array('Subjects', $order) && !in_array('Subjects ', $order) ){
                                      	echo '<li class="ui-state-default" id="Subjects">'.translate('Subjects','multimedia').'</li>';
                                      }
                                      if(!in_array('Media type', $order) && !in_array('Media type ', $order) ){
                                      	echo '<li class="ui-state-default" id="Media type">'.translate('Media type','multimedia').'</li>';
                                      }
                                      ?>
                                      </ul>

                                    </p>
                                </td>

                                <td >
                                    <p align="left"><?php _e('Selected', 'multimedia');?> <br>
                                      <ul id="sortable2" class="sortable-list">
                                      <?php
                                      foreach ($order as $index => $item) {
                                        $item = trim($item); // Important
                                        echo '<li class="ui-state-default" id="'.$item.'">'.translate($item ,'multimedia').'</li>';
                                      }
                                      ?>
                                      </ul>
                                      <input type="hidden" id="order_aux" name="multimedia_config[available_filter]" value="<?php echo trim($config['available_filter']); ?> " >

                                    </p>
                                </td>
                                </tr>
                                </table>

                            </td>
                        </tr>
                    </tbody>
                </table>

                <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                </p>

            </form>
        </div>
        <script type="text/javascript">
            var $j = jQuery.noConflict();

            $j( function() {
              $j( "ul.droptrue" ).sortable({
                connectWith: "ul"
              });

              $j('.sortable-list').sortable({

                connectWith: 'ul',
                update: function(event, ui) {
                  var changedList = this.id;
                  var order = $j(this).sortable('toArray');
                  var positions = order.join(';');
                  $j('#order_aux').val(positions);

                }
              });
            } );
        </script>

        <?php
}
?>
