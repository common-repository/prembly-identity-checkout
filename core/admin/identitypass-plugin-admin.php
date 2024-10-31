<?php
    class Premidx_IdentitypassAdmin {

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
                    'name' => esc_html__('Payment Forms', 'identity_configuration_form'),
                    'singular_name' => esc_html__('Identitypass Form', 'identity_configuration_form'),
                    'add_new' => esc_html__('Add New', 'identity_configuration_form'),
                    'add_new_item' => esc_html__('Add Identitypass Form', 'identity_configuration_form'),
                    'edit_item' => esc_html__('Edit Identitypass Form', 'identity_configuration_form'),
                    'new_item' => esc_html__('Identitypass Form', 'identity_configuration_form'),
                    'view_item' => esc_html__('View Identitypass Form', 'identity_configuration_form'),
                    'all_items' => esc_html__('All Forms', 'identity_configuration_form'),
                    'search_items' => esc_html__('Search Identitypass Forms', 'identity_configuration_form'),
                    'not_found' => esc_html__('No Identitypass Forms found', 'identity_configuration_form'),
                    'not_found_in_trash' => esc_html__('No Identitypass Forms found in Trash', 'identity_configuration_form'),
                    'parent_item_colon' => esc_html__('Parent Identitypass Form:', 'identity_configuration_form'),
                    'menu_name' => esc_html__('Identitypass Forms', 'identity_configuration_form'),
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

            function idxpay_identity_add_view_payments($actions, $post)
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
            
            // add_filter('page_row_actions', 'idxpay_identity_add_view_payments', 10, 2);
            // plugin_dir_path( __FILE__ ) . '../../resources/sc_images/idpass-logo.png'

            function idplugin_add_settings_page()
            {
                add_menu_page(
                    esc_html__( 'Identitypass', 'prembly-identity-checkout' ),
                    esc_html__( 'Identitypass', 'prembly-identity-checkout' ),
                    'manage_options',
                    'prembly-identity-checkout', // what is displayed as the name on the url
                    'wpplugin_settings_page_markup',
                    plugins_url('../../resources/sc_images/idpass-logo.png', __FILE__),
                    5
                );
                
                add_submenu_page('prembly-identity-checkout', esc_html__( 'Configuration', 'prembly-identity-checkout' ), esc_html__( 'Configuration', 'prembly-identity-checkout' ), 'manage_options', 'edit.php?post_type=identity_kyc_config', 'show_admin_settings_screen');
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

                echo '
                    <div>
                        <h1>
                            My Users
                        </h1>
                        <span style="font-size:14px">See the list of your verified users below!</span>
                        <hr>
                    </div>
                    <div style="margin-top:20%; margin-left: 45%">
                        Noting was found
                    </div>
                ';
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
                if(!current_user_can('manage_options')) {
                    return;
                }
    
    ?>
                <div class="wrap idx_x_verification" style="margin-left:20%;margin-right:30%">
                    <h1>IdentityPass KYC Configuration</h1>
                    <!-- <h2>API Keys Settings</h2> -->
                    <div>Don't have your API Keys? Follow this link: <a href="https://dashboard.prembly.com/Settings" target="_blank">here</a> </div>
                    <!-- <hr>
                    <?php
                        // if ( isset ( $_POST['submitx'] ) )
                        // {
                        //     print_r ( $_POST );
                        // }
                    ?> -->
                    <!-- <form method="post" action="options.php" > -->
                    <form method="POST">
                        <?php settings_fields('idplugin-settings-pallet');
                        do_settings_sections('idplugin-settings-pallet'); ?>
                        <table class="form-table setting_page">
                            <tr valign="top">
                                <!-- <th scope="row">KYC Mode</th>
    
                                <td> -->
                                <!-- <div> -->
                                <div class="input-group group-select">
                                    <select class="form-control" name="kycidx_mode" id="parent_id">
                                        <option value="test" <?php echo esc_attr(kyc_mode_check('test', get_option('premidxkyc_mode'))) ?>>Test Mode</option>
                                        <option value="live" <?php echo esc_attr(kyc_mode_check('live', get_option('premidxkyc_mode'))) ?>>Live Mode</option>
                                    </select>
                                    <label for="kycidx_tsk">KYC Mode</label>
                                </div>
                            </tr>
                            <tr valign="top">
                                <!-- <th scope="row">Test Secret API Key</th>
                                <td>
                                    <input type="text" name="kyc_tsk" value="<?php echo esc_attr(get_option('premidxkyc_tsk')); ?>" />
                                </td> -->
                            </tr>
    
                            <tr valign="top">
                                <div class="input-group">
                                    <input class="form-control" type="text" value="<?php echo esc_attr(get_option('premidxkyc_tpk')); ?>" name="kycidx_tpk" required="required" placeholder="Test Public API Key">
                                    <label for="kycidx_tpk">Test Public API Key</label>
                                    <div class="padlock-mark">&#128274;</div>
                                </div>

                                <!-- <th scope="row">Test Public API Key</th>
                                <td><input type="text" name="kyc_tpk" value="<?php echo esc_attr(get_option('premidxkyc_tpk')); ?>" /></td> -->
                            </tr>
                            <tr valign="top">
                                <div class="input-group">
                                    <input class="form-control" type="text" value="<?php echo esc_attr(get_option('premidxkyc_lpk')); ?>" name="kycidx_lpk" required="required" placeholder="Test Public API Key">
                                    <label for="kycidx_lpk">Public API Key</label>
                                    <div class="padlock-mark">&#128274;</div>
                                </div>

                                <!-- <th scope="row">Public API Key</th>
                                <td><input type="text" name="kyc_lpk" value="<?php echo esc_attr(get_option('premidxkyc_lpk')); ?>" /></td> -->
                            </tr>

                            <tr valign="top">
                                <div class="input-group">
                                    <input class="form-control" type="text" value="<?php echo esc_attr(get_option('premidxkyc_conf')); ?>" name="kycidx_conf" required="required" placeholder="Configuration Id">
                                    <label for="kycidx_conf">Configuration ID (Widget Code)</label>
                                    <div class="padlock-mark">&#128274;</div>
                                </div>

                                <!-- <th scope="row">Public API Key</th>
                                <td><input type="text" name="kyc_lpk" value="<?php echo esc_attr(get_option('premidxkyc_conf')); ?>" /></td> -->
                            </tr>
                            <tr valign="top">
                                <div class="input-group">
                                    <input class="form-control" type="text" value="<?php echo esc_attr(get_option('premidxkyc_redirect')); ?>" name="kycidx_redirect" required="required" placeholder="Enter URL to redirect users">
                                    <label for="kycidx_redirect">URL to redirect</label>
                                </div>
                            </tr>
                            <hr>
                            <?php submit_button(); ?>
                            <?php
                                if ( isset ( $_POST['submit'] ) )
                                {
                                    wp_nonce_field( 'save_config_'.sanitize_text_field($_POST['kycidx_lpk']) );

                                    $arr = ['premidxkyc_mode', 'premidxkyc_tpk', 'premidxkyc_lpk', 'premidxkyc_conf', 'premidxkyc_redirect'];
                                    
                                    if(isset($_POST['kycidx_mode'])){
                                        $kyc_mode_bool = update_option ( 'premidxkyc_mode', sanitize_text_field($_POST['kycidx_mode']) );
                                    }

                                    if(isset($_POST['kycidx_tpk'])){
                                        $kyc_tpk_bool =update_option ( 'premidxkyc_tpk', sanitize_text_field($_POST['kycidx_tpk']) );
                                    }

                                    if(isset($_POST['kycidx_lpk'])){
                                        $kyc_lpk_bool = update_option ( 'premidxkyc_lpk', sanitize_text_field($_POST['kycidx_lpk']) );
                                    }

                                    if(isset($_POST['kycidx_conf'])){
                                        $kyc_conf_bool = update_option ( 'premidxkyc_conf', sanitize_text_field($_POST['kycidx_conf']) );
                                    }

                                    if(isset($_POST['kycidx_redirect'])){
                                        $kyc_redirect_bool = update_option ( 'premidxkyc_redirect', sanitize_text_field($_POST['kycidx_redirect']) );
                                    }
    
                                }

                                if ( $kyc_mode_bool &&  $kyc_tpk_bool && $kyc_lpk_bool && $kyc_conf_bool && $kyc_redirect_bool)
                                {
                                    print ( '<div id="kyc_report" style="background: lightgreen; padding: 10px; width:100%; border-radius: 10px; color: #fff">
                                    Settings updated successfully!
                                    </div>' );
                                }
                            ?>
                        </table>
                    </form>
                </div>
            <?php
            }
            
            function idplugin_register_setting_page()
            {
                register_setting('idplugin-settings-pallet', 'premidxkyc_mode');
                register_setting('idplugin-settings-pallet', 'premidxkyc_tsk');
                register_setting('idplugin-settings-pallet', 'premidxkyc_tpk');
                register_setting('idplugin-settings-pallet', 'premidxkyc_lsk');
                register_setting('idplugin-settings-pallet', 'premidxkyc_lpk');
                register_setting('idplugin-settings-pallet', 'premidxkyc_conf');
            }
        }

        public function initplugin_script()
        {
            wp_register_script( 'Idx_plugin', 'https://js.prembly.com/v1/inline/widget.js', false, '1');
            wp_enqueue_script( 'Idx_plugin' );
        }

        public function add_custom_action_links( $links )
        {

            $settings_link = array(
                '<a href="' . admin_url('admin.php?page=edit.php?post_type=identity_kyc_config') . '">' . __('Configuration', 'prembly-identity-checkout') . '</a>',
            );
            return array_merge($settings_link, $links);
        }
    }

    if ( !class_exists('WP_List_Table') ) {
        include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
    }
