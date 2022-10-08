<?php

use service\AcfCreator;

?>
</div>
<footer class="page-footer">

</footer>
<?php if ( ! isset( $_COOKIE[ 'hideCookieNotification' ] ) || ! $_COOKIE[ 'hideCookieNotification' ] ) : ?>
    <div id="cookie-notification" class="cookie-notification">
		<?php echo wpautop( AcfCreator::get( 'cookie_notification', 'options' ) ); ?>
        <button id="hide-cookie-notification-btn"></button>
    </div>
<?php endif; ?>
<?php wp_footer();
global $script_js;
if ( $script_js ): ?>
    <script><?php echo $script_js; ?></script>
<?php endif; ?>
</body>
</html>
