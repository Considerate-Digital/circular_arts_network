<?php
$primaryColor = uclwp_get_option('ucl_primary_color', '#f85c70');
$secondaryColor = uclwp_get_option('ucl_secondary_color', '#0d1927');

if($primaryColor != ''){
    echo "
        .uclwp-bs-wrapper .ucl-single-cat:before,
        .uclwp-bs-wrapper .uclwp-menu-box .active,
        .uclwp-section .ucl-features li:before,
        .ucl-pagination li a:hover, .ucl-pagination li .current,
        .uclwp-bs-wrapper .ucl-btn {
            background-color: $primaryColor !important;
        }

        .uclwp-section .ucl-features li:before {
            box-shadow: 0px 0px 0px 3px {$primaryColor}66 !important;
        }

        .ucl-btn {
            box-shadow: 0px 10px 30px 0px {$primaryColor}66 !important;
        }

        .ucl-input-wrapper .ucl-text-input:focus,
        .uclwp-menu-box .active,
        .ucl-pagination li a:hover, .ucl-pagination li .current {
            border-color: $primaryColor !important;
        }

        .uclwp-bs-wrapper .uclwp-grid-box-wrap .uclwp-box-inner .cats i,
        .uclwp-bs-wrapper .uclwp-list-box-wrap .uclwp-box-inner .cats i,
        .uclwp-bs-wrapper .uclwp-price-wrap .uclwp-price-amount {
            color: $primaryColor;
        }

        .uclwp-bs-wrapper .ucl-btn:hover {
            background-color: $secondaryColor !important;
        }
    ";
}
?>