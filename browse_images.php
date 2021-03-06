<?php

/*
* File: browse_images.php
* Author: Peter Gill
* Copyright: 2012 Peter Gill
* Link: https://github.com/majorsilence/ImgSite
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details:
* http://www.gnu.org/licenses/gpl.html
*
*/

include("connection_info.php");

if(session_id() == '') 
{
    session_start();
}

 echo "<!DOCTYPE html>\n";

?>

<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title></title>
        
        
        <?php

            echo site_header_info();

        ?>
        
		<link rel="stylesheet" href="styles/galleriffic-2.css" type="text/css" />
		<script type="text/javascript" src="javascript/jquery.galleriffic.js"></script>
		<script type="text/javascript" src="javascript/jquery.opacityrollover.js"></script>
		<script type="text/javascript">
			document.write('<style>.noscript { display: none; }</style>');
		</script>
        
        

        
        
	</head>
	<body>
        <?php
        echo site_menu();
        ?>

    
        <div class="maincontent">
            <div id="page">
                <div id="container">
                    <h2>Latest Images</h2>

                    <!-- Start Advanced Gallery Html Containers -->
                    <div id="gallery" class="content">
                        <div id="controls" class="controls"></div>
                        <div class="slideshow-container">
                            <div id="loading" class="loader"></div>
                            <div id="slideshow" class="slideshow"></div>
                        </div>
                        <div id="caption" class="caption-container"></div>
                    </div>
                    
                    <div id="thumbs" class="navigation">
                        <ul class="thumbs noscript">
                            
                            <?php
                            
                            $dbh = get_connection();
        
                            $sql = "SELECT Id, FileNameOrig, FileNameView, FileNamePreview, UploadTime FROM UsersMedia ORDER BY UploadTime DESC LIMIT 50;";
                            $stmt = $dbh->prepare($sql);
                            $stmt->execute();
                            
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            
                            if (count($result) > 0)
                            {
                            
                                foreach($result as $value)
                                {
                                    $img_preview_src = $value["FileNamePreview"];
                                    $img_view_src = $value["FileNameView"];
                                    $img_orig_src = $value["FileNameOrig"];
                                
                                    echo "<li>\n";
                                    echo '    <a class="thumb" name="leaf" href="' . $img_view_src . '" title="Title #0">' . "\n";
                                    echo '        <img src="' . $img_preview_src . '" alt="Title #0" />' . "\n";
                                    echo "    </a>\n";
                                    echo '    <div class="caption">' . "\n";
                                    echo '        <div class="download">' . "\n";
                                    echo '            <a href="' . $img_orig_src . '">Download Original</a>' . "\n";
                                    echo "        </div>\n";
                                    echo '        <div class="image-title">Title #0</div>' . "\n";
                                    echo '        <div class="image-desc">Description</div>' . "\n";
                                    echo "    </div>\n";
                                    echo "</li>\n";
                                }
                            }
                            
                            ?>
                            
                            <li>
                                <a class="thumb" name="leaf" href="http://farm4.static.flickr.com/3261/2538183196_8baf9a8015.jpg" title="Title #0">
                                    <img src="http://farm4.static.flickr.com/3261/2538183196_8baf9a8015_s.jpg" alt="Title #0" />
                                </a>
                                <div class="caption">
                                    <div class="download">
                                        <a href="http://farm4.static.flickr.com/3261/2538183196_8baf9a8015_b.jpg">Download Original</a>
                                    </div>
                                    <div class="image-title">Title #0</div>
                                    <div class="image-desc">Description</div>
                                </div>
                            </li>

                            

                            
                        </ul>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>
        </div>

		<script type="text/javascript">
			jQuery(document).ready(function($) {
				// We only want these styles applied when javascript is enabled
				$('div.navigation').css({'width' : '300px', 'float' : 'left'});
				$('div.content').css('display', 'block');

				// Initially set opacity on thumbs and add
				// additional styling for hover effect on thumbs
				var onMouseOutOpacity = 0.67;
				$('#thumbs ul.thumbs li').opacityrollover({
					mouseOutOpacity:   onMouseOutOpacity,
					mouseOverOpacity:  1.0,
					fadeSpeed:         'fast',
					exemptionSelector: '.selected'
				});
				
				// Initialize Advanced Galleriffic Gallery
				var gallery = $('#thumbs').galleriffic({
					delay:                     2500,
					numThumbs:                 15,
					preloadAhead:              10,
					enableTopPager:            true,
					enableBottomPager:         true,
					maxPagesToShow:            7,
					imageContainerSel:         '#slideshow',
					controlsContainerSel:      '#controls',
					captionContainerSel:       '#caption',
					loadingContainerSel:       '#loading',
					renderSSControls:          true,
					renderNavControls:         true,
					playLinkText:              'Play Slideshow',
					pauseLinkText:             'Pause Slideshow',
					prevLinkText:              '&lsaquo; Previous Photo',
					nextLinkText:              'Next Photo &rsaquo;',
					nextPageLinkText:          'Next &rsaquo;',
					prevPageLinkText:          '&lsaquo; Prev',
					enableHistory:             false,
					autoStart:                 false,
					syncTransitions:           true,
					defaultTransitionDuration: 900,
					onSlideChange:             function(prevIndex, nextIndex) {
						// 'this' refers to the gallery, which is an extension of $('#thumbs')
						this.find('ul.thumbs').children()
							.eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
							.eq(nextIndex).fadeTo('fast', 1.0);
					},
					onPageTransitionOut:       function(callback) {
						this.fadeTo('fast', 0.0, callback);
					},
					onPageTransitionIn:        function() {
						this.fadeTo('fast', 1.0);
					}
				});
			});
		</script>
	</body>
</html>