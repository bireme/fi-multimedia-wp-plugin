<?php
/*
Template Name: FI-Multimedia Home
*/
global $mm_service_url, $mm_plugin_slug;

require_once(PLUGIN_PATH . '/lib/Paginator.php');

$mm_config = get_option('multimedia_config');
$mm_initial_filter = $mm_config['initial_filter'];

$site_language = strtolower(get_bloginfo('language'));
$lang_dir = substr($site_language,0,2);

// set query using default param q (query) or s (wordpress search) or newexpr (metaiah)
$query = $_GET['s'] . $_GET['q'];
$query = stripslashes( trim($query) );

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

$mm_service_request = $mm_service_url . 'api/multimedia/search/?q=' . urlencode($query) . '&fq=' .urlencode($filter) . '&start=' . $start . '&lang=' . $lang_dir;

//print $mm_service_request;

$response = @file_get_contents($mm_service_request);
if ($response){
    $response_json = json_decode($response);
    //var_dump($response_json);
    $total = $response_json->diaServerResponse[0]->response->numFound;
    $start = $response_json->diaServerResponse[0]->response->start;
    $resource_list = $response_json->diaServerResponse[0]->response->docs;
    $descriptor_list = $response_json->diaServerResponse[0]->facet_counts->facet_fields->descriptor_filter;
    $collection_filter = $response_json->diaServerResponse[0]->facet_counts->facet_fields->media_collection_filter;
    $media_type_filter = $response_json->diaServerResponse[0]->facet_counts->facet_fields->media_type_filter;
}

$page_url_params = real_site_url($mm_plugin_slug) . '?q=' . urlencode($query) . '&filter=' . urlencode($filter);
$feed_url = real_site_url($mm_plugin_slug) . 'multimedia-feed?q=' . urlencode($query) . '&filter=' . urlencode($filter);

$pages = new Paginator($total, $start);
$pages->paginate($page_url_params);

?>

