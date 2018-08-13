<?php
class New_Gallery_Dashboard{
    public function __construct(){
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }
    public function add_admin_menu(){
        add_submenu_page('galeries', 'Ajouter une galerie', 'Ajouter', 'galleries_editor', 'add_gallery', array($this, 'menu_html'));
    }

    public function menu_html(){
        if(isset($_POST['action'])){
            if($_POST['action'] = 'new'){
                global $wpdb;
                $wpdb->insert($wpdb->prefix . 'artistic_galleries', array('galleryName' => $_POST['galleryName']));
                echo '<script type=\'text/javascript\'>document.location.replace(\'' . admin_url() . 'admin.php?page=galeries\');</script>';
            }
        }

        ?>
        <div class="wrap">
                    <h1><?php echo get_admin_page_title() ?></h1>
        <form method="post" action="">
            <table class="form-table">
                <tbody>
                <tr>
                    <th><label>Nom de la galerie</label></th>
                    <td><input type="text" name="galleryName" value="Nouvelle galerie"/></td>

                </tr>
                </tbody>
            </table>
            <input type="hidden" name="action" value="new">
            <input name="submit" id="submit" class="button button-primary" value="Ajouter la galerie" type="submit">
        </form>
        </div>
        <?php
    }
}