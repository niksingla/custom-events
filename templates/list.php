<?php 
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<ol class="list list-view">
    <?php 
        $count = 0;
        if($query->have_posts()): 
            while($query->have_posts()){
                if($count==$atts['numposts']) break;
                $count++;
                $query->the_post();
                $event_desc = get_post_meta(get_the_ID(), 'description', true);
                $event_date = get_post_meta(get_the_ID(), '_event_date', true);
                $event_date = date("jS \of F", ($event_date));  
                $event_time = get_post_meta(get_the_ID(), '_event_timings', true);
                $event_time = date("h:i A", strtotime($event_time));  
                $event_loc = get_post_meta(get_the_ID(), '_event_location', true);
                ?>
                <li title="<?php echo $event_desc;?>">
                <a href="<?php echo get_the_permalink(); ?>">
                <strong><?php echo the_title();?></strong> at <?php 
                echo $event_time.', ';
                echo ' '.$event_date;
                echo ' in '.$event_loc ; ?>
                </a></li>
                <?php            
            }
            wp_reset_postdata();
        else:
            echo "The Events will appear here.";
        endif; 
    ?>
</ol>
