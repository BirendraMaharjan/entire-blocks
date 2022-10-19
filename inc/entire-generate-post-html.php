<?php
function entire_generate_post_html( $data ) {
	ob_start();

	$noOfPost    = intval( $data['noOfPost'] );
	$blogColumns = intval( $data['blogColumns'] );

	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args  = array(
		'post_type'      => 'post',
		'posts_per_page' => $noOfPost,
		'paged'          => $paged,
		'orderby'        => 'id',
		'order'          => 'ASC',
	);

	$the_query = new WP_Query( $args );
	?>
    <style>
        .entire-blocks-section-title {
            text-align: <?php echo esc_attr($data['alignment']); ?>
        }

        .entire-blocks-section .list-item-wrap .list-item {
            flex: 0 0 <?php echo esc_attr(100 / $blogColumns); ?>%;
        }

        .entire-blocks-section .list-item-desc a {
            color: <?php echo esc_attr($data['titleColorList']); ?>
        }

        .entire-blocks-section .list-item-desc p {
            color: <?php echo esc_attr($data['descriptionColorList']); ?>
        }

        .entire-blocks-section .btn-wrap a {
            color: <?php echo esc_attr($data['readMoreColor']); ?>
        }
    </style>
    <section class="entire-blocks-section">
		<?php if ( ! empty( $data['title'] ) || ! empty( $data['description'] ) ): ?>
            <div class="entire-blocks-section-title">
				<?php if ( ! empty( $data['title'] ) ): ?>
                    <h2><?php echo wp_kses_post( $data['title'] ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $data['description'] ) ): ?>
                    <div><?php echo wp_kses_post( $data['description'] ); ?></div>
				<?php endif; ?>
            </div>
		<?php endif; ?>
		<?php if ( $the_query->have_posts() ) : ?>
            <div class="list-item-wrap">
                <div class="list-items">
                    <!-- the loop -->
					<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                        <div class="list-item" id="post-<?php the_ID(); ?>">
                            <div class="entire-blocks-card entire-blocks-box-shadow">
								<?php if ( has_post_thumbnail() && $data['displayThumbnails'] == 'true' ): ?>
                                    <div class="list-item-img">
										<?php the_post_thumbnail( 'post-thumbnail-small' ); ?>
                                    </div><!-- .list-item-img -->
								<?php endif; ?>
                                <div class="list-item-desc">
                                    <h3 class="list-item-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    <p>
										<?php if ( has_excerpt() ): ?>
											<?php echo wp_kses_post( get_the_excerpt() ); ?>
										<?php else: ?>
											<?php echo wp_kses_post( wp_trim_words( get_the_content(), 20 ) ); ?>
										<?php endif; ?>
                                    </p>
                                    <div class="btn-wrap">
                                        <a href="<?php the_permalink(); ?>"><?php esc_html_e( $data['readMoreText'], 'entire-blocks' ); ?></a>
                                    </div>
                                </div><!-- .list-item-desc -->
                            </div><!-- .card -->
                        </div><!-- .list-item -->
					<?php endwhile; ?>
                    <!-- end of the loop -->
                </div><!-- .list-items -->
            </div><!-- .list-item-wrap -->

            <!-- pagination here -->
			<?php
			$total_pages = $the_query->max_num_pages;

			if ( $total_pages > 1 ) {
				?>
                <div class="pagination-wrap">
                    <style>
                        .page-numbers .page-numbers {
                            color: <?php echo esc_attr($data['paginationColor']); ?>;
                            background: <?php echo esc_attr($data['paginationBackgroundColor']); ?>;
                        }

                        .page-numbers .page-numbers.current {
                            color: <?php echo esc_attr($data['paginationBackgroundColor']); ?>;
                            border: 1px solid<?php echo esc_attr($data['paginationBackgroundColor']); ?>;
                            background: <?php echo esc_attr($data['paginationColor']); ?>;
                        }
                    </style>
					<?php
					$current_page = max( 1, get_query_var( 'paged' ) );

					$links = paginate_links( array(
						'base'      => get_pagenum_link( 1 ) . '%_%',
						'format'    => 'page/%#%',
						'current'   => $current_page,
						'total'     => $total_pages,
						'prev_text' => __( '« prev' ),
						'next_text' => __( 'next »' ),
						'type'      => 'list'
					) );
					echo wp_kses_post( $links );
					?>
                </div>
				<?php
			}
			?>
		<?php else : ?>
            <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
    </section><!-- .entire-blocks-section -->
	<?php return ob_get_clean();
}

