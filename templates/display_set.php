<?php 
if ( ! defined( 'WPINC' ) ) {
	die;
}
$view = $setting_value;
?>

<div class="form-group">
    <label for="event_view"><?php _e( 'Event display Settings', 'textdomain' ); ?></label>
    <select class="form-select" id="event_view_setting" name="event_view_setting">
        <option <?php if($view =='list') echo "selected"; ?> value="list">List</option>
        <option <?php if($view =='grid') echo "selected"; ?> value="grid">Grid</option>
        <option <?php if($view =='calendar') echo "selected"; ?> value="calendar">Calendar</option>
    </select>
</div>

