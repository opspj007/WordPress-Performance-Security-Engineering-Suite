<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPBREAD {

	public static function init() {
		add_shortcode( 'wp_breadcrumbs', array( __CLASS__, 'shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'assets' ) );
		add_action( 'wp_head', array( __CLASS__, 'schema' ), 20 );

		if ( (bool) get_option( 'wpbread_auto_insert', true ) ) {
			add_filter( 'the_content', array( __CLASS__, 'auto_insert' ), 20 );
		}
	}

	public static function activate() {
		add_option( 'wpbread_separator', '›' );
		add_option( 'wpbread_home_label', 'Home' );
		add_option( 'wpbread_auto_insert', 1 );
		add_option( 'wpbread_show_current', 1 );
		add_option( 'wpbread_show_home', 1 );
		add_option( 'wpbread_enable_schema', 1 );
	}

	public static function assets() {
		wp_register_style(
			'wp-breadcrumbs',
			WPBREAD_URL . 'assets/css/breadcrumbs.css',
			array(),
			WPBREAD_VERSION
		);
		wp_enqueue_style( 'wp-breadcrumbs' );
	}

	public static function shortcode( $atts = array() ) {
		$atts = shortcode_atts(
			array(
				'class' => '',
			),
			$atts,
			'wp_breadcrumbs'
		);

		return self::render( sanitize_html_class( $atts['class'] ) );
	}

	public static function auto_insert( $content ) {
		if ( is_admin() || ! is_main_query() || ! in_the_loop() ) {
			return $content;
		}

		if ( is_front_page() || is_home() || is_feed() || is_search() || is_404() ) {
			return $content;
		}

		if ( has_shortcode( $content, 'wp_breadcrumbs' ) ) {
			return $content;
		}

		return self::render() . $content;
	}

	public static function get_items() {
		$items = array(
			array(
				'label' => get_option( 'wpbread_home_label', 'Home' ),
				'url'   => home_url( '/' ),
			),
		);

		if ( is_front_page() || is_home() ) {
			return $items;
		}

		if ( is_search() ) {
			$items[] = array(
				'label' => sprintf(
					/* translators: %s: search query */
					__( 'Search: %s', 'wp-breadcrumbs' ),
					get_search_query()
				),
				'url' => '',
			);
			return $items;
		}

		if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
			$shop_id = wc_get_page_id( 'shop' );
			if ( $shop_id > 0 && ! is_shop() ) {
				$items[] = array(
					'label' => get_the_title( $shop_id ),
					'url'   => get_permalink( $shop_id ),
				);
			}
		}

		if ( is_category() || is_tag() || is_tax() ) {
			$term = get_queried_object();
			$ancestors = array_reverse( get_ancestors( $term->term_id, $term->taxonomy ) );
			foreach ( $ancestors as $ancestor_id ) {
				$ancestor = get_term( $ancestor_id, $term->taxonomy );
				if ( ! is_wp_error( $ancestor ) ) {
					$items[] = array(
						'label' => $ancestor->name,
						'url'   => get_term_link( $ancestor ),
					);
				}
			}
			$items[] = array(
				'label' => $term->name,
				'url'   => '',
			);
			return $items;
		}

		if ( is_singular() ) {
			$post = get_queried_object();

			if ( 'product' === $post->post_type && function_exists( 'wc_get_product' ) ) {
				$terms = get_the_terms( $post->ID, 'product_cat' );
				if ( $terms && ! is_wp_error( $terms ) ) {
					$term = self::deepest_term( $terms );
					self::append_term_hierarchy( $items, $term );
				}
			} else {
				$taxonomies = get_object_taxonomies( $post->post_type, 'objects' );
				foreach ( $taxonomies as $taxonomy ) {
					if ( empty( $taxonomy->hierarchical ) || 'post_format' === $taxonomy->name ) {
						continue;
					}
					$terms = get_the_terms( $post->ID, $taxonomy->name );
					if ( $terms && ! is_wp_error( $terms ) ) {
						$term = self::deepest_term( $terms );
						self::append_term_hierarchy( $items, $term );
						break;
					}
				}
			}

			if ( 'page' === $post->post_type && $post->post_parent ) {
				$parents = array_reverse( get_post_ancestors( $post->ID ) );
				foreach ( $parents as $parent_id ) {
					$items[] = array(
						'label' => get_the_title( $parent_id ),
						'url'   => get_permalink( $parent_id ),
					);
				}
			}

			$items[] = array(
				'label' => get_the_title( $post ),
				'url'   => '',
			);
			return $items;
		}

		return $items;
	}

	private static function deepest_term( $terms ) {
		$best = reset( $terms );
		foreach ( $terms as $term ) {
			if ( count( get_ancestors( $term->term_id, $term->taxonomy ) ) >
				count( get_ancestors( $best->term_id, $best->taxonomy ) ) ) {
				$best = $term;
			}
		}
		return $best;
	}

	private static function append_term_hierarchy( &$items, $term ) {
		$ancestors = array_reverse( get_ancestors( $term->term_id, $term->taxonomy ) );
		foreach ( $ancestors as $ancestor_id ) {
			$ancestor = get_term( $ancestor_id, $term->taxonomy );
			if ( ! is_wp_error( $ancestor ) ) {
				$items[] = array(
					'label' => $ancestor->name,
					'url'   => get_term_link( $ancestor ),
				);
			}
		}
		$items[] = array(
			'label' => $term->name,
			'url'   => get_term_link( $term ),
		);
	}

	public static function render( $extra_class = '' ) {
		$items = self::get_items();
		$show_home = (bool) get_option( 'wpbread_show_home', true );
		$show_current = (bool) get_option( 'wpbread_show_current', true );
		$separator = get_option( 'wpbread_separator', '›' );

		if ( ! $show_home && ! empty( $items ) ) {
			array_shift( $items );
		}
		if ( ! $show_current && count( $items ) > 1 ) {
			array_pop( $items );
		}

		if ( empty( $items ) ) {
			return '';
		}

		ob_start();
		?>
		<nav class="wpbread <?php echo esc_attr( $extra_class ); ?>" aria-label="<?php esc_attr_e( 'Breadcrumbs', 'wp-breadcrumbs' ); ?>">
			<ol class="wpbread__list">
				<?php foreach ( $items as $index => $item ) : ?>
					<li class="wpbread__item">
						<?php if ( ! empty( $item['url'] ) && $index < count( $items ) - 1 ) : ?>
							<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
						<?php else : ?>
							<span<?php echo ( $index === count( $items ) - 1 ) ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $item['label'] ); ?></span>
						<?php endif; ?>
						<?php if ( $index < count( $items ) - 1 ) : ?>
							<span class="wpbread__separator" aria-hidden="true"><?php echo esc_html( $separator ); ?></span>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ol>
		</nav>
		<?php
		return ob_get_clean();
	}

	public static function schema() {
		if ( ! get_option( 'wpbread_enable_schema', true ) || is_front_page() || is_home() ) {
			return;
		}

		$items = self::get_items();
		if ( count( $items ) < 2 ) {
			return;
		}

		$list = array();
		foreach ( $items as $position => $item ) {
			$list[] = array(
				'@type'    => 'ListItem',
				'position' => $position + 1,
				'name'     => wp_strip_all_tags( $item['label'] ),
				'item'     => ! empty( $item['url'] ) ? esc_url( $item['url'] ) : get_permalink(),
			);
		}

		$data = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $list,
		);

		echo '<script type="application/ld+json">' .
			wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) .
			'</script>' . "\n";
	}
}