function entire_generate_post_html1( $data ) {
	ob_start();

	echo "<pre>";
	$post_id = 62;
	$post    = get_post( $post_id );
	$blocks  = parse_blocks( $post->post_content );
	foreach ( $blocks as $block ) {
		if ( 'create-block/entire-blocks' === $block['blockName'] ) {
			$attributes = $block['attrs'];
			break;
		}
	}

	$file_or_folder = __DIR__ . './../build';
	$filename       = 'block.json';
	$metadata_file  = ( substr( $file_or_folder, - strlen( $filename ) ) !== $filename ) ?
		trailingslashit( $file_or_folder ) . $filename :
		$file_or_folder;
	if ( ! file_exists( $metadata_file ) ) {
		return false;
	}

	$metadata = wp_json_file_decode( $metadata_file, array( 'associative' => true ) );
	if ( ! is_array( $metadata ) || empty( $metadata['name'] ) ) {
		return false;
	}
	$metadata['file'] = wp_normalize_path( realpath( $metadata_file ) );

	print_r( $metadata['file'] );


	foreach ( $attributes as $attribute_name => $value ) {
		// If the attribute is not defined by the block type, it cannot be
		// validated.
		if ( ! isset( $metadata['attributes'][ $attribute_name ] ) ) {
			continue;
		}

		$schema = $metadata['attributes'][ $attribute_name ];

		// Validate value by JSON schema. An invalid value should revert to
		// its default, if one exists. This occurs by virtue of the missing
		// attributes loop immediately following. If there is not a default
		// assigned, the attribute value should remain unset.
		$is_valid = rest_validate_value_from_schema( $value, $schema, $attribute_name );
		if ( is_wp_error( $is_valid ) ) {
			unset( $attributes[ $attribute_name ] );
		}
	}

	// Populate values of any missing attributes for which the block type
	// defines a default.
	$missing_schema_attributes = array_diff_key( $metadata['attributes'], $attributes );
	foreach ( $missing_schema_attributes as $attribute_name => $schema ) {
		if ( isset( $schema['default'] ) ) {
			$attributes[ $attribute_name ] = $schema['default'];
		}
	}

	print_r( $attributes );

	echo "</pre>";

	return ob_get_clean();


	exit;
	$noOfPost    = intval( $data['noOfPost'] );
	$blogColumns = intval( $data['blogColumns'] );

	$paged     = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args      = array(
		'post_type'      => 'post',
		'posts_per_page' => $noOfPost,
		'paged'          => $paged,
		'orderby'        => 'id',
		'order'          => 'ASC',
	);
	$the_query = new WP_Query( $args );
	?>

	<?php if ( $the_query->have_posts() ) : ?>
		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
            <div class="list-item" id="post-<?php the_ID(); ?>">
				<?php if ( has_post_thumbnail() && $data['displayThumbnails'] == 'true' ): ?>
                    <div class="list-item-img">
						<?php the_post_thumbnail( 'post-thumbnail-small' ); ?>
                    </div>
                    <!-- .list-item-img -->
				<?php endif; ?>
                <div class="list-item-desc">
                    <!-- .list-item-tag -->
                    <h3 class="list-item-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                    <p>
						<?php if ( has_excerpt() ): ?>
							<?php echo wp_kses_post( get_the_excerpt() ); ?>
						<?php else: ?>
							<?php echo wp_trim_words( wp_kses_post( get_the_content() ), 20 ); ?>
						<?php endif; ?>
                    </p>
                    <div class="btn-wrap">
                        <a href="<?php the_permalink(); ?>"><?php echo esc_attr( $data['readMoreText'] ); ?></a>
                    </div>
                </div>
                <!-- .list-item-desc -->
            </div>
            <!-- .list-item -->
		<?php endwhile; ?>
        <!-- end of the loop -->
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>

	<?php return ob_get_clean();
}