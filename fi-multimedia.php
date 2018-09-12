<?php
/*
Plugin Name: FI-Multimedia
Plugin URI: http://reddes.bvsalud.org/projects/fi-admin/
Description: List multimedia metadata from FI-ADMIN.
Author: BIREME/OPAS/OMS
Version: 0.2
Author URI: http://reddes.bvsalud.org/
*/

define('PLUGIN_VERSION', '0.2' );

define('PLUGIN_SYMBOLIC_LINK', false );
define('PLUGIN_DIRNAME', 'fi-multimedia' );

if(PLUGIN_SYMBOLIC_LINK == true) {
    define('PLUGIN_PATH',  ABSPATH . 'wp-content/plugins/' . PLUGIN_DIRNAME );
} else {
    define('PLUGIN_PATH',  plugin_dir_path(__FILE__) );
}

define('PLUGIN_DIR',   plugin_basename( PLUGIN_PATH ) );
define('PLUGIN_URL',   plugin_dir_url(__FILE__) );


require_once(PLUGIN_PATH . '/settings.php');
require_once(PLUGIN_PATH . '/template-functions.php');

if(!class_exists('FI_Multimedia_Plugin')) {
    class FI_Multimedia_Plugin {

        private $plugin_slug = 'multimedia';
        private $service_url = 'http://fi-admin.data.bvsalud.org/';

        /**
         * Construct the plugin object
         */
        public function __construct() {
            // register actions

            add_action( 'init', array(&$this, 'load_translation'));
            add_action( 'admin_menu', array(&$this, 'admin_menu'));
            add_action( 'admin_init', array(&$this, 'multiselect'));
            add_action( 'plugins_loaded', array(&$this, 'plugin_init'));
            add_action( 'wp_head', array(&$this, 'google_analytics_code'));
            add_action( 'template_redirect', array(&$this, 'template_redirect'));
            add_action( 'widgets_init', array(&$this, 'register_sidebars'));
            add_filter( 'get_search_form', array(&$this, 'search_form'));
            add_filter( 'document_title_parts', array(&$this, 'theme_slug_render_title'));



        } // END public function __construct

        /**
         * Activate the plugin
         */
        public static function activate()
        {
            // Do nothing
        } // END public static function activate

        /**
         * Deactivate the plugin
         */
        public static function deactivate()
        {
            // Do nothing
        } // END public static function deactivate


        function load_translation(){
            // Translations
            load_plugin_textdomain( 'multimedia', false,  PLUGIN_DIR . '/languages' );
        }

        function plugin_init() {
            $multimedia_config = get_option('multimedia_config');

            if ($multimedia_config['plugin_slug'] != ''){
                $this->plugin_slug = $multimedia_config['plugin_slug'];
            }

        }

        function admin_menu() {
            add_options_page(__('Multimedia Settings', 'multimedia'), __('FI-Multimedia', 'multimedia'),
                'manage_options', 'multimedia.php', 'multimedia_page_admin');
            //call register settings function
            add_action( 'admin_init', array(&$this, 'register_settings'));
        }


        function template_redirect() {
            global $wp, $mm_service_url, $mm_plugin_slug;
            $pagename = $wp->query_vars["pagename"];

            // check if request contains plugin slug string
            $pos_slug = strpos($wp->request, $this->plugin_slug);
            if ( $pos_slug !== false ){
                $pagename = substr($wp->request, $pos_slug);
            }

            if ( is_404() && $pos_slug !== false ){

                $mm_service_url = $this->service_url;
                $mm_plugin_slug = $this->plugin_slug;

                if ($pagename == $this->plugin_slug || $pagename == $this->plugin_slug . '/resource'
                    || $pagename == $this->plugin_slug . '/multimedia-feed') {

                    add_action( 'wp_enqueue_scripts', array(&$this, 'page_template_styles_scripts'));

                    if ($pagename == $this->plugin_slug){
                        $template = PLUGIN_PATH . '/template/home.php';
                    }elseif ($pagename == $this->plugin_slug . '/multimedia-feed'){
                        header("Content-Type: text/xml; charset=UTF-8");
                        $template = PLUGIN_PATH . '/template/rss.php';
                    }else{
                        $template = PLUGIN_PATH . '/template/resource.php';
                    }
                    // force status to 200 - OK
                    status_header(200);

                    // redirect to page and finish execution
                    include($template);
                    die();
                }
            }
        }

        function register_sidebars(){
            $args = array(
                'name' => __('Multimedia sidebar', 'multimedia'),
                'id'   => 'multimedia-home',
                'description' => 'Multimedia Area',
                'before_widget' => '<section id="%1$s" class="row-fluid marginbottom25 widget_categories">',
                'after_widget'  => '</section>',
                'before_title'  => '<header class="row-fluid border-bottom marginbottom15"><h1 class="h1-header">',
                'after_title'   => '</h1></header>',
            );
            register_sidebar( $args );
        }


        function theme_slug_render_title($title) {
            global $wp, $mm_plugin_title;
            $pagename = '';

            // check if request contains plugin slug string
            $pos_slug = strpos($wp->request, $this->plugin_slug);
            if ( $pos_slug !== false ){
                $pagename = substr($wp->request, $pos_slug);
            }

            if ( is_404() && $pos_slug !== false ){
                $title['title'] = 'Multimedia | ' . get_bloginfo('name');
            }

            return $title;
        }


        function search_form( $form ) {
		    global $wp;
		    $pagename = $wp->query_vars["pagename"];

		    if ($pagename == $this->plugin_slug || preg_match('/resource\//', $pagename)) {
		        $form = preg_replace('/action="([^"]*)"(.*)/','action="' . home_url($this->plugin_slug) . '"',$form);
		    }

		    return $form;
		}

        function page_template_styles_scripts(){
            wp_enqueue_script('multimedia',  PLUGIN_URL . 'template/js/functions.js');
            wp_enqueue_style ('multimedia',  PLUGIN_URL . 'template/css/style.css');

        }


        function register_settings(){
            register_setting('multimedia-settings-group', 'multimedia_config');
            wp_enqueue_style ('multimedia',  PLUGIN_URL . 'template/css/admin.css');
            wp_enqueue_script('multimedia',  PLUGIN_URL . 'template/js/jquery-ui.js');
        }

        function google_analytics_code(){
            global $wp;

            $pagename = $wp->query_vars["pagename"];
            $multimedia_config = get_option('multimedia_config');

            // check if is defined GA code and pagename starts with plugin slug
            if ($multimedia_config['google_analytics_code'] != ''
                && strpos($pagename, $this->plugin_slug) === 0){
        ?>

        <script type="text/javascript">
          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', '<?php echo $multimedia_config['google_analytics_code'] ?>']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();

        </script>

        <?php
            } //endif
        }

        function multiselect(){
          wp_enqueue_style ('multimedia',  PLUGIN_URL . 'template/css/admin.css');

        }


    } // END class FI_Multimedia_Plugin
} // END if(!class_exists('FI_Multimedia_Plugin'))

if(class_exists('FI_Multimedia_Plugin'))
{
    // Installation and uninstallation hooks
    register_activation_hook(__FILE__, array('FI_Multimedia_Plugin', 'activate'));
    register_deactivation_hook(__FILE__, array('FI_Multimedia_Plugin', 'deactivate'));

    // instantiate the plugin class
    $wp_plugin_template = new FI_Multimedia_Plugin();
}

?>
