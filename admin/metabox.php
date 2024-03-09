<?php

/**
 * Creates a metabox on all pages and posts that searches Giphy.
 */
class GiphyPopMetaBox
{
    /**
     * $api_key
     * - Giphy API key, stored as an option - [gp_api_key] - in the wp_options table
     * @var string
     */
    public $api_key;

    /**
     * $api_limit
     * - The maximum number of results returned by the API per page.
     *
     * @var string
     */
    public $api_limit;

    public function __construct() {
        $this->api_key = get_option('gp_api_key', '');
        $this->api_limit = get_option('gp_limit', '');
        add_action('add_meta_boxes', [$this, 'add_giphy_metabox']);
    }

    /**
     * Adds a giphy search metabox to posts and pages
     *
     * @return void
     */
    public function add_giphy_metabox(): void {
        $screens = [
            'post',
            'page'
        ];

        foreach ($screens as $screen) {
            add_meta_box(
                'giphy_metabox_id',
                'Search Giphy',
                [$this, 'giphy_metabox_html'],
                $screen
            );
        }
    }

    public function giphy_metabox_html() {
    ?>
       <div class="giphy_search_wrapper">
            <input type="text" id="search_giphy" name="giphy_search_query" placeholder="Enter a serach term ..."/>
            <input type="submit" class="button button-primary" name="giphy_search_submit" value="search" />
        </div>
        <div id="giphy-messages" class="hidden">
            <p class="message-text"></p>
        </div>
        <div id="giphy_search_results" class="hidden">
            <header class="search-results-header">
                <button type="button" class="button button-secondary hidden" id="giphy-next-page" data-offset="<?= $this->api_limit; ?>">More results</button>
                <button type="button" class="button button-primary" id="clear-giphy-results">Clear Results</button>
            </header>
            <ul class="results"></ul>
        </div>
        <script type="text/javascript">
            const api_key = '<?= $this->api_key; ?>';
            const api_limit = '<?= $this->api_limit; ?>';
        </script>
    <?php
    }

}

$metaBox = new GiphyPopMetaBox();
