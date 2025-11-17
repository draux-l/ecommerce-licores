<?php

$searchlabel = apply_filters( 'strute_search_label', __( 'Search', 'strute' ) );
$searchplaceholder = apply_filters( 'strute_search_placeholder', __( 'Type Search Term &hellip;', 'strute' ) );
$searchsubmit = apply_filters( 'strute_search_submit', __( 'Search', 'strute' ) );
$searchquery = get_search_query();

echo '<div class="searchbody">';

	echo '<form method="get" class="searchform" action="' . esc_url( home_url( '/' ) ) . '" >';

		echo '<label class="screen-reader-text">' . esc_html( $searchlabel ) . '</label>';
		echo '<input type="text" class="searchtext" name="s" placeholder="' . esc_attr( $searchplaceholder ) . '" value="' . esc_attr( $searchquery ) . '" />';
		echo '<input type="submit" class="submit" name="submit" value="' . esc_attr( $searchsubmit ) . '" />';

	echo '</form>';
	echo '<div class="searchicon"><i class="fas fa-search"></i></div>';

echo '</div><!-- /searchbody -->';