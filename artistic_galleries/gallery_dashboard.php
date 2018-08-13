<?php
class Gallery_Dashboard{

    public $gallery_content;

    public function __construct($galleryId){
        global $wpdb;
        $artworks_request = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}artistic_artworks  WHERE `artGalleryId` = {$galleryId}");
        $this->gallery_content = json_decode(json_encode($artworks_request),true);
    }
    public function html(){

        echo '<div class ="thumbnails">';
        foreach($this->gallery_content as $artwork){
            ?>
                <img src="<?php echo $artwork['artUrl'] ?>">
            <?php
        }
        echo '</div>';
    }
}