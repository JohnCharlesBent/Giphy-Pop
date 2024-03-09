<?php

class GiphyPopAdminSettings
{
    /**
     * Holds the values used in the fields callback
     *
     * @var array
     */
    private $options;

    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_settings_page']);
        add_action('admin_init', [$this, 'settings']);
    }

    /**
     * Register plugin settings and fields
     *
     * @return void
     */
    public function settings() 
    {
        add_settings_section( 
            'giphy_pop_settings', 
            '', 
            '', 
            'giphy-pop-settings-admin', 
            array()
        );
        
        add_settings_field('gp_api_key', 'Giphy API Key', array($this, 'giphy_api_key_callback'), 'giphy-pop-settings-admin', 'giphy_pop_settings');
        
        add_settings_field('gp_limit', 'Giphy Results Limit', array($this, 'giphy_limit_callback'), 'giphy-pop-settings-admin', 'giphy_pop_settings');
        
        register_setting('giphy_pop', 'gp_api_key', array('sanitize_cb' => 'sanitize_text_field', 'default' => ''));
        
        register_setting('giphy_pop', 'gp_limit', array('sanitize_cb' => 'sanitize_text_field', 'default' => '1'));
    }

    /**
     * Add options page
     */
    public function add_settings_page(): void
    {
        // This page will live in the "Settings" menu in the WordPress Admin
        add_options_page(
            'Giphy Pop Settings',
            'Giphy Pop Settings',
            'manage_options',
            'giphy-pop-settings-admin',
            array( 
                $this,
                'create_admin_page'
            )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page(): void
    {

    if ( isset( $_POST['gp_api_key'] ) && isset( $_POST['gp_limit'] ) ) {
        $api_key = $_POST['gp_api_key'];
        $limit = $_POST['gp_limit'];
        update_option('gp_api_key', $api_key);
        update_option('gp_limit', $limit);
        ?>
        <div class="notice notice-success is-dismissible">
            <p>Settings Saved</p>
        </div>
        <?php
    }
?>
    <div class="wrap">
        <h1>Giphy Pop API Settings</h1>
        <p class="description">Add the Giphy API Key below. This plugin will add a metabox to all pages and posts that searches Giphy's API for gifs. Gifs can be copied and pasted into content fields.<p>
        <form method="POST" action="" enctype="multipart/form-data">
            <?php
                settings_fields('giphy_pop');
                // settings_fields('giphy_limit');
                do_settings_sections('giphy-pop-settings-admin');
                submit_button();
            ?>
        </form>
    </div>
<?php
    }

    /**
     * Sanitize settings field as needed
     * 
     * @param $input - array
     */
    public function sanitize(array $input): array
    {
        $new_input = array();
        if (isset($input['gp_api_key'])) {
            $new_input['gp_api_key'] = absint($input['gp_api_key']);
        }
        if (isset($input['gp_limit'])) {
            $new_input['gp_limit'] = absint($input['gp_limit']);
        }
        return $new_input;
    }

    /**
     * Outputs input for the Giphy API key
     *
     * @return void
     */
    public function giphy_api_key_callback()
    {
        $this->options['gp_api_key'] = get_option('gp_api_key', '');
    ?>
        <input type="password" id="gp-api-key" name="gp_api_key" value="<?= isset($this->options['gp_api_key']) ? esc_attr($this->options['gp_api_key']) : ''; ?>" />
        <p class="description">The API Key provided by the <a href="https://developers.giphy.com/docs/api/#quick-start-guide">Giphy API</a>.</p>
    <?php
    }

    public function giphy_limit_callback()
    {
        $this->options['gp_limit'] = get_option('gp_limit', '5');
    ?>
        <input type="number" id="gp-limit" name="gp_limit" value="<?= isset($this->options['gp_limit']) ? esc_attr($this->options['gp_limit']) : '1'; ?>" min="1" max="25"/>
        <p class="description">The maximum number of results you want returned from the API. The API maximum is 25 results per page.</p>
    <?php
    }
}

if (is_admin()) { $giphy_pop_settings_page = new GiphyPopAdminSettings(); }