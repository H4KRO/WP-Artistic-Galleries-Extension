<?php
/*
Plugin Name: Artistic Galleries
Description: Un plugin wordpress pour les artistes !
Version: 0.1
Author: Eddie Vallier
Author URI: http://eddie.vallier.xyz
License: GPL2
*/

class Artistic_Galleries_Plugin{
    public function __construct(){
        add_action('admin_menu', array($this, 'build_menu'));
        register_activation_hook(__FILE__, array('Artistic_Galleries_Plugin', 'install'));
        add_action('admin_print_styles', array($this, 'enqeue_plugin'), 11 );

        include_once plugin_dir_path( __FILE__ ).'/artistic_galleries_widget.php';
        add_action('widgets_init', function(){register_widget('Artistic_Galleries_Widget');});

    }

    public function enqeue_plugin(){
        wp_enqueue_style( 'admin_dashboard_css', plugins_url('/styles/admin_dashboard.css', __FILE__) );
        wp_enqueue_script( 'admin_dashboard', plugins_url('/js/admin_dashboard.js', __FILE__) );
    }

    public static function install(){
        global $wpdb;
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}artworks (id INT AUTO_INCREMENT PRIMARY KEY, artworkName VARCHAR(20) NOT NULL, artworkUrl VARCHAR(255) NOT NULL, artworkGallery VARCHAR(20) NOT NULL);");
    }

    public function build_menu(){
        add_menu_page('Gestion des galeries', 'Galeries', 'manage_options', 'artistic_galleries', array($this, 'html_dashboard'));
    }

    public function html_dashboard(){
        $this->request_analyzer();
        echo '<div class="wrapper">';
        echo '<h1>'.get_admin_page_title().'</h1>';
        $this->html_upload_form();
        $this->html_gallery_selector();
        $this->html_display_artworks();
        $this->html_modal();
        //$this->debug();
        echo '</div>';
    }

    public function html_display_artworks(){
        ?>
        <div class="artistic_admin_block" id="artworks_displayer">
        <?php
        global $wpdb;
        $request = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}artworks", ARRAY_A);
        foreach($request as $item){
            echo '<div class="thumbnail"><img onclick="openModal(' . $item['id'] . ')" class="artwork" gallery="' . $item['artworkGallery'] . '" src="'.$item['artworkUrl'].'"></div>';
        }
        ?>
        </div>
        <script>
            initArtworksArray(<?php echo json_encode($request) ?>);
        </script>
        <?php
    }

    public function html_upload_form(){
        ?>
            <form class="artistic_admin_block" id="upload_form" action="" method="post" enctype="multipart/form-data">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="artworkName">Nom de l'oeuvre</label>
                            </th>
                            <td>
                                <input type="text" name="artworkName" class="regular-text">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="artworkGallery_new">Galerie</label>
                            </th>
                            <td>
                                <input type="text" name="artworkGallery_new" id="artworkGallery_new">
                                <select name="artworkGallery_choice" id="artworkGallery_choice">
                                    <?php
                                    foreach($this->getGalleries() as $galleryName){
                                        echo '<option>' . $galleryName['artworkGallery'] . '</option>';
                                    }
                                    ?>
                                    <option onclick="option_new()">Nouvelle galerie</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label class="button button-primary" for="fileToUpload">Parcourir...</label>
                                <input type="file" name="fileToUpload" id="fileToUpload"></br>
                            </th>
                            <td>
                                <input type="submit" class="button button-primary" value="Ajouter" name="submit">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        <?php
    }

    public function html_modal(){
        ?>
        <div id="modal">
            <div id="parameters">
                <div id="closeCross" onClick="closeModal()">&times;</div>
                <form method="post">
                    <input id="artworkId" type="hidden" name="id" value="">
                    <label for="artworkTitle">Nom : </label>
                    <input type="text" id="artworkTitle" name="artworkTitle" value=""></br>
                    <div class="thumbnail" id="parametersThumbnail"><img src="" id="artworkImage" class="thumbnail"></div></br>
                    <input type="submit" class="button button-primary" value="Editer" name="action">
                    <input type="submit" class="button button-primary" value="Supprimer" name="action">
                </form>
            </div>
        </div>
        <?php
    }

    public function debug(){
        echo '<pre>';
        print_r($_REQUEST);
        print_r($_FILES);
        print_r($this->getGalleries());
        echo '</pre>';
    }

    public function request_analyzer(){
        if(isset($_REQUEST['submit']) == 'Ajouter' && isset($_REQUEST['artworkName'])){
            $artworkName = $_REQUEST['artworkName'];
            $artworkPath = plugin_dir_path(__FILE__).'artworks/'.$_FILES['fileToUpload']['name'];
            move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $artworkPath);
            $artworkUrl = plugins_url( 'artworks/'.$_FILES['fileToUpload']['name'], __FILE__ );
            if($_REQUEST['artworkGallery_new'] == ''){
                $artworkGallery = $_REQUEST['artworkGallery_choice'];
            }else{
                $artworkGallery = $_REQUEST['artworkGallery_new'];
            }
            $this->database_upload($artworkName, $artworkGallery, $artworkUrl);
        }
        if($_REQUEST['action'] == 'Supprimer'){
            global $wpdb;
            $wpdb->delete($wpdb->prefix.'artworks', array('id'=>$_REQUEST['id']));
        }
        if($_REQUEST['action'] == 'Editer'){
            global $wpdb;
            $wpdb->update($wpdb->prefix.'artworks', array('artworkName'=>$_REQUEST['artworkTitle']), array('id'=>$_REQUEST['id']));
        }
    }

    public function get_extension($fileName){
        $fileNameExplosed = explode('.', $fileName);
        return '.'.$fileNameExplosed[sizeof($fileNameExplosed)-1];
    }

    public function database_upload($artworkName, $artworkGallery, $artworkUrl){
        global $wpdb;
        $wpdb->insert($wpdb->prefix.'artworks', array('artworkName'=>$artworkName, 'artworkUrl'=>$artworkUrl, 'artworkGallery'=>$artworkGallery));
    }

    public function getGalleries(){
        global $wpdb;
        return $wpdb->get_results("SELECT DISTINCT `artworkGallery` FROM {$wpdb->prefix}artworks", ARRAY_A);
    }

    public function html_gallery_selector(){
        ?>
        <div class="artistic_admin_block" id="gallery_selector">
            <?php
            foreach($this->getGalleries() as $gallery){
                echo '<button class="button button-primary" onclick="gallery_selector(\'' . $gallery['artworkGallery'] . '\')">' . $gallery['artworkGallery'] . '</button>';
            }
            ?>
            <button class="button button-primary" onclick="every_gallery_display()">Toutes</button>
        </div>
        <?php
    }

}

new Artistic_Galleries_Plugin();