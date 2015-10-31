<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Piratenpartij_Nederland
 * @since Piratenpartij Nederland 1.0
 */

get_header(); ?>
<script type="text/javascript">
/*
 * Custom All-in-One Event Calendar view. This version will use Open Street Maps for showing the location maps. The originial view uses Google Maps
 * The Pirateparty like to use open source and open data projects. Therefore this modification is made by The Pirateparty Netherlands (TheYOSH).
*/

// Load the new maps when the page is done loading. This will replace the contents of the original Google Maps html.
jQuery(function() {
  // Try to find the coordinates
  var coordinates = jQuery('#ai1ec-gmap-address').val();
  // Try to find the DOM location
  var mapReplaceMentDiv = jQuery('.ai1ec-gmap-placeholder:first');

  // Not found, so return and stop the processing
  if (coordinates == '' || !mapReplaceMentDiv.length) return;

  // We have coordinates. So make them floats so we can calculate with it
  coordinates = coordinates.split(',');
  coordinates[0] = parseFloat(coordinates[0]);
  coordinates[1] = parseFloat(coordinates[1]);

  // Define the view box at OSM. This is done by specifying box coordinates. These can be calculates based on the marker spot
  // Make sure it is a float value. Playing with this value will also make the zoomlevel behave different
  var expandSize = 0.0005
  // Base OSM embed url
  var OSMEmbedurl = '//www.openstreetmap.org/export/embed.html';
  // Add the view box coordinates
  OSMEmbedurl += '?bbox=' + (coordinates[1] - expandSize) + ',' + (coordinates[0] - expandSize) + ',' + (coordinates[1] + expandSize) + ',' + (coordinates[0] + expandSize);
  // Add the marker
  OSMEmbedurl += '&layer=mapnik&marker=' + coordinates[0] + ',' + coordinates[1];

  // Create the external link to a full screen map
  var OSMBigUrl = '//www.openstreetmap.org/';
  // Add coordinates
  OSMBigUrl += '?mlat=' + coordinates[0] + '&mlon=' + coordinates[1];
  // Add Zoom level and marker
  OSMBigUrl += '#map=17/' + coordinates[0] + '/' + coordinates[1];

  // Create the new iFrame embed code for showing the OSM map
  var OSMIframe = jQuery('<iframe>').attr({'width'        : '100%',
                                           'height'       : '100%',
                                           'frameborder'  : 0,
                                           'scrolling'    : 'no',
                                           'marginheight' : 0,
                                           'marginwidth'  : 0,
                                           'src'          : OSMEmbedurl});

  var OSMBigMapLink = jQuery('<a>').attr({'href'   : OSMBigUrl,
                                          'target' : '_blank',
                                          'title'  : 'Klik voor een grotere map in een nieuw venster'});

  // Use the same text as the title tag text
  OSMBigMapLink.text(OSMBigMapLink.attr('title'));

  // Replace the Google map div with the OSM Iframe object
  mapReplaceMentDiv.html(OSMIframe);
  // Add the external OSM link to the div
  mapReplaceMentDiv.append(OSMBigMapLink);
  // Remove the trigger class for Google Maps
  mapReplaceMentDiv.removeClass('ai1ec-gmap-placeholder');

  // Some Google cleanup
  jQuery('.ai1ec-gmap-container').remove();
});
</script>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			/*
			 * Include the post format-specific template for the content. If you want to
			 * use this in a child theme, then include a file called called content-___.php
			 * (where ___ is the post format) and that will be used instead.
			 */
			get_template_part( 'content', get_post_format() );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

			// Previous/next post navigation.
			the_post_navigation( array(
				'next_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Next', 'ppnl' ) . '</span> ' .
					'<span class="screen-reader-text">' . __( 'Next post:', 'ppnl' ) . '</span> ' .
					'<span class="post-title">%title</span>',
				'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __( 'Previous', 'ppnl' ) . '</span> ' .
					'<span class="screen-reader-text">' . __( 'Previous post:', 'ppnl' ) . '</span> ' .
					'<span class="post-title">%title</span>',
			) );

		// End the loop.
		endwhile;
		?>
		</main><!-- .site-main -->
	</div><!-- .content-area -->
<?php get_footer(); ?>
