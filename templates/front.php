<?php
if (!defined('WPINC')) {
    die;
}

?>
<h5 style="text-align: center;">All Events</h5>
<?php
$view = get_option('event_view_setting');;
$sort = $atts['sort'];
$args = array(
    'post_type' => 'event'
);
switch ($sort) {
    case 'date':
        $args['meta_key'] = '_event_date';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'ASC';
        break;
    case 'title':
        $args['orderby'] = 'title';
        $args['order'] = 'ASC';
        break;
}
$query = new WP_Query($args);
switch ($view) {
    case 'list':
        include 'list.php';
        break;
    case 'grid':
        include 'grid.php';
        break;
    case 'calendar':
        include 'calendar.php';
        break;
    default:
        include 'list.php';
        break;
}
?>
