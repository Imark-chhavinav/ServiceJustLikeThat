<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.2
 */
	$fields = get_fields('option'); //pre($fields);
?>
	<div style="display:none" class="loader_team"></div>
	<div class="clearfix"></div>
    <footer>
        <div class="container">
            <div class="footer-logo">
                <a href="#"><img src="<?php echo $fields['footer_logo']['url']; ?>" alt="<?php echo $fields['footer_logo']['title']; ?>"></a>
            </div>
            <div class="footer-menu">
			<?php wp_nav_menu( array(  'menu' => 'Top Menu' , 'container_class' => '' , 'menu_class'=> '' ) ); ?>
                <ul>
                    <!--<li><a href="#">Home</a></li>
                    <li><a href="#">About Us </a></li>
                    <li><a href="#">How it works</a></li>
                    <li><a href="#">Directory</a></li>
                    <li><a href="#">Jobs</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Contact Us</a></li>-->
                </ul>
            </div>
            <div class="app-links-cover">
                <ul>
                    <li>
                        <a href="#" target="_blank"><img src="<?php echo $fields['ios_app_footer_icon']['url']; ?>" alt="<?php echo $fields['ios_app_footer_icon']['title']; ?>"></a>
                    </li>
                    <li>
                        <a href="#" target="_blank"><img src="<?php echo $fields['android_app_footer_icon']['url']; ?>" alt="<?php echo $fields['android_app_footer_icon']['title']; ?>"></a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
    <div class="copyright-cover">
        <div class="container">
            <div class="copyright-links pull-left">
                <ul>
                    <li><a href="#">Terms & Conditions </a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="copyright-text">
                <p>&copy; <?php echo date("Y"); ?> servicesjustlikethat.com</p>
            </div>
            <div class="powered-by pull-right">
                <p>Powered By - <a href="https://www.imarkinfotech.com" target="_blank">iMark Infotech</a></p>
            </div>
        </div>
    </div> 
    <?php wp_footer(); ?>
</body>

</html>