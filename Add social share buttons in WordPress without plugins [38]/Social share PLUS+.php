/**
* Social share buttons in wordpress by WPCookie
* https://redpishi.com/wordpress-tutorials/social-share-buttons-wordpress-no-plugin/
*/
add_filter( 'the_content', function ( $content ) { if ( is_singular( 'post' ) ) {

	/*********************
	* choose icons
	**********************/
	$facebook  = 1;
	$x         = 1;
	$LinkedIn  = 1;
	$pinterest = 1;
	$email     = 1;
	$whatsapp  = 1;
	$telegram  = 1;

	/*************************
	* choose locations
	*************************/
    $palce = "top"; //top, bottom, left, right
	/************************/
    $end = $befor = "";

	$link = get_the_permalink();
	$title = get_the_title();
	if ($facebook == 1) {
		$facebook_icon = '
	   <li class="social-link">
		  <a href="http://www.facebook.com/sharer.php?u='.$link.'" class="social-link-anchor facebook">
			 <svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
				<path d="M12 2C6.5 2 2 6.5 2 12c0 5 3.7 9.1 8.4 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.3v7C18.3 21.1 22 17 22 12c0-5.5-4.5-10-10-10z"></path>
			 </svg>
			 <span class="screen-reader-text">Facebook</span>
		  </a>
	   </li>
	';
	} else { $facebook_icon = '';  }
	if ( $x == 1 ) {
		$x_icon = '
	<li class="social-link">
		  <a href="https://x.com/share?url='.$link.'&text='.$title.'" class="social-link-anchor x">
			<svg xmlns="http://www.w3.org/2000/svg" id="svg16" width="90.709" height="84.9" version="1.1" viewBox="0 0 24 22.463"><g id="layer1" transform="translate(-19.357 -77.422)"><g id="layer1-3" style="fill:#fff" transform="matrix(.04028 0 0 .04028 -.361 62.33)"><path id="path1009" d="M491.03 374.75 721.1 682.38 489.58 932.49h52.11l202.7-218.98 163.77 218.98h177.32L842.46 607.56l215.5-232.81h-52.11L819.18 576.42 668.35 374.75Zm76.63 38.39h81.46l359.72 480.97h-81.46z" style="fill:#fff"/></g></g><style id="style18" type="text/css">.st0{stroke:#fff;stroke-miterlimit:10}</style></svg>
            <span class="screen-reader-text">Twitter</span>
		  </a>
	   </li>
	';
	} else { $x_icon = ''; }
	if ( $whatsapp == 1 ) {
		$whatsapp_icon = '
	<li class="social-link">
		  <a href="whatsapp://send?text=Check this out! ➡️ '.$link.'" class="social-link-anchor whatsapp" data-action="share/whatsapp/share">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" aria-hidden="true"><path d="M15.484 20.491c-1.794-.489-3.87-1.415-5.255-2.347-3.12-2.097-6.386-6.056-7.25-8.787-.44-1.384-.245-3.158.466-4.251.85-1.308 1.466-1.73 2.524-1.73.812 0 1.125.136 1.355.588.37.727 1.684 3.949 1.684 4.129 0 .249-.448.933-1.074 1.64-.28.317-.508.668-.508.781 0 .484 1.496 2.488 2.61 3.497a12.58 12.58 0 0 0 2.976 2.011c1.126.535 1.479.639 1.726.506.112-.06.632-.623 1.155-1.252.884-1.061.972-1.139 1.235-1.086.373.074 4.024 1.795 4.257 2.006.32.289.185 1.635-.246 2.469-.248.478-1.012 1.092-1.922 1.542-.798.396-.862.41-1.933.441-.857.024-1.264-.011-1.8-.157z" style="fill:white;stroke-width:.098874"/></svg><span class="screen-reader-text">Whatsapp</span>
		  </a>
	   </li>
	';
	} else { $whatsapp_icon = ''; }
	if ( $telegram == 1 ) {
		$telegram_icon = '
	<li class="social-link">
		  <a href="https://telegram.me/share/url?url='.$link.'&text='.$title.'" class="social-link-anchor telegram">
			<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" aria-hidden="true"><path d="M15.951 19.784c-2.654-1.948-3.006-2.16-3.38-2.042-.122.039-.91.63-1.75 1.316-1.505 1.225-1.838 1.399-2.092 1.089-.07-.086-.573-1.584-1.117-3.328l-.989-3.171-2.605-.977c-1.433-.538-2.652-1.052-2.709-1.142-.172-.272-.122-.46.174-.653.498-.325 20.763-8.085 21.013-8.047.133.02.295.12.36.224.135.216-3.553 18.164-3.791 18.452a.499.499 0 0 1-.348.167c-.112-.004-1.356-.853-2.766-1.888zm-6.58-2.2c.033-.12.127-.711.209-1.313.1-.737.235-1.217.416-1.47.147-.207 2.068-2.042 4.268-4.078 3.646-3.376 4.528-4.323 3.855-4.144-.31.083-9.78 5.93-10.136 6.258-.364.335-.357.708.038 2.129.745 2.678.804 2.837 1.052 2.837.13 0 .264-.099.298-.219z" style="fill:white;stroke-width:.125"/></svg>
            <span class="screen-reader-text">Telegram</span>
		  </a>
	   </li>
	';
	} else { $telegram_icon = ''; }
	if ( $LinkedIn == 1 ) {
		$LinkedIn_icon = '
	  <li class="social-link">
		  <a href="http://www.linkedin.com/sharing/share-offsite/?url='.$link.'" class="social-link-anchor linkedin">
             <svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
				<path d="M19.7,3H4.3C3.582,3,3,3.582,3,4.3v15.4C3,20.418,3.582,21,4.3,21h15.4c0.718,0,1.3-0.582,1.3-1.3V4.3 C21,3.582,20.418,3,19.7,3z M8.339,18.338H5.667v-8.59h2.672V18.338z M7.004,8.574c-0.857,0-1.549-0.694-1.549-1.548 c0-0.855,0.691-1.548,1.549-1.548c0.854,0,1.547,0.694,1.547,1.548C8.551,7.881,7.858,8.574,7.004,8.574z M18.339,18.338h-2.669 v-4.177c0-0.996-0.017-2.278-1.387-2.278c-1.389,0-1.601,1.086-1.601,2.206v4.249h-2.667v-8.59h2.559v1.174h0.037 c0.356-0.675,1.227-1.387,2.526-1.387c2.703,0,3.203,1.779,3.203,4.092V18.338z"></path>
			 </svg>
			 <span class="screen-reader-text">LinkedIn</span>
		  </a>
	   </li>
	';
	} else { $LinkedIn_icon = '';}
	if ( $pinterest == 1 ) {
		if(function_exists("the_post_thumbnail")) { $thumbnail = get_the_post_thumbnail_url(); } else { $thumbnail = ''; }
		$pinterest_icon = '
	 <li class="social-link">
		  <a href="http://www.pinterest.com/pin/create/button/?url='.$link.'&media='.$thumbnail.'&description='.$title.'" class="social-link-anchor pinterest" data-pin-do="buttonPin" data-pin-config="beside" data-pin-color="red" data-pin-height="28">
			 <svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
				<path d="M12.289,2C6.617,2,3.606,5.648,3.606,9.622c0,1.846,1.025,4.146,2.666,4.878c0.25,0.111,0.381,0.063,0.439-0.169 c0.044-0.175,0.267-1.029,0.365-1.428c0.032-0.128,0.017-0.237-0.091-0.362C6.445,11.911,6.01,10.75,6.01,9.668 c0-2.777,2.194-5.464,5.933-5.464c3.23,0,5.49,2.108,5.49,5.122c0,3.407-1.794,5.768-4.13,5.768c-1.291,0-2.257-1.021-1.948-2.277 c0.372-1.495,1.089-3.112,1.089-4.191c0-0.967-0.542-1.775-1.663-1.775c-1.319,0-2.379,1.309-2.379,3.059 c0,1.115,0.394,1.869,0.394,1.869s-1.302,5.279-1.54,6.261c-0.405,1.666,0.053,4.368,0.094,4.604 c0.021,0.126,0.167,0.169,0.25,0.063c0.129-0.165,1.699-2.419,2.142-4.051c0.158-0.59,0.817-2.995,0.817-2.995 c0.43,0.784,1.681,1.446,3.013,1.446c3.963,0,6.822-3.494,6.822-7.833C20.394,5.112,16.849,2,12.289,2"></path>
			 </svg>
			 <span class="screen-reader-text">Pinterest</span>
		  </a>
		  <script type="text/javascript" async defer src="//assets.pinterest.com/js/pinit.js"></script>
	   </li>
	';
	} else { $pinterest = ''; };
	if ( $email == 1 ) {
		$email_icon = '
	 <li class="social-link">
		  <a href="mailto:?subject=Please%20visit%20this%20link%20'.$link.'&body=Hello!%20I%20thought%20you%20would%20find%20this%20article%20interesting:%20'.$title.'.%20Here%20is%20the%20website%20link:%20'.$link.'.%20Thank%20you." class="social-link-anchor email">
			 <svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
				<path d="M20,4H4C2.895,4,2,4.895,2,6v12c0,1.105,0.895,2,2,2h16c1.105,0,2-0.895,2-2V6C22,4.895,21.105,4,20,4z M20,8.236l-8,4.882 L4,8.236V6h16V8.236z"></path>
			 </svg>
			 <span class="screen-reader-text">Mail</span>
		  </a>
	   </li>
	';
	} else { $email_icon = '';}
	$style = '
	<style>
	.social-link-anchor svg {
		color: white;
		fill: white;
	}
	a.social-link-anchor.email svg {
		color: #444444;
		fill: #444444;
	}
	a.social-link-anchor.facebook {
		background-color: #1778f2;
	}
	a.social-link-anchor.whatsapp {
    background-color: #26d366;
    border-radius: 100%;
    }
    a.social-link-anchor.telegram {
    background-color: #28a8e9;
    border-radius: 100%;
    }
	a.social-link-anchor.linkedin {
		background-color: #0d66c2;
	}
	a.social-link-anchor.pinterest {
		background-color: #e60122;
	}
	a.social-link-anchor.email {
		background-color: #f0f0f0;
	}
	a.social-link-anchor.x {
    background: black;
    }
    a.social-link-anchor.x svg {
    transform: scale(0.6);
    }
	a.social-link-anchor {
		width: 36px;
		height: 36px;
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 2px;
	}
	a.social-link-anchor:hover {
		transform: scale(1.1);
	}
	a.social-link-anchor {
		transition: all 0.15s ease;
	}
	li.social-link {
		list-style: none;
	}
	ul.end-post-layout {
		display: flex;
		gap: 15px;
		flex-direction: row;
		flex-wrap: wrap;
	}
	ul.left-post-layout {
		display: flex;
		gap: 10px;
		flex-direction: column;
		flex-wrap: wrap;
		position: fixed;
		top: 50%;
		left: 10px;
		transform: translateY(-50%);
		align-content: flex-start;
		margin: 0px!important;
		background-color: white;
		padding: 5px;
	}
	ul.right-post-layout {
		display: flex;
		gap: 10px;
		flex-direction: column;
		flex-wrap: wrap;
		position: fixed;
		top: 50%;
		right: 10px;
		transform: translateY(-50%);
		align-content: flex-start;
		margin: 0px!important;
		background-color: white;
		padding: 5px;
	}
	</style>
	';
	$social_icons = $facebook_icon . $x_icon . $whatsapp_icon . $telegram_icon . $LinkedIn_icon . $pinterest_icon . $email_icon.'</ul>';

    //top, bottom, left, right
	if ( $palce == 'left' ){
		$befor = '<ul class="left-post-layout">'.$social_icons;
	} else if ( $palce == 'right' ) {
		$befor = '<ul class="right-post-layout">'.$social_icons;
	} else if  ( $palce == 'top' ) {
        $befor = '<ul class="end-post-layout">'.$social_icons;
    } else if  ( $palce == 'bottom' ) {
    $end = '<ul class="end-post-layout">'.$social_icons; }
	return $befor.$content.$end.$style;
} else return $content;  });
