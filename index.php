<?php
// Prevent browser caching and redirect to the latest React app
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header('Location: /reports/client-work-tracker/index.html?v=' . time());
exit();
