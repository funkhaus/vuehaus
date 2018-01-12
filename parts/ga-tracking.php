<?php
    // Get options out of General Setting panel
    $tracking_names = array('ga_tracking_code_1', 'ga_tracking_code_2');
    $tracking_ids = array();
    foreach($tracking_meta_names as $name) {
        $option = get_option($name)
        if( !empty($option) ) {
            $tracking_ids[] = $option;
        }
    }
?>
<?php if( !empty($tracking_ids) and !current_user_can('administrator') ) : ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $tracking_ids[0]; ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      <?php foreach($tracking_ids as $id) : ?>
      gtag('config', '<?php echo $id; ?>');
      <?php endforeach; ?>
    </script>
<php endif; ?>
