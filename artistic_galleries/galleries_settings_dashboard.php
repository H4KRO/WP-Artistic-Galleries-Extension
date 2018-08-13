<?php
class Galleries_Dashboard{
    public function __construct(){
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public static function register_settings(){
        register_setting( 'artistic_galleries_settings', 'artistic_galleries_number_of_columns');
        add_option('artistic_galleries_number_of_columns', 4);
    }
    public function add_admin_menu(){
        add_submenu_page('galeries', 'Options', 'Options', 'galleries_editor', 'settings_galleries', array($this, 'menu_html'));
    }
    public function menu_html(){
        ?>
        <div class="wrap">
            <h1><?php echo get_admin_page_title() ?></h1>
            <form method="post" action="options.php">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <?php settings_fields('artistic_galleries_settings') ?>
                            <th><label>Nombre de colonnes</label></th>
                            <td><input type="number" min="1" max="10" name="artistic_galleries_number_of_columns" value="<?php echo get_option('artistic_galleries_number_of_columns')?>"/></td>

                        </tr>
                    </tbody>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}