<?php
class Artistic_Galleries_Widget extends WP_Widget{
    public function __construct(){
        parent::__construct('artistic_galleries_widget', 'Artistic Galleries Widget', array('description' => 'Un affichage élégant'));
        if(! is_admin()) {
            wp_enqueue_style('artistic_galleries_css', plugins_url('/styles/artistic_galleries.css', __FILE__));
            wp_enqueue_style('lightbox_css', plugins_url('/styles/lightbox.css', __FILE__));

            wp_enqueue_script('lightbox_js', plugins_url('/js/lightbox-plus-jquery.js', __FILE__));
            wp_enqueue_script('gridify_js', plugins_url('/js/gridify.js', __FILE__));

            wp_enqueue_script('artistic_galleries_js', plugins_url('/js/artistic_galleries.js', __FILE__));
        }
    }

    public function form($instance)
    {
        $gallery = isset($instance['gallery']) ? $instance['gallery'] : '';
        ?>
        <p>
            <label for="<?php echo $this->get_field_name( 'gallery' ); ?>"><?php _e( 'Gallerie:' ); ?></label>
            <select id="<?php echo $this->get_field_id( 'gallery' ); ?>" name="<?php echo $this->get_field_name( 'gallery' ); ?>" type="text" value="<?php echo  $gallery; ?>" />
            <?php
                foreach($this->getGalleriesNames() as $galleryName){
                    echo '<option>' . $galleryName . '</option>';
                }
            ?>
            </select>
        </p>
        <?php
    }

    public function getGalleriesNames(){
        global $wpdb;
        $galleriesQuery = $wpdb->get_results("SELECT DISTINCT artworkGallery FROM {$wpdb->prefix}artworks", ARRAY_A);
        $galleries = array();
        foreach($galleriesQuery as $gallery){
            array_push($galleries, $gallery['artworkGallery']);
        }
        return $galleries;
    }

    public function getGalleryArtworks($gallery){
        global $wpdb;
        $artworks = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}artworks WHERE artworkGallery = '{$gallery}'", ARRAY_A);
        return $artworks;
    }

    public function ordonateGallery($galleryArtworks){
        $ordonate = array();
        $nb_columns = 4;
        foreach(range(0, $nb_columns-1) as $i){
            $ordonate[$i] = array();
        }
        $i = 0;
        foreach($galleryArtworks as $artwork){
            array_push($ordonate[$i], $artwork);
            $i++;
            if($i >= $nb_columns) $i = 0;
        }
        return $ordonate;
    }

    public function widget($args, $instance){
        echo '<div class="grid">';
        foreach($this->getGalleryArtworks($instance['gallery']) as $artwork){
            echo '<a href="' . $artwork['artworkUrl'] . '" data-lightbox="artistic_galleries"><img src="' . $artwork['artworkUrl'] . '"></a>';
        }
        echo '</div>';
        ?>
        <script type="text/javascript">
            window.onload = function(){
                var options =
                    {
                        srcNode: 'img',             // grid items (class, node)
                        margin: '5',             // margin in pixel, default: 0px
                        width: '500px',             // grid item width in pixel, default: 220px
                        max_width: '',              // dynamic gird item width if specified, (pixel)
                        resizable: true,            // re-layout if window resize
                        transition: 'all 0.5s ease' // support transition for CSS3, default: all 0.5s ease
                    }
                document.querySelector('.grid').gridify(options);
            }
        </script>
    <?php
    }
}