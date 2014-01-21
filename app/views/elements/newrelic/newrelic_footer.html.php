<?php
if (extension_loaded('newrelic')) {
    newrelic_get_browser_timing_footer();
}
?>