<?php

use service\AcfCreator;

$title = AcfCreator::get( 'widget_title', 'widget_' . $widget_id );
?>

<h1><?php echo $title; ?></h1>