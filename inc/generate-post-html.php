<?php
function generate_post_html($data)
{
    ob_start();

    $noOfPost = intval($data['noOfPost']);
    $blogColumns = intval($data['blogColumns']);

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $noOfPost,
        'paged' => $paged,
        'orderby' => 'id',
        'order' => 'ASC',
    );
    $the_query = new WP_Query($args);
    ?>
    <style>
        .agutenpride-section-title{
            text-align: <?php echo esc_attr($data['alignment']); ?>
        }
        .agutenpride-section .list-item-wrap  .list-item{
            flex: 0 0 calc(<?php echo esc_attr(100 / $blogColumns); ?>% - 15px);
            flex-basis: calc(<?php echo esc_attr(100 / $blogColumns); ?>% - 15px);
        }
        .agutenpride-section .list-item-desc a{
            color: <?php echo esc_attr($data['titleColorList']); ?>
        }

        .agutenpride-section .list-item-desc p{
            color: <?php echo esc_attr($data['descriptionColorList']); ?>
        }
        .agutenpride-section .btn-wrap a{
            color: <?php echo esc_attr($data['readMoreColor']); ?>
        }
    </style>
    <section class="agutenpride-section">
        <?php if (!empty($data['title']) || !empty($data['description'])): ?>
            <div class="agutenpride-section-title">
                <?php if (!empty($data['title'])): ?>
                    <h2><?php echo wp_kses_post($data['title']); ?></h2>
                <?php endif; ?>
                <?php if (!empty($data['description'])): ?>
                    <div><?php echo wp_kses_post($data['description']); ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if ($the_query->have_posts()) : ?>
            <div class="list-item-wrap">
                <div class="list-items">
                    <!-- the loop -->
                    <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                        <div class="list-item" id="post-<?php the_ID(); ?>">
                            <?php if (has_post_thumbnail() && $data['displayThumbnails'] == 'true'): ?>
                                <div class="list-item-img">
                                    <?php the_post_thumbnail('post-thumbnail-small'); ?>
                                </div>
                                <!-- .list-item-img -->
                            <?php endif; ?>
                            <div class="list-item-desc">
                                <!-- .list-item-tag -->
                                <h3 class="list-item-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                <p>
                                    <?php if (has_excerpt()): ?>
                                        <?php echo wp_kses_post(get_the_excerpt()); ?>
                                    <?php else: ?>
                                        <?php echo wp_trim_words(wp_kses_post(get_the_content()), 20); ?>
                                    <?php endif; ?>
                                </p>
                                <div class="btn-wrap">
                                    <a href="<?php the_permalink(); ?>"><?php echo esc_attr($data['readMoreText']); ?></a>
                                </div>
                            </div>
                            <!-- .list-item-desc -->
                        </div>
                        <!-- .list-item -->
                    <?php endwhile; ?>
                    <!-- end of the loop -->
                </div><!-- .list-items -->
            </div><!-- .list-item-wrap -->

            <!-- pagination here -->
            <?php
            $total_pages = $the_query->max_num_pages;

            if ($total_pages > 1) {
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
                        $current_page = max(1, get_query_var('paged'));

                        $links = paginate_links(array(
                            'base' => get_pagenum_link(1) . '%_%',
                            'format' => 'page/%#%',
                            'current' => $current_page,
                            'total' => $total_pages,
                            'prev_text' => __('« prev'),
                            'next_text' => __('next »'),
                            'type' => 'list'
                        ));
                        echo wp_kses_post($links);
                    ?>
                </div>
                <?php
            }
            ?>
        <?php else : ?>
            <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
        <?php endif; ?>
        <?php wp_reset_postdata(); ?>
    </section><!-- .agutenpride-section -->
    <?php return ob_get_clean();
}

