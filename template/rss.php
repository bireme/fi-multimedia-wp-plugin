<?php
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";

/*
Template Name: LIS RSS
*/

$multi_config = get_option('multimedia_config');
$multi_service_url = $multi_config['service_url'];
$multi_initial_filter = $multi_config['initial_filter'];

$site_language = strtolower(get_bloginfo('language'));

$query = ( isset($_GET['s']) ? $_GET['s'] : $_GET['q'] );
$user_filter = stripslashes($_GET['filter']);
$page = ( isset($_GET['page']) ? $_GET['page'] : 1 );
$total = 0;
$count = 10;
$filter = '';

if ($multi_initial_filter != ''){
    if ($user_filter != ''){
        $filter = $multi_initial_filter . ' AND ' . $user_filter;
    }else{
        $filter = $multi_initial_filter;
    }
}else{
    $filter = $user_filter;
}
$start = ($page * $count) - $count;

$multi_service_request = $multi_service_url . 'api/multimedia/search/?q=' . urlencode($query) . '&fq=' .urlencode($filter) . '&start=' . $start;

//print $multi_service_request;

$response = @file_get_contents($multi_service_request);
if ($response){
    $response_json = json_decode($response);
    //var_dump($response_json);
    $total = $response_json->diaServerResponse[0]->response->numFound;
    $start = $response_json->diaServerResponse[0]->response->start;
    $resource_list = $response_json->diaServerResponse[0]->response->docs;
    $descriptor_list = $response_json->diaServerResponse[0]->facet_counts->facet_fields->descriptor_filter;
}

$page_url_params = home_url($multimedia_plugin_slug) . '?q=' . urlencode($query) . '&filter=' . urlencode($filter);


?>
<rss version="2.0">
    <channel>
        <title><?php _e('Multimedia', 'multimedia') ?> <?php echo ($query != '' ? '|' . $query : '') ?></title>
        <link><?php echo htmlspecialchars($page_url_params) ?></link>
        <description><?php echo $query ?></description>
        <?php
            foreach ( $resource_list as $resource) {
                echo "<item>\n";
                echo "   <title>". htmlspecialchars($resource->title) . "</title>\n";
                if ($resource->authors){
                    echo "   <author>". implode(", ", $resource->authors) . "</author>\n";
                }
                echo "   <link>" . home_url($multimedia_plugin_slug) .'/resource/' . $resource->django_id . "</link>\n";
                echo "   <description>". htmlspecialchars($resource->description[0]) . "</description>\n";
                echo "   <guid isPermaLink=\"false\">" . $resource->django_id . "</guid>\n";
                echo "</item>\n";
            }
        ?>
    </channel>
</rss>
