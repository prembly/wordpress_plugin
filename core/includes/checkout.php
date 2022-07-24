<?php

class Idx_pfd_checkout {
    
    private $plugin_name;
	private $version;
    
    protected $loader;

    public $idx_admin;

    public function __construct( $plugin_name, $version ) {
        $this->plugin_name = $plugin_name;
        
        /**
         * Load all the custom libraries
         */

        $this->load_libraries();
        $this->define_admin();

        // $idx_admin = new IdentitypassAdmin( $plugin_name, $version );

        // add_action( 'admin_menu', array( &$this, 'idplugin_add_settings_page' ) );
        
        if(empty($version) || $version == null) { $this->version = '1.0'; } else { $this->version = $version; }

        add_action ( 'init', 'wp_idx_init' );
    }

    function wpplugin_settings_page_markup_2()
    {
        if( !current_user_can('manage_options') ) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( get_admin_page_title() ); ?></h1>
            <!-- <p><?php esc_html_e( 'Some contentssssssss here' ); ?></p> -->
        </div>
        <?php
    }
    
    public function initplugin_script()
    {
        wp_register_script( 'Idx_plugin', 'https://js.myidentitypay.com/v1/inline/kyc.js', false, '1');
        wp_enqueue_script( 'Idx_plugin' );
    }

    function generate_user($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
    
        for ($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
    
        return $randomString;
    }

    public function wp_idx_init() {
        include_once plugin_dir_path( __FILE__ ) . '../../core/includes/verifier.php';

        $this->initplugin_script();
        
        // wp_enqueue_script( 'functionality-scripts', plugin_dir_url( __FILE__ ) . '../../core/script.js', array('jquery') );

        wp_enqueue_script(
            'script-handle',
            plugin_dir_url( __FILE__ ) . '../../resources/js/script.js',
            array( 'jquery' ),
            'version',
            true
        );
    
        wp_localize_script(
            'script-handle',
            'pluginScope',
            array(
                'testing' => (esc_attr(get_option('kyc_mode')) == 'test') ? true : false,
                'key' => (esc_attr(get_option('kyc_mode')) == 'test') ? esc_attr(get_option('kyc_tsk')) : esc_attr(get_option('kyc_lpk')),
                'userRef' => 'wp_user' . $this->generate_user(8),
            )   
        );

        wp_enqueue_style( 'style-handler', plugin_dir_url( __FILE__ ) . '../../resources/css/styles.css', array(), $this->version, 'all' );


        // Check if the logged in WordPress User can edit Posts or Pages
        // If not, don't register our TinyMCE plugin
        if (! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
            return;
        }

        // // Check if the logged in WordPress User has the Visual Editor enabled
        // // If not, don't register our TinyMCE plugin
        if (get_user_option('rich_editing') !== 'true' ) {
            return;
        }
        
        add_filter('mce_buttons', array( &$this, 'add_tinymce_toolbar_button' ));

    }

    private function load_libraries()
    {
        include_once plugin_dir_path( __FILE__ ) . '../../core/admin/identitypass-plugin-admin.php';

        require_once plugin_dir_path( __FILE__ ) . '../../core/includes/identity_loader.php';

        $this->loader = new Idx_Identity_Loader();
    }


    function add_tinymce_toolbar_button( $buttons ) 
    {

        array_push($buttons, 'custom_class');
        return $buttons;

    }

    public function run() {
        $this->loader->run();
        $this->wp_idx_init();
    }

    public function define_admin()
    {

        $idx_admin = new IdentitypassAdmin($this->plugin_name, $this->version);
        // $idx_admin = new IdentitypassAdmin( $plugin_name, $version );

        // Add settings link to plugin
        $this->loader->add_filter(
            'plugin_action_links_' . IDX_PLUGIN_BASENAME,
            $idx_admin,
            'add_custom_action_links'
        );

        // $this->loader->add_action('wp_enqueue_scripts', $idx_admin, 'initplugin_script');
    }
 }


