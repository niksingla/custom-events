# Custom Events

Creates custom post type Events, easy to use.

## Features

* Creates custom post type and shows at the left sidebar admin menu.
* Users can see the upcoming events.
* Admin can decide the view type - Grid, List or Calendar.
* Admin can add an Event widget wherever required.
* Admin can sort the events by Event names, date, etc.
* Admin can decide number of events to display

## Installation

Install the plugin in WordPress. You can download a
[zip via GitHub]((https://github.com/niksingla/custom-events.git)) and upload it using the WordPress
plugin uploader ("Plugins" > "Add New" > "Upload Plugin").

## How it works

Easy to Install and activate the plugin and use the following shortcode on any page:
```php
[events_list]
``` 
### Shortcodes with parameters
```php
[events_list sort=date]
```
```php
[events_list sort=title]
``` 
```php
[events_list numposts=4]
``` 

## License

Custom Events is licensed under the GPL v2 or later.
