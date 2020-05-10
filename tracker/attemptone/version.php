<?php
//version details for block
//used to ensure the plugin is compatible with the given Moodle site, as well as to spot whether an upgrade is needed.

$plugin->component = 'block_attemptone';  // Recommended since 2.0.2 (MDL-26035). Required since 3.0 (MDL-48494)
//The full frankenstyle component name in the form of plugintype_pluginname.

$plugin->version = 2011062800;  // YYYYMMDDHH (year, month, day, 24-hr time)
//The version number of the plugin.

$plugin->requires = 2010112400; // YYYYMMDDHH (This is the release version for Moodle 2.0)
//The minimum version number of Moodle core required by this plugin.