<?php get_header('multimedia');?>
	<div id="content" class="row-fluid">
		<div class="ajusta2">
            <div class="row-fluid breadcrumb">
                <a href="<?php echo real_site_url(); ?>"><?php _e('Home','multimedia'); ?></a> >
                <?php if ($query == '' && $filter == ''): ?>
                    <?php _e('Multimedia', 'multimedia') ?>
                <?php else: ?>
                    <a href="<?php echo real_site_url($mm_plugin_slug); ?>"><?php _e('Multimedia', 'multimedia') ?> </a> >
                    <?php _e('Search result', 'multimedia') ?>
                <?php endif; ?>
            </div>

			<section id="conteudo">
                <?php if ( isset($total) && strval($total) == 0) :?>
                    <h1 class="h1-header"><?php _e('No results found','multimedia'); ?></h1>
                <?php else :?>
    				<header class="row-fluid border-bottom">
                        <?php if ( ( $query != '' || $user_filter != '' ) && strval($total) > 0) :?>
    					   <h1 class="h1-header"><?php _e('Resources found','multimedia'); ?>: <?php echo $total; ?></h1>
                        <?php else: ?>
                           <h1 class="h1-header"><?php _e('Total of resources','multimedia'); echo ': ' . $total; ?></h1>
                        <?php endif; ?>
                        <div class="pull-right">
                            <a href="<?php echo $feed_url; ?>" target="blank"><img src="<?php echo PLUGIN_URL ?>template/images/icon_rss.png" class="rss_feed" /></a>
                        </div>

    				</header>
    				<div class="row-fluid">
                        <?php foreach ( $resource_list as $resource) { ?>
    					    <article class="conteudo-loop">
        						<div class="row-fluid">
        							<h2 class="h2-loop-tit"><?php echo $resource->title; ?></h2>
        						</div>

                                <?php if ($resource->media_collection): ?>
                                    <p class="row-fluid">
                                        <a href='<?php echo real_site_url($mm_plugin_slug); ?>/?filter=media_collection:"<?php echo $resource->media_collection; ?>"'>
                                            <?php echo $resource->media_collection ?>
                                        </a>
                                    </p>
                                <?php endif; ?>

        						<p class="row-fluid">
        							<?php echo ( strlen($resource->description[0]) > 400 ? substr($resource->description[0],0,400) . '...' : $resource->description[0]); ?><br/>
        							<span class="more"><a href="<?php echo real_site_url($mm_plugin_slug); ?>resource/?id=<?php echo $resource->id; ?>"><?php _e('See more details','multimedia'); ?></a></span>
        						</p>

                                <?php if ($resource->source_language_display): ?>
            						<div id="conteudo-loop-idiomas" class="row-fluid">
            							<span class="conteudo-loop-idiomas-tit"><?php _e('Available languages','multimedia'); ?>:</span>
            							<?php print_lang_value($resource->source_language_display, $site_language); ?>
            						</div>
                                <?php endif; ?>

                                <?php if ($resource->descriptor || $resource->keyword ) : ?>
                                    <div id="conteudo-loop-tags" class="row-fluid margintop10">
                                        <i class="ico-tags"> </i>
                                            <?php
                                                $descriptors = (array)$resource->descriptor;
                                                $keywords = (array)$resource->keyword;
                                            ?>
                                            <?php echo implode(", ", array_merge( $descriptors, $keywords) ); ?>
                                      </div>
                                <?php endif; ?>

                                <?php if ($resource->link): ?>
                                    <div id="conteudo-loop-data" class="row-fluid margintop05">
                                        <?php display_thumbnail($resource->link[0]); ?>
                                    </div>
                                <?php endif; ?>


        					</article>
                        <?php } ?>
    				</div>
                    <div class="row-fluid">
                        <?php echo $pages->display_pages(); ?>
                    </div>
                <?php endif; ?>
			</section>
			<aside id="sidebar">
			       <section class="header-search">
                    		<?php if ($mm_config['show_form']) : ?>
                        		<form role="search" method="get" id="searchform" action="<?php echo real_site_url($mm_plugin_slug); ?>">
                            			<input value='<?php echo $query ?>' name="q" class="input-search" id="s" type="text" placeholder="<?php _e('Search', 'multimedia'); ?>...">
                            			<input id="searchsubmit" value="<?php _e('Search', 'multimedia'); ?>" type="submit">
                        		</form>
                    		<?php endif; ?>
                	</section>

                    <?php dynamic_sidebar('multimedia-home');?>

                    <?php if (strval($total) > 0) :?>

                      <?php
                            $order = explode(';', $mm_config['available_filter']);
                            foreach ( $order as $index => $content) { ?>

                            <?php  if($content == 'Collection'){ ?>
                                <section class="row-fluid marginbottom25 widget_categories">
                                    <header class="row-fluid border-bottom marginbottom15">
                                        <h1 class="h1-header"><?php _e('Collection','multimedia'); ?></h1>
                                    </header>
                                    <ul>
                                        <?php foreach ( $collection_filter as $collection) { ?>
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
                                        <?php } ?>
                                    </ul>
                                </section>
                            <?php  }  ?>
                            <?php if($content == 'Subjects'){ ?>
                              <section class="row-fluid marginbottom25 widget_categories">
                              <header class="row-fluid border-bottom marginbottom15">
                              <h1 class="h1-header"><?php _e('Subjects','multimedia'); ?></h1>
                              </header>
                              <ul>
                                        <?php foreach ( $descriptor_list as $descriptor) { ?>
                                            <li class="cat-item">
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
                                                <a href='<?php echo $filter_link ?>'><?php echo $descriptor[0] ?></a>
                                                <span class="cat-item-count"><?php echo $descriptor[1] ?></span>
                                            </li>
                                        <?php } ?>
                              </ul>
                              </section>
                            <?php  } ?>
                            <?php if($content == 'Media type'){ ?>
                                  <section class="row-fluid marginbottom25 widget_categories">
                                      <header class="row-fluid border-bottom marginbottom15">
                                          <h1 class="h1-header"><?php _e('Media type','multimedia'); ?></h1>
                                      </header>
                                      <ul>
                                          <?php foreach ( $media_type_filter as $type) { ?>
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
                                                  <a href='<?php echo $filter_link; ?>'><?php print_lang_value($type[0], $site_language); ?></a>
                                                  <span class="cat-item-count"><?php echo $type[1] ?></span>
                                              </li>
                                          <?php } ?>
                                      </ul>
                                  </section>
                                <?php } ?>
                        <?php   } ?>
                    <?php endif; ?>
			</aside>
			<div class="spacer"></div>
		</div>
	</div>
<?php get_footer();?>
