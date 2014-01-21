<?php
if (extension_loaded('newrelic')) {
    newrelic_set_appname('GoDesigner');
    newrelic_get_browser_timing_header();
}
?>