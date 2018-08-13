<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?><a href="<?php echo admin_url() ?>admin.php?page=add_gallery" class="page-title-action">Ajouter</a></h1>
    <hr class="wp-header-end">
    <form id="movies-filter" method="get">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <?php $test_list_table->display() ?>
    </form>
</div>
<pre>
    <?php //print_r($_REQUEST); ?>
</pre>
