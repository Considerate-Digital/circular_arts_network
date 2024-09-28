<?php
$primaryColor = can_get_option('can_primary_color', '#3AAF4A');
$secondaryColor = can_get_option('can_secondary_color', '#fffff');

if($primaryColor != '' && $secondaryColor != ''){
    echo "
        .can-bs-wrapper .can-single-cat:before,
        .can-bs-wrapper .can-menu-box .active,
        .can-section .can-features li:before,
        .can-pagination li a:hover, .can-pagination li .current,
        .can-bs-wrapper .can-btn {
            background-color: $primaryColor !important;
        }

        .can-section .can-features li:before {
            box-shadow: 0px 0px 0px 3px {$primaryColor}66 !important;
        }

        .can-btn {
            box-shadow: 0px 10px 30px 0px {$primaryColor}66 !important;
        }

        .can-input-wrapper .can-text-input:focus,
        .can-menu-box .active,
        .can-pagination li a:hover, .can-pagination li .current {
            border-color: $primaryColor !important;
        }

        .can-bs-wrapper .can-grid-box-wrap .can-box-inner .cats i,
        .can-bs-wrapper .can-list-box-wrap .can-box-inner .cats i,
        .can-bs-wrapper .can-price-wrap .can-price-amount {
            color: $primaryColor;
        }

        .can-bs-wrapper .can-btn:hover {
		color: $primaryColor !important; 
            background-color: $secondaryColor !important;
        }
        
        .can-single-cat:hover {
            border-color: $primaryColor;
        }

	.can-box-inner-stories {
		border-color: $primaryColor !important;
	}
	
	.can-content-wrap-stories  {
		background-color: $primaryColor;
		color: $secondaryColor;
	} 
	.can-content-wrap-stories *  {
		background-color: $primaryColor !important;
		color: $secondaryColor !important;
	}
	.can-content-wrap-stories {
		min-height: 12.3rem;
	}
	.can-btn-wrap-stories {
		background-color: $primaryColor !important;
	}
	.can-btn-wrap-stories .can-btn {
		color: $primaryColor !important;
		background-color: $secondaryColor !important;
	    box-shadow: 0px 10px 30px 0px {$secondaryColor}66 !important;
	}
    ";
}
?>
