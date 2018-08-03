<?php
global $mm_service_url, $mm_plugin_slug;
echo "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";

/*
Template Name: LIS RSS
*/

$mm_config = get_option('multimedia_config');
$mm_initial_filter = $mm_config['initial_filter'];

$site_language = strtolower(get_bloginfo('language'));

$query = ( isset($_GET['s']) ? $_GET['s'] : $_GET['q'] );
$user_filter = stripslashes($_GET['filter']);
$page = ( isset($_GET['page']) ? $_GET['page'] : 1 );
$total = 0;
$count = 10;
$filter = '';

if ($mm_initial_filter != ''){
    if ($user_filter != ''){
        $filter = $mm_initial_filter . ' AND ' . $user_filter;
    }else{
        $filter = $mm_initial_filter;
    }
}else{
    $filter = $user_filter;
}
$start = ($page * $count) - $count;

$mm_service_request = $mm_service_url . 'api/multimedia/search/?q=' . urlencode($query) . '&fq=' .urlencode($filter) . '&start=' . $start;

//print $mm_service_request;

$response = @file_get_contents($mm_service_request);
if ($response){
    $response_json = json_decode($response);
    //var_dump($response_json);
    $total = $response_json->diaServerResponse[0]->response->numFound;
    $start = $response_json->diaServerResponse[0]->response->start;
    $resource_list = $response_json->diaServerResponse[0]->response->docs;
    $descriptor_list = $response_json->diaServerResponse[0]->facet_counts->facet_fields->descriptor_filter;
}

$page_url_params = home_url($mm_plugin_slug) . '?q=' . urlencode($query) . '&filter=' . urlencode($filter);


?>
<rss version="2.0">
    <channel>
        <title><?php _e('Multimedia', 'multimedia') ?> <?php echo ($query != '' ? ' | ' . $query : '') ?></title>
        <link><?php echo htmlspecialchars($page_url_params) ?></link>
        <description><?php echo $query ?></description>
        <?php
            foreach ( $resource_list as $resource) {
                echo "<item>\n";
                echo "   <title>". htmlspecialchars($resource->title) . "</title>\n";
                if ($resource->authors){
                    echo "   <author>". implode(", ", $resource->authors) . "</author>\n";
                }
                echo "   <link>" . home_url($mm_plugin_slug) .'/resource/?id=' . $resource->id . "</link>\n";
                echo "   <description>". htmlspecialchars($resource->description[0]) . "</description>\n";
                echo "   <guid isPermaLink=\"false\">" . $resource->id . "</guid>\n";
                echo "</item>\n";
            }
        ?>
    </channel>
</rss>
