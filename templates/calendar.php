<?php 
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>

<?php

global $wpdb, $table_prefix;
$wp_posts = $table_prefix.'posts';
$q = "SELECT * FROM `$wp_posts` WHERE `post_type`= 'event' AND `post_status`='publish'";
$events = $wpdb->get_results($q);
if(!empty($events)):
$calendar = '<div class="calendar month-top">'.date('F', mktime(0,0,0,getdate()['mon'])).'</div>';
$calendar .= '<div class="calendar-with-events">';
$calendar .= '<table>';
$calendar .= '<thead>';
$calendar .= '<tr>';
$calendar .= '<th>Sun</th>';
$calendar .= '<th>Mon</th>';
$calendar .= '<th>Tue</th>';
$calendar .= '<th>Wed</th>';
$calendar .= '<th>Thu</th>';
$calendar .= '<th>Fri</th>';
$calendar .= '<th>Sat</th>';
$calendar .= '</tr>';
$calendar .= '</thead>';
$calendar .= '<tbody>';

$today = getdate();
$month = $today['mon'];
$year = $today['year'];

$calendar .= '<tr>';
$weekday = date('w',mktime(0,0,0,$month,1,$year));
for($i = 0; $i < $weekday; $i++){
    $calendar .= '<td></td>';
}
$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
for($day = 1; $day <= $days_in_month; $day++){
    if($weekday == 7){
        $calendar .= '</tr><tr>';
        $weekday = 0;
    }
    $weekday++;
    $event_day = false;
    foreach($events as $event){
        $event_date = get_post_meta($event->ID, '_event_date', true);
        $event_link = get_permalink($event->ID);
        if(date('j',($event_date)) == $day && date('n',($event_date)) == $month && date('Y',($event_date)) == $year){
            $event_name = $event->post_title;
            $calendar .= '<td class="event" data-event="' . $event_name . '" title="'.$event_name.'">' .'<a href="'.$event_link.'" >'. $day . '</a></td>';
            $event_day = true;
            break;
        }
    }
    if(!$event_day){
        $calendar .= '<td>' . $day . '</td>';
    }
}
$calendar .= '</tr>';

$calendar .= '</tbody>';
$calendar .= '</table>';
$calendar .= '</div>';

echo $calendar;
else:
    echo "The Events will appear here.";
endif;
?>
