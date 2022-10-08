<?php

use service\WidgetCreator;

get_header();
?>
    <section>
		<?php WidgetCreator::render_widget_area( 'front_page_widget_area' ); ?>
    </section>
<?php get_footer();