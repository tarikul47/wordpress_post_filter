<?php

/**
 * Adds Industry_Widget widget.
 */
class Industry_Widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_custom_scripts']);
        add_action('wp_ajax_custom_filter', [$this, 'custom_filter_posts']);
        add_action('wp_ajax_nopriv_custom_filter', [$this, 'custom_filter_posts']);

        parent::__construct(
            'industry_Widget',
            esc_html__('Industry Widget', 'tarikul'),
            array('description' => esc_html__('A Custom Filter Widget', 'tarikul'))
        );
    }

    public function custom_filter_posts()
    {
        //   check_ajax_referer('custom-filter-nonce', 'nonce');

        /**
         * amar jana mothe "pre_get_posts" action hook die amra post loop ta modify korte pari
         * but ekane ami kivabe "pre_get_posts" action hook kivabe run korte pari
         */

        print_r($_POST);
        print_r('Raju');
        die();

        if (isset($_POST['filter_category'])) {
            $category_ids = $_POST['filter_category'];
            $args = array(
                'post_type' => 'post',
                'category__in' => $category_ids,
            );

            $query = new WP_Query($args);



            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    // Display the filtered posts.
                }
            } else {
                echo 'No posts found.';
            }

            wp_reset_postdata();
        }
        die();
    }

    public  function enqueue_custom_scripts()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_script('custom-filter', get_template_directory_uri() . '/js/custom-filter.js', array('jquery'), '1.0', true);

        wp_localize_script('custom-filter', 'custom_filter_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('custom-filter-nonce')
        ));
    }

    /**
     * Front-end display of the widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from the database.
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        // Display post categories with checkboxes
        $categories = get_categories();
        if (!empty($categories)) {
            echo '<form id="category-filter-form" action="' . esc_url(home_url()) . '" method="post">';
            foreach ($categories as $category) {
                echo '<label>';
                echo '<input type="checkbox" name="filter_category[]" value="' . $category->cat_ID . '"> ' . esc_html($category->name);
                echo '</label><br>';
            }
            echo '<input type="hidden" name="action" value="custom_filter">';
            echo '<input type="submit" value="Filter">';
            echo '</form>';
        }

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from the database.
     */
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('New title', 'tarikul');
?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'tarikul'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
<?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from the database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';

        return $instance;
    }
} // class Industry_Widget

// Register Industry_Widget widget
function register_industry_Widget()
{
    register_widget('Industry_Widget');
}
add_action('widgets_init', 'register_industry_Widget');


//add_action('pre_get_posts', 'filter_posts_by_category');
