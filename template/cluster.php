<?php

ini_set('display_errors', '0');

$lang = $_POST['lang'];
$site_lang = $_POST['site_lang'];
$query = $_POST['query'];
$filter = $_POST['filter'];
$user_filter = $_POST['uf'];
$fb = $_POST['fb'];
$cluster = $_POST['cluster'];
$cluster_fb = ( $_POST['cluster'] ) ? $_POST['cluster'].':'.$fb : '';
$count = 1;

$mm_service_request = $mm_service_url . 'api/multimedia/search/?q=' . urlencode($query) . '&fq=' . urlencode($filter) . '&fb=' . $cluster_fb . '&lang=' . $lang . '&count=' . $count;

// echo "<pre>"; print_r($mm_service_request); echo "</pre>"; die();

$response = @file_get_contents($mm_service_request);
if ($response){
    $response_json = json_decode($response);
    // echo "<pre>"; print_r($response_json); echo "</pre>"; die();
    $total = $response_json->diaServerResponse[0]->response->numFound;
    $resource_list = $response_json->diaServerResponse[0]->response->docs;
    $descriptor_list = $response_json->diaServerResponse[0]->facet_counts->facet_fields->descriptor_filter;
    $collection_filter = $response_json->diaServerResponse[0]->facet_counts->facet_fields->media_collection_filter;
    $media_type_filter = $response_json->diaServerResponse[0]->facet_counts->facet_fields->media_type_filter;
    $thematic_area_list = $response_json->diaServerResponse[0]->facet_counts->facet_fields->thematic_area_display;
}

?>

<?php if($cluster == 'media_collection_filter'): ?>
    <?php if($collection_filter): ?>
        <ul class="filter-list">
            <?php foreach ( $collection_filter as $collection ) : ?>
                <?php
                    $filter_link = '?';
                    if ($query != ''){
                        $filter_link .= 'q=' . $query . '&';
                    }
                    $filter_link .= 'filter=media_collection_filter:"' . $collection[0] . '"';
                    if ($user_filter != ''){
                        $filter_link .= ' AND ' . $user_filter ;
                    }
                ?>
                <li class="cat-item">
                    <a href='<?php echo $filter_link; ?>'><?php echo $collection[0]; ?></a>
                    <span class="cat-item-count"><?php echo $collection[1] ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?php endif; ?>

<?php if($cluster == 'descriptor_filter'): ?>
    <?php if($descriptor_list): ?>
        <ul class="filter-list">
            <?php foreach ( $descriptor_list as $descriptor ) : ?>
                <?php
                    $filter_link = '?';
                    if ($query != ''){
                        $filter_link .= 'q=' . $query . '&';
                    }
                    $filter_link .= 'filter=descriptor:"' . $descriptor[0] . '"';
                    if ($user_filter != ''){
                        $filter_link .= ' AND ' . $user_filter ;
                    }
                ?>
                <?php if ( filter_var($descriptor[0], FILTER_VALIDATE_INT) === false ) : ?>
                    <li class="cat-item">
                        <a href='<?php echo $filter_link ?>'><?php echo $descriptor[0] ?></a>
                        <span class="cat-item-count"><?php echo $descriptor[1] ?></span>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?php endif; ?>

<?php if($cluster == 'media_type_filter'): ?>
    <?php if($media_type_filter): ?>
        <ul class="filter-list">
            <?php foreach ( $media_type_filter as $type ) : ?>
                <?php
                    $filter_link = '?';
                    if ($query != ''){
                        $filter_link .= 'q=' . $query . '&';
                    }
                    $filter_link .= 'filter=media_type_filter:"' . $type[0] . '"';
                    if ($user_filter != ''){
                        $filter_link .= ' AND ' . $user_filter ;
                    }
                ?>
                <li class="cat-item">
                    <a href='<?php echo $filter_link; ?>'><?php multimedia_print_lang_value($type[0], $site_lang); ?></a>
                    <span class="cat-item-count"><?php echo $type[1] ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?php endif; ?>

<?php if($cluster == 'thematic_area_display'): ?>
    <?php if($thematic_area_list): ?>
        <ul class="filter-list">
            <?php foreach ( $thematic_area_list as $ta ) : ?>
                <?php
                    $filter_link = '?';
                    if ($query != ''){
                        $filter_link .= 'q=' . $query . '&';
                    }
                    $filter_link .= 'filter=thematic_area_display:"' . $ta[0] . '"';
                    if ($user_filter != ''){
                        $filter_link .= ' AND ' . $user_filter ;
                    }
                ?>
                <li class="cat-item">
                    <a href='<?php echo $filter_link; ?>'><?php multimedia_print_lang_value($ta[0], $site_lang); ?></a>
                    <span class="cat-item-count"><?php echo $ta[1] ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
<?php endif; ?>