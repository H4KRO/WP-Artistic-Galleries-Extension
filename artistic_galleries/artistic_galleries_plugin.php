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
        $this->html_display_artworks();
        $this->html_upload_form();
        $this->debug();
        echo '</div>';
    }
    public function html_display_artworks(){
        global $wpdb;
        $request = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}artworks", ARRAY_A);
        foreach($request as $item){
            echo '<img class="thumbnail" src="'.$item['artworkUrl'].'">';
        }
        ?>
        <?php

    }
    public function html_upload_form(){
        ?>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="text" name="artworkName">
                <input type="file" name="fileToUpload" id="fileToUpload">
                <input type="submit" value="upload" name="submit">
            </form>
        <?php
    }
    public function debug(){
        echo '<pre>';
        print_r($_REQUEST);
        print_r($_FILES);
        echo '</pre>';
    }
    public function request_analyzer(){
        if(isset($_REQUEST['submit']) == 'upload' && isset($_REQUEST['artworkName'])){
            $artworkName = $_REQUEST['artworkName'];
            $artworkPath = plugin_dir_path(__FILE__).'artworks/'.$_FILES['fileToUpload']['name'];
            move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $artworkPath);
            $artworkUrl = plugins_url( 'artworks/'.$_FILES['fileToUpload']['name'], __FILE__ );
            $this->database_upload($artworkName, $artworkUrl);
        }
    }
    public function get_extension($fileName){
        $fileNameExplosed = explode('.', $fileName);
        return '.'.$fileNameExplosed[sizeof($fileNameExplosed)-1];
    }
    public function database_upload($artworkName, $artworkUrl){
        global $wpdb;
        $wpdb->insert($wpdb->prefix.'artworks', array('artworkName'=>$artworkName, 'artworkUrl'=>$artworkUrl));
    }
}

new Artistic_Galleries_Plugin();