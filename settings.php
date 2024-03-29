<?php
function multimedia_page_admin() {

    $config = get_option('multimedia_config');

    $filter_db = array(
        "MEDLINE" => __("MEDLINE", "biblio"),
        "LILACS" => __("LILACS", "biblio"),
        "MedCarib" => __("MedCarib", "biblio"),
        "BBO" => __("BBO - Dentistry", "biblio"),
        "colecionaSUS" => __("Coleciona SUS", "biblio"),
        "BDENF" => __("BDENF - Nursing", "biblio"),
        "IBECS" => __("IBECS", "biblio"),
        "tese" => __("Index Psychology - Theses", "biblio"),
        "SIRPEP" => __("Index Psychology - Scientific divulgation", "biblio"),
        "RIPSA-CONSULTA" => __("RIPSA", "biblio"),
        "RIPSA-RELATORIOS" => __("RIPSA - Reports", "biblio"),
        "RIPSA-PRODUTOS" => __("RIPSA - Products", "biblio"),
        "fichasidb" => __("RIPSA - Qualification record", "biblio"),
        "RIPSA-NORMATIVOS" => __("RIPSA - Normative acts", "biblio"),
        "Puerto" => __("Theses - Puerto Rico", "biblio"),
        "A_DOLEC" => __("ADOLEC - Adolescence", "biblio"),
        "CidSaude" => __("CidSaúde - Healthy Cities", "biblio"),
        "DESASTRES" => __("Desastres - Disasters", "biblio"),
        "HANSENIASE" => __("Hanseníase - Leprosy", "biblio"),
        "HISA" => __("HISA - History of Health", "biblio"),
        "HomeoIndex" => __("HomeoIndex - Homeopathy", "biblio"),
        "INDEXPSI" => __("Index Psychology - Scientific journals", "biblio"),
        "REPIDISCA" => __("REPIDISCA", "biblio"),
        "respostas_aps" => __("SOF - Formative Second Opinion", "biblio"),
        "PAHO" => __("PAHO", "biblio"),
        "WHOLIS" => __("WHO IRIS", "biblio"),
        "CUMED" => __("CUMED", "biblio"),
    );

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
                        <th scope="row"><?php _e('Related Documents filter', 'multimedia'); ?>:</th>
                        <td>
                            <input type="text" name="multimedia_config[default_filter_db]" value='<?php echo $config['default_filter_db']; ?>' class="regular-text code">
                            <small style="display: block;">* <?php _e('The filters must be separated by commas.', 'multimedia'); ?></small>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('More Related Documents filter', 'multimedia'); ?>:</th>
                        <td>
                            <input type="text" name="multimedia_config[extra_filter_db]" value='<?php echo $config['extra_filter_db']; ?>' class="regular-text code">
                            <small style="display: block;">* <?php _e('The filters must be separated by commas.', 'multimedia'); ?></small>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Sidebar order', 'multimedia');?>:</th>
                        <?php
                            $available_filters = 'Collection;Subjects;Media type;Thematic area;Publication year';
                            $available_filter_list = explode(';', $available_filters);
                            if(!isset($config['available_filter'])){
                                $order = $available_filter_list;
                            } else {
                                $order = array_filter(explode(';', $config['available_filter']));
                            }
                        ?>
                        <td>
                            <table border=0>
                                <tr>
                                    <td>
                                        <p align="left"><?php _e('Available', 'multimedia');?><br>
                                            <ul id="sortable1" class="droptrue">
                                                <?php foreach ($available_filter_list as $key => $value) : ?>
                                                    <?php if ( !in_array($value, $order) ) : ?>
                                                        <?php echo '<li class="ui-state-default" id="'.$value.'">'.translate($value,'multimedia').'</li>'; ?>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </ul>
                                        </p>
                                    </td>
                                    <td>
                                        <p align="left"><?php _e('Selected', 'multimedia');?> <br>
                                            <ul id="sortable2" class="sortable-list">
                                                <?php
                                                    foreach ($order as $index => $item) {
                                                        $item = trim($item);
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
        });
    </script>
    <?php
}
?>
