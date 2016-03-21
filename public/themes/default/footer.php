    <?php if ( ! isset($show) || $show == true) : ?>
    <hr />
    <div class="row-fluid">
        <p>In case of any issues, please write to <a href="mailto:info@reelbank.in">info@reelbank.in</a>
    </div>
    <?php endif; ?>
	<div id="debug"><!-- Stores the Profiler Results --></div>
    <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?php echo js_path(); ?>jquery-1.7.2.min.js"><\/script>');</script>
    <?php echo Assets::js(); ?>
</body>
</html>