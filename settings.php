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
                                $config['available_filter'] = ['Collection', 'Subjects', 'Media type'];
                              }
                              ?>

                            <td>
                              <table border=0>
                                <tr>
                                <td >
                                    <p align="right"><?php _e('Available', 'multimedia');?><br>
                                    <select id="List1" size="5" multiple style="width: 100pt">
                                        <?php
                                        if(!in_array('Collection', $config['available_filter'])){
                                            echo '<option value="Collection" >'. translate('Collection','multimedia').'</option>';
                                        }
                                        if(!in_array('Subjects', $config['available_filter'])){
                                            echo '<option value="Subjects" >'. translate('Subjects','multimedia').'</option>';
                                        }
                                        if(!in_array('Media type', $config['available_filter'])){
                                            echo '<option value="Media type" >'. translate('Media type','multimedia').'</option>';
                                        }
                                        ?>
                                        </select>
                                    </p>
                                </td>
                                <td >
                                    <p align="center">
                                        <input type="button" name="add" value=">>" OnClick="changeList(document.getElementById('List1'), document.getElementById('List2'))" > <br>
                                        <input type="button" name="remove" value="<<" OnClick="changeList(document.getElementById('List2'), document.getElementById('List1'))" > <br>
                                    </p>
                                </td>
                                <td >
                                    <p align="left"><?php _e('Selected', 'multimedia');?> <br>
                                    <select size ="5" multiple style="width: 100pt" id="List2" name="multimedia_config[available_filter][]">
                                    <?php
                                      foreach($config['available_filter'] as $filter) {
                                          echo '<option value="'.$filter.'" selected>'.translate($filter,'multimedia').'</option>';
                                      }
                                      ?>
                                    </select>
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

            function changeList(ListOrigem, ListDestino){
                //ListOrigem = document.getElementById('List1');
                //ListDestino = document.getElementById('List2');
                var i;
                for (i = 0; i < ListOrigem.options.length ; i++){
                    if (ListOrigem.options[i].selected == true){
                        var Op = document.createElement("OPTION");
                        Op.text = ListOrigem.options[i].text;
                        Op.value = ListOrigem.options[i].value;
                        Op.setAttribute("selected", true);
                        ListDestino.options.add(Op);
                        ListOrigem.options.remove(i);
                        i--;
                    }
                }
            }






        </script>

        <?php
}
?>
