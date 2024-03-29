<?php
    include "../../../../wp-load.php";

    $lang = sanitize_text_field($_GET['lang']);
    $similar_docs_url = sanitize_text_field($_GET['query']);

    // get similar docs
    $similar_docs_xml = @file_get_contents($similar_docs_url);
    // transform to php array
    $xml = simplexml_load_string($similar_docs_xml,'SimpleXMLElement',LIBXML_NOCDATA);
    $json = json_encode($xml);
    $similar_docs = json_decode($json, TRUE);

    if ( $similar_docs && array_key_exists('document', $similar_docs) ) {
        if ( array_key_exists('id', $similar_docs['document']) ) {
            $similar_docs['document'] = array($similar_docs['document']);
        }

        foreach ( $similar_docs['document'] as $similar) {
            ?>
            <li class="cat-item">
                <a href="http://pesquisa.bvsalud.org/portal/resource/<?php echo $lang . '/' . $similar['id']; ?>" target="_blank">
                <?php
                    $preferred_lang_list = array($lang, 'en', 'es', 'pt');
                    $similar_title = '';
                    // start with more generic title
                    if (isset($similar['ti'])){
                        $similar_title = is_array($similar['ti']) ? $similar['ti'][0] : $similar['ti'];
                    }
                    // search for title in different languages
                    foreach ($preferred_lang_list as $lang){
                        $ti_lang = 'ti_' . $lang;
                        if (isset($similar[$ti_lang])){
                            $similar_title = $similar[$ti_lang];
                            break;
                        }
                    }
                    echo $similar_title;
                ?>
                </a>
            </li>
            <?php
        }
    } else {
        echo '<li>' . __('No related documents', 'multimedia') . '</li>';
    }
?>