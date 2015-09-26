<?php
if (extension_loaded('newrelic')) {
    echo newrelic_get_browser_timing_footer();
}