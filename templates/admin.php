<?php
    if ( ! defined( 'WPINC' ) ) {
        die;
    }    
?>
<style>
   .invalid-feedback{
    margin:0;
    padding: 0;
    color:red;
    font-size: 12px;
   }
</style>
<div class="form-group">
        <label for="description"><?php _e( 'Event Description', 'textdomain' ); ?></label>
        <textarea class="form-control" required name="description" placeholder="Add your description here" id="description"><?php echo $description; ?></textarea>
        <div class="invalid-feedback" id="descError"></div>
		<p>
			<label for="event_location"><?php _e( 'Date', 'textdomain' ); ?></label>
			<input class="form-control" required type="date" name="event_date" id="event_date" value="<?php echo esc_attr( date('Y-m-d',$date) ); ?>">
            <div class="invalid-feedback" id="dateError"></div>
		</p>
        <p>
			<label for="event_timings"><?php _e( 'Time', 'textdomain' ); ?></label>
			<input class="form-control" required type="time" name="event_timings" id="event_timings" value="<?php 
            if(!$timings=='') echo esc_attr( $timings );
            else echo "00:00";?>">
            <div class="invalid-feedback" id="timeError"></div>
		</p>
        <p>
			<label for="event_location"><?php _e( 'Location', 'textdomain' ); ?></label>
			<input class="form-control" required type="text" name="event_location" id="event_location" value="<?php echo esc_attr( $location ); ?>">
            <div class="invalid-feedback" id="locationError"></div>
        </p>
</div>
