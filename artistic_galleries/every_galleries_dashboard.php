<?php
class Every_Galleries_Dashboard{

    public function __construct(){
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public function add_admin_menu(){
        add_submenu_page('galeries', 'Galeries', 'Toutes les galeries', 'galleries_editor', 'galeries', array($this, 'menu_html'));
    }

    public function menu_html(){
        if(!$this->is_editing()) {
            include dirname(__FILE__) . '/table/includes/class-tt-example-list-table.php';
            $test_list_table = new Galleries_List_Table();
            $test_list_table->prepare_items();
            include dirname(__FILE__) . '/table/page.php';
        }else{
            $gallleryDashboard = new Gallery_Dashboard($_REQUEST['gallery']);
            $gallleryDashboard->html();
        }
    }

    public function is_editing(){
        return isset($_REQUEST['action']) == 'edit';
    }

}