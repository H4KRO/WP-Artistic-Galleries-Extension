<?php
class Artistic_Galleries_Widget extends WP_Widget{
    public function __construct(){
        parent::__construct('artistic_galleries_widget', 'Artistic Galleries Widget', array('description' => 'Un affichage élégant'));

    }
    public function widget($args, $instance){

    }
}