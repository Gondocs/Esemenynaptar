<?php
/*
Plugin Name: WP Event Plugin
Description: Események kezelésére és megjelenítésére szolgáló plugin.
Version: 1.0
Author: GitHub Copilot
*/

// Register Custom Post Type for Events
function wp_event_plugin_register_post_type() {
    $labels = array(
        'name'                  => _x( 'Események', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Esemény', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Események', 'text_domain' ),
        'name_admin_bar'        => __( 'Esemény', 'text_domain' ),
        'archives'              => __( 'Esemény Archívum', 'text_domain' ),
        'attributes'            => __( 'Esemény Attribútumok', 'text_domain' ),
        'parent_item_colon'     => __( 'Szülő Esemény:', 'text_domain' ),
        'all_items'             => __( 'Összes Esemény', 'text_domain' ),
        'add_new_item'          => __( 'Új Esemény Hozzáadása', 'text_domain' ),
        'add_new'               => __( 'Új Hozzáadása', 'text_domain' ),
        'new_item'              => __( 'Új Esemény', 'text_domain' ),
        'edit_item'             => __( 'Esemény Szerkesztése', 'text_domain' ),
        'update_item'           => __( 'Esemény Frissítése', 'text_domain' ),
        'view_item'             => __( 'Esemény Megtekintése', 'text_domain' ),
        'view_items'            => __( 'Események Megtekintése', 'text_domain' ),
        'search_items'          => __( 'Esemény Keresése', 'text_domain' ),
        'not_found'             => __( 'Nem található', 'text_domain' ),
        'not_found_in_trash'    => __( 'Nem található a kukában', 'text_domain' ),
        'featured_image'        => __( 'Kiemelt Kép', 'text_domain' ),
        'set_featured_image'    => __( 'Kiemelt Kép Beállítása', 'text_domain' ),
        'remove_featured_image' => __( 'Kiemelt Kép Eltávolítása', 'text_domain' ),
        'use_featured_image'    => __( 'Használja Kiemelt Képként', 'text_domain' ),
        'insert_into_item'      => __( 'Beszúrás az Eseménybe', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Feltöltve ehhez az Eseményhez', 'text_domain' ),
        'items_list'            => __( 'Események Listája', 'text_domain' ),
        'items_list_navigation' => __( 'Események Lista Navigáció', 'text_domain' ),
        'filter_items_list'     => __( 'Események Lista Szűrése', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Esemény', 'text_domain' ),
        'description'           => __( 'Események kezelése és megjelenítése', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'taxonomies'            => array( 'category', 'post_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => array( 'slug' => 'esemenyek' ),
    );
    register_post_type( 'event', $args );
}
add_action( 'init', 'wp_event_plugin_register_post_type', 0 );

// Flush rewrite rules on activation
function wp_event_plugin_activate() {
    wp_event_plugin_register_post_type();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'wp_event_plugin_activate' );

// Flush rewrite rules on deactivation
function wp_event_plugin_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'wp_event_plugin_deactivate' );

// Add Custom Meta Boxes for Event Details
function wp_event_plugin_add_meta_boxes() {
    add_meta_box(
        'event_details',
        __( 'Esemény Részletei', 'text_domain' ),
        'wp_event_plugin_render_meta_box',
        'event',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'wp_event_plugin_add_meta_boxes' );

function wp_event_plugin_render_meta_box( $post ) {
    wp_nonce_field( 'wp_event_plugin_save_meta_box_data', 'wp_event_plugin_meta_box_nonce' );

    $location = get_post_meta( $post->ID, '_event_location', true );
    $date = get_post_meta( $post->ID, '_event_date', true );
    $time = get_post_meta( $post->ID, '_event_time', true );
    $capacity_min = get_post_meta( $post->ID, '_event_capacity_min', true );
    $capacity_max = get_post_meta( $post->ID, '_event_capacity_max', true );
    $recommended_for = get_post_meta( $post->ID, '_event_recommended_for', true );
    $duration = get_post_meta( $post->ID, '_event_duration', true );
    $image_link = get_post_meta( $post->ID, '_event_image_link', true );
    $short_description = get_post_meta( $post->ID, '_event_short_description', true );

    echo '<label for="event_location">' . __( 'Lokáció', 'text_domain' ) . '</label>';
    echo '<input type="text" id="event_location" name="event_location" value="' . esc_attr( $location ) . '" size="25" /><br>';

    echo '<label for="event_date">' . __( 'Dátum', 'text_domain' ) . '</label>';
    echo '<input type="date" id="event_date" name="event_date" value="' . esc_attr( $date ) . '" size="25" /><br>';

    echo '<label for="event_time">' . __( 'Időpont', 'text_domain' ) . '</label>';
    echo '<input type="time" id="event_time" name="event_time" value="' . esc_attr( $time ) . '" size="25" /><br>';

    echo '<label for="event_capacity_min">' . __( 'Minimum Hány fő', 'text_domain' ) . '</label>';
    echo '<input type="number" id="event_capacity_min" name="event_capacity_min" value="' . esc_attr( $capacity_min ) . '" size="25" required /><br>';

    echo '<label for="event_capacity_max">' . __( 'Maximum Hány fő', 'text_domain' ) . '</label>';
    echo '<input type="number" id="event_capacity_max" name="event_capacity_max" value="' . esc_attr( $capacity_max ) . '" size="25" required /><br>';

    echo '<label for="event_recommended_for">' . __( 'Kinek ajánlott', 'text_domain' ) . '</label>';
    echo '<select id="event_recommended_for" name="event_recommended_for">';
    $options = array('Bárkinek', 'Férfiaknak', 'Nőknek', 'Gyerekeknek', 'Családoknak', 'Csoportoknak', 'Baráti társaságoknak');
    foreach ($options as $option) {
        $selected = ($recommended_for == $option) ? 'selected' : '';
        echo '<option value="' . esc_attr($option) . '" ' . $selected . '>' . esc_html($option) . '</option>';
    }
    echo '</select><br>';

    echo '<label for="event_duration">' . __( 'Időtartam', 'text_domain' ) . '</label>';
    echo '<input type="text" id="event_duration" name="event_duration" value="' . esc_attr( $duration ) . '" size="25" /><br>';

    echo '<label for="event_image_link">' . __( 'Kép Link', 'text_domain' ) . '</label>';
    echo '<input type="text" id="event_image_link" name="event_image_link" value="' . esc_attr( $image_link ) . '" size="25" /><br>';

    echo '<label for="event_short_description">' . __( 'Rövid Leírás', 'text_domain' ) . '</label>';
    echo '<textarea id="event_short_description" name="event_short_description" rows="4" cols="50">' . esc_textarea( $short_description ) . '</textarea><br>';
}

function wp_event_plugin_save_meta_box_data( $post_id ) {
    if ( ! isset( $_POST['wp_event_plugin_meta_box_nonce'] ) ) {
        return;
    }

    if ( ! wp_verify_nonce( $_POST['wp_event_plugin_meta_box_nonce'], 'wp_event_plugin_save_meta_box_data' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( ! isset( $_POST['event_location'] ) || ! isset( $_POST['event_date'] ) || ! isset( $_POST['event_time'] ) || ! isset( $_POST['event_capacity_min'] ) || ! isset( $_POST['event_capacity_max'] ) || ! isset( $_POST['event_recommended_for'] ) || ! isset( $_POST['event_duration'] ) || ! isset( $_POST['event_image_link'] ) || ! isset( $_POST['event_short_description'] ) ) {
        return;
    }

    $location = sanitize_text_field( $_POST['event_location'] );
    $date = sanitize_text_field( $_POST['event_date'] );
    $time = sanitize_text_field( $_POST['event_time'] );
    $capacity_min = sanitize_text_field( $_POST['event_capacity_min'] );
    $capacity_max = sanitize_text_field( $_POST['event_capacity_max'] );
    $recommended_for = sanitize_text_field( $_POST['event_recommended_for'] );
    $duration = sanitize_text_field( $_POST['event_duration'] );
    $image_link = esc_url_raw( $_POST['event_image_link'] );
    $short_description = sanitize_textarea_field( $_POST['event_short_description'] );

    update_post_meta( $post_id, '_event_location', $location );
    update_post_meta( $post_id, '_event_date', $date );
    update_post_meta( $post_id, '_event_time', $time );
    update_post_meta( $post_id, '_event_capacity_min', $capacity_min );
    update_post_meta( $post_id, '_event_capacity_max', $capacity_max );
    update_post_meta( $post_id, '_event_recommended_for', $recommended_for );
    update_post_meta( $post_id, '_event_duration', $duration );
    update_post_meta( $post_id, '_event_image_link', $image_link );
    update_post_meta( $post_id, '_event_short_description', $short_description );
}
add_action( 'save_post', 'wp_event_plugin_save_meta_box_data' );

// Shortcode to Display Events
function wp_event_plugin_display_events( $atts ) {
    $atts = shortcode_atts( array(
        'view' => 'list',
    ), $atts, 'display_events' );

    // Filter form
    $output = '<form method="GET" class="event-filter-form">';
    $output .= '<label for="event_recommended_for">Kinek szól az élmény?</label>';
    $output .= '<select name="event_recommended_for">';
    $options = array('Bárkinek', 'Férfiaknak', 'Nőknek', 'Gyerekeknek', 'Családoknak', 'Csoportoknak', 'Baráti társaságoknak');
    foreach ($options as $option) {
        $selected = (isset($_GET['event_recommended_for']) && $_GET['event_recommended_for'] == $option) ? 'selected' : '';
        $output .= '<option value="' . esc_attr($option) . '" ' . $selected . '>' . esc_html($option) . '</option>';
    }
    $output .= '</select>';

    $output .= '<label for="event_capacity">Hány főnek?</label>';
    $output .= '<input type="number" name="event_capacity" value="' . (isset($_GET['event_capacity']) ? esc_attr($_GET['event_capacity']) : '') . '" />';

    $output .= '<label for="event_month">Melyik hónapban?</label>';
    $output .= '<select name="event_month">';
    $months = array('01' => 'Január', '02' => 'Február', '03' => 'Március', '04' => 'Április', '05' => 'Május', '06' => 'Június', '07' => 'Július', '08' => 'Augusztus', '09' => 'Szeptember', '10' => 'Október', '11' => 'November', '12' => 'December');
    $output .= '<option value="">Bármelyik</option>';
    foreach ($months as $key => $value) {
        $selected = (isset($_GET['event_month']) && $_GET['event_month'] == $key) ? 'selected' : '';
        $output .= '<option value="' . esc_attr($key) . '" ' . $selected . '>' . esc_html($value) . '</option>';
    }
    $output .= '</select>';
    $output .= '<button type="submit">Keresés</button>';
    $output .= '</form>';

    $meta_query = array();
    if ( isset( $_GET['event_recommended_for'] ) && ! empty( $_GET['event_recommended_for'] ) && $_GET['event_recommended_for'] != 'Bárkinek' ) {
        $meta_query[] = array(
            'key'     => '_event_recommended_for',
            'value'   => sanitize_text_field( $_GET['event_recommended_for'] ),
            'compare' => 'LIKE',
        );
    }
    if ( isset( $_GET['event_capacity'] ) && ! empty( $_GET['event_capacity'] ) ) {
        $event_capacity = intval( $_GET['event_capacity'] );
        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key'     => '_event_capacity_min',
                'value'   => $event_capacity,
                'type'    => 'numeric',
                'compare' => '<=',
            ),
            array(
                'key'     => '_event_capacity_max',
                'value'   => $event_capacity,
                'type'    => 'numeric',
                'compare' => '>=',
            ),
        );
    }
    if ( isset( $_GET['event_month'] ) && ! empty( $_GET['event_month'] ) ) {
        $meta_query[] = array(
            'key'     => '_event_date',
            'value'   => '-' . sanitize_text_field( $_GET['event_month'] ) . '-',
            'compare' => 'LIKE',
        );
    }

    $query = new WP_Query( array(
        'post_type' => 'event',
        'posts_per_page' => -1,
        'meta_query' => $meta_query,
    ) );

    if ( $query->have_posts() ) {
        $output .= '<div class="events-container">';
        while ( $query->have_posts() ) {
            $query->the_post();
            $location = get_post_meta( get_the_ID(), '_event_location', true );
            $duration = get_post_meta( get_the_ID(), '_event_duration', true );
            $capacity_min = get_post_meta( get_the_ID(), '_event_capacity_min', true );
            $capacity_max = get_post_meta( get_the_ID(), '_event_capacity_max', true );
            $recommended_for = get_post_meta( get_the_ID(), '_event_recommended_for', true );
            $image_link = get_post_meta( get_the_ID(), '_event_image_link', true );
            $short_description = get_post_meta( get_the_ID(), '_event_short_description', true );

            $output .= '<div class="event-card">';
            if ( $image_link ) {
                $output .= '<a href="' . get_permalink() . '"><img src="' . esc_url( $image_link ) . '" alt="' . get_the_title() . '" class="event-image" /></a>';
            }
            $output .= '<div class="event-details">';
            $output .= '<h2><a href="' . get_permalink() . '">' . get_the_title() . '</a></h2>';
            $output .= '<p class="event-short-description">' . esc_html( $short_description ) . '</p>';
            $output .= '<p><i class="icon icon-location"></i> ' . esc_html( $location ) . '</p>';
            $output .= '<p><i class="icon icon-duration"></i> ' . esc_html( $duration ) . '</p>';
            $output .= '<p><i class="icon icon-capacity"></i> ' . esc_html( $capacity_min ) . ' - ' . esc_html( $capacity_max ) . '</p>';
            $output .= '<p><i class="icon icon-recommended"></i> ' . esc_html( $recommended_for ) . '</p>';
            $output .= '<a href="' . get_permalink() . '" class="event-details-button">Részletek</a>';
            $output .= '</div>';
            $output .= '</div>';
        }
        $output .= '</div>';
        wp_reset_postdata();
    } else {
        $output .= '<p>Nincsenek események.</p>';
    }

    return $output;
}
add_shortcode( 'display_events', 'wp_event_plugin_display_events' );

// Enqueue Styles
function wp_event_plugin_enqueue_styles() {
    wp_enqueue_style( 'wp-event-plugin-styles', plugins_url( '/css/styles.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'wp_event_plugin_enqueue_styles' );

// Add CSS for card view and filter form
function wp_event_plugin_additional_styles() {
    ?>
    <style>
        .event-filter-form {
            margin-bottom: 20px;
        }
        .event-filter-form label {
            display: block;
            margin-bottom: 5px;
        }
        .event-filter-form input,
        .event-filter-form select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .event-filter-form button {
            padding: 10px 15px;
            background: #0073aa;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .event-filter-form button:hover {
            background: #005177;
        }
        .events-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .event-card {
            display: flex;
            align-items: stretch;
            border-radius: 5px;
            overflow: hidden;
            background-color: #ffffff;
            flex-direction: column;
            align-items: flex-start;
            width: calc(33.333% - 20px);
            box-sizing: border-box;
            border: 1px solid #ddd;
        }
        .event-card h2 {
            margin-top: 0;
            color: #0073aa;
        }
        .event-card p {
            margin: 5px 0;
        }
        .event-image {
            width: 100%;
            height: 240px;
            overflow: hidden;
        }
        .event-details {
            width: 100%;
            padding-top: 1.5em;
            padding-right: 15px;
            padding-left: 15px;
            background-color: #ffffff;
            position: relative;
        }
        .event-details-button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background: #0073aa;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .event-details-button:hover {
            background: #005177;
        }
        .event-card a {
            color: #0073aa;
            text-decoration: none;
        }
        .event-card a:hover {
            text-decoration: underline;
        }
        .event-short-description {
            font-size: 14px;
            color: #666;
        }
        .event-details i {
            margin-right: 5px;
        }
        .event-details .icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            background-size: contain;
            background-repeat: no-repeat;
            margin-right: 5px;
        }
        .icon-location {
            background-image: url('path/to/location-icon.png');
        }
        .icon-duration {
            background-image: url('path/to/clock-icon.png');
        }
        .icon-capacity {
            background-image: url('path/to/users-icon.png');
        }
        .icon-recommended {
            background-image: url('path/to/user-icon.png');
        }
    </style>
    <?php
}
add_action( 'wp_head', 'wp_event_plugin_additional_styles' );