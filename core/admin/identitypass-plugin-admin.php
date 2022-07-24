<?php
    class IdentitypassAdmin {

        private $plugin_name;
        private $version;

        public function __construct( $plugin_name, $version )
        {

            $this->plugin_name = $plugin_name;
            $this->version = $version;

            add_action( 'admin_menu', 'idplugin_add_settings_page' );
            
            add_action( 'admin_init', 'idplugin_register_setting_page' );

            add_action( 'init',  'register_idx_idcheckout' );

            function register_idx_idcheckout()
            {
                
                $labels = array(
                    'name' => _x('Payment Forms', 'identity_configuration_form'),
                    'singular_name' => _x('Identitypass Form', 'identity_configuration_form'),
                    'add_new' => _x('Add New', 'identity_configuration_form'),
                    'add_new_item' => _x('Add Identitypass Form', 'identity_configuration_form'),
                    'edit_item' => _x('Edit Identitypass Form', 'identity_configuration_form'),
                    'new_item' => _x('Identitypass Form', 'identity_configuration_form'),
                    'view_item' => _x('View Identitypass Form', 'identity_configuration_form'),
                    'all_items' => _x('All Forms', 'identity_configuration_form'),
                    'search_items' => _x('Search Identitypass Forms', 'identity_configuration_form'),
                    'not_found' => _x('No Identitypass Forms found', 'identity_configuration_form'),
                    'not_found_in_trash' => _x('No Identitypass Forms found in Trash', 'identity_configuration_form'),
                    'parent_item_colon' => _x('Parent Identitypass Form:', 'identity_configuration_form'),
                    'menu_name' => _x('Identitypass Forms', 'identity_configuration_form'),
                );

                $args = array(
                    'labels' => $labels,
                    'hierarchical' => true,
                    'description' => 'Identitypass Forms filterable by genre',
                    'supports' => array('title', 'editor'),
                    'public' => true,
                    'show_ui' => true,
                    'show_in_menu' => true,
                    'menu_position' => 5,
                    'menu_icon' => plugins_url('../../resources/sc_images/idpass-logo.png', __FILE__),
                    'show_in_nav_menus' => true,
                    'publicly_queryable' => true,
                    'exclude_from_search' => false,
                    'has_archive' => false,
                    'query_var' => true,
                    'can_export' => true,
                    'rewrite' => false,
                    'comments' => false,
                    'capability_type' => 'post'
                );
    
                register_post_type( 'identity_configuration_form', $args );
            }

            function idx_identity_add_view_payments($actions, $post)
            {
                if ( get_post_type() === 'identity_configuration_form' ) {
                    unset($actions['view']);
                    unset($actions['quick edit']);
                    $url = add_query_arg(
                        array(
                            'post_id' => $post->ID,
                            'action' => 'submissions',
                        )
                    );
                    $actions['export'] = '<a href="' . admin_url('admin.php?page=submissions&form=' . $post->ID) . '" >View Payments</a>';
                }
                return $actions;
            }

            // add_filter('page_row_actions', 'idx_identity_add_view_payments', 10, 2);
            // plugin_dir_path( __FILE__ ) . '../../resources/sc_images/idpass-logo.png'

            function idplugin_add_settings_page()
            {
                add_menu_page(
                    __( 'Identitypass', 'identitypass_checkout' ),
                    __( 'Identitypass', 'identitypass_checkout' ),
                    'manage_options',
                    'identitypass_checkout', // what is displayed as the name on the url
                    'wpplugin_settings_page_markup',
                    plugins_url('../../resources/sc_images/idpass-logo.png', __FILE__),
                    5
                );
                
                add_submenu_page('identitypass_checkout', __( 'Configuration', 'identitypass_checkout' ), __( 'Configuration', 'identitypass_checkout' ), 'manage_options', 'edit.php?post_type=identity_kyc_config', 'show_admin_settings_screen');
                // add_submenu_page('edit.php?post_type=identity_configuration_form', 'Configuration', 'Configuration', 'edit_posts', basename(__FILE__), 'show_admin_settings_screen');
            }
            // background-image: url('. get_field ("option", "logo_image") . ');

            // function admin_style() {
            //     echo '<style>
            //        #toplevel_page_logo_based_menu {
            //             background-image: url('. plugin_dir_path( __FILE__ ) . '../../resources/sc_images/idpass-logo.png' . ');
            //             // background-image: url('. get_field ("option", "logo_image") . ');
            //         }
            //                 #toplevel_page_logo_based_menu > a, #toplevel_page_logo_based_menu > a > div.wp-menu-image {
            //             display: none;
            //         }
            //      </style>';
            // }
            // add_action('admin_enqueue_scripts', 'admin_style');

            function wpplugin_settings_page_markup()
            {
                if(!current_user_can('manage_options')) {
                    return;
                }

                echo '<p><h1>Dashboard display overview coming soon!</h1></p>';
            }

            function kyc_mode_check($name, $txncharge)
            {
                if ($name == $txncharge) {
                    $result = "selected";
                } else {
                    $result = "";
                }
                return $result;
            }

            function show_admin_settings_screen()
            {
    
    ?>
                <div class="wrap idx_x_verification">
                    <h1>Identitypass KYC Configuration</h1>
                    <h2>API Keys Settings</h2>
                    <div>Don't have your API Keys? <br>Get them here: <a href="https://dashboard.myidentitypass.com/settings" target="_blank">here</a> </div><br><br>
                    <form method="post" action="options.php">
                        <?php settings_fields('idplugin-settings-pallet');
                        do_settings_sections('idplugin-settings-pallet'); ?>
                        <table class="form-table setting_page">
                            <tr valign="top">
                                <!-- <th scope="row">KYC Mode</th>
    
                                <td> -->
                                <!-- <div> -->
                                <div class="input-group group-select">
                                    <select class="form-control" name="kyc_mode" id="parent_id">
                                        <option value="test" <?php echo kyc_mode_check('test', esc_attr(get_option('kyc_mode'))) ?>>Test Mode</option>
                                        <option value="live" <?php echo kyc_mode_check('live', esc_attr(get_option('kyc_mode'))) ?>>Live Mode</option>
                                    </select>
                                    <label for="kyc_tsk">KYC Mode</label>
                                </div>
                            </tr>
                            <tr valign="top">
                                <div class="input-group">
                                    <input class="form-control" type="text"  value="<?php echo esc_attr(get_option('kyc_tsk')); ?>" name="kyc_tsk" required="required" placeholder="Test Secret API Key">
                                    <label for="kyc_tsk">Test Secret API Key</label>
                                    <div class="padlock-mark">&#128274;</div>
                                </div>

                                <!-- <th scope="row">Test Secret API Key</th>
                                <td>
                                    <input type="text" name="kyc_tsk" value="<?php echo esc_attr(get_option('kyc_tsk')); ?>" />
                                </td> -->
                            </tr>
    
                            <tr valign="top">
                                <div class="input-group">
                                    <input class="form-control" type="text" value="<?php echo esc_attr(get_option('kyc_tpk')); ?>" name="kyc_tpk" required="required" placeholder="Test Public API Key">
                                    <label for="kyc_tpk">Test Public API Key</label>
                                    <div class="padlock-mark">&#128274;</div>
                                </div>

                                <!-- <th scope="row">Test Public API Key</th>
                                <td><input type="text" name="kyc_tpk" value="<?php echo esc_attr(get_option('kyc_tpk')); ?>" /></td> -->
                            </tr>
    
                            <tr valign="top">
                                <div class="input-group">
                                    <input class="form-control" type="text" value="<?php echo esc_attr(get_option('kyc_lsk')); ?>" name="kyc_lsk" required="required" placeholder="Secret API Key">
                                    <label for="kyc_lsk">Secret API Key</label>
                                    <div class="padlock-mark">&#128274;</div>
                                </div>

                                <!-- <th scope="row">Secret API Key</th>
                                <td><input type="text" name="kyc_lsk" value="<?php echo esc_attr(get_option('kyc_lsk')); ?>" /></td> -->
                            </tr>
                            <tr valign="top">
                                <div class="input-group">
                                    <input class="form-control" type="text" value="<?php echo esc_attr(get_option('kyc_lpk')); ?>" name="kyc_lpk" required="required" placeholder="Test Public API Key">
                                    <label for="kyc_lpk">Public API Key</label>
                                    <div class="padlock-mark">&#128274;</div>
                                </div>

                                <!-- <th scope="row">Public API Key</th>
                                <td><input type="text" name="kyc_lpk" value="<?php echo esc_attr(get_option('kyc_lpk')); ?>" /></td> -->
                            </tr>
    
                        </table>
    
                        <hr>
    
                        <?php submit_button(); ?>
                    </form>
                </div>
            <?php
            }
            
            function idplugin_register_setting_page()
            {
                register_setting('idplugin-settings-pallet', 'kyc_mode');
                register_setting('idplugin-settings-pallet', 'kyc_tsk');
                register_setting('idplugin-settings-pallet', 'kyc_tpk');
                register_setting('idplugin-settings-pallet', 'kyc_lsk');
                register_setting('idplugin-settings-pallet', 'kyc_lpk');
            }
        }

        public function initplugin_script()
        {
            wp_register_script( 'Idx_plugin', 'https://js.myidentitypay.com/v1/inline/kyc.js', false, '1');
            wp_enqueue_script( 'Idx_plugin' );
        }

        public function add_custom_action_links( $links )
        {
            $settings_link = array(
                '<a href="' . admin_url('admin.php?page=edit.php?post_type=identity_kyc_config') . '">' . __('Configuration', $this->plugin_name) . '</a>',
            );
            return array_merge($settings_link, $links);
        }
    }

    if ( !class_exists('WP_List_Table') ) {
        include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
    }
