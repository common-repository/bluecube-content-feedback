<?php

/**
 * Class ContentFeedbackFormWidget
 */
class ContentFeedbackFormWidget extends WP_Widget {

  function __construct() {
    parent::__construct('ContentFeedbackFormWidget', 'BlueCube Content Feedback Form', array('classname' => 'bc-content-feedback-form-widget', 'description' => 'Shows the content feedback form.'));
  }

  public function assets() {
    $js_uri = plugins_url( 'asset/main.js', dirname(__FILE__) );
    wp_enqueue_script('ContentFeedbackFormWidget', $js_uri, ['jquery'], BlueCube\ContentFeedback::VER, true);
    $css_uri = plugins_url( 'asset/main.css', dirname(__FILE__) );
    wp_enqueue_style('ContentFeedbackFormWidget', $css_uri, [], BlueCube\ContentFeedback::VER);
  }

  public function widget($args, $instance) {

    global $post;

    if (!$post)
      return;

    if (apply_filters('bc_show_content_feedback_widget', true) == false) {
      return;
    }

    $security = wp_create_nonce('bc-content-feedback-form');

    $btn_class = isset($instance['btn_class']) ? $instance['btn_class'] : '';
    $title = isset($instance['title']) ? $instance['title'] : '';
    $content = isset($instance['content']) ? $instance['content'] : '';
    $message = isset($instance['message']) ? $instance['message'] : '';

    echo $args['before_widget'];
    $title = apply_filters('widget_title', $title);
    if (!empty($title)) echo $args['before_title'] . $title . $args['after_title'];
    echo '<form>';
    if (!empty($content)) echo wpautop($content);
    echo '<input type="hidden" name="security" id="security" value="'.$security.'">';
    echo '<input type="hidden" name="post_id" id="post-id" value="'.$post->ID.'">';
    echo '<input type="hidden" name="widget_instance" id="widget-instance" value="'.$this->id.'">';
    echo '<button type="button" value="yes" class="'.$btn_class.'">Yes</button> ';
    echo '<button type="button" value="no" class="'.$btn_class.'">No</button>';
    echo '</form>';
    echo '<div class="message hidden">'.wpautop($message).'</div>';
    echo $args['after_widget'];
    add_action('wp_footer', array($this, 'assets'));
  }

  public function form($instance) {

    $title = isset($instance['title']) ? $instance['title'] : 'Feedback';
    $content = isset($instance['content']) ? $instance['content'] : '';
    $message = isset($instance['message']) ? $instance['message'] : '';
    $btn_class = isset($instance['btn_class']) ? $instance['btn_class'] : '';

    echo '<p><label for="' . $this->get_field_id('title') . '">Title:</label>
    <input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" /></p>';

    echo '<p>';
    echo '<label for="' . $this->get_field_id('content') . '">Question:</label>';
    echo '<textarea name="'.$this->get_field_name('content').'" id="'.$this->get_field_id('content').'" class="widefat" rows="5">'.$content.'</textarea>';
    echo '</p>';

    echo '<p>';
    echo '<label for="' . $this->get_field_id('message') . '">After submission message:</label>';
    echo '<textarea name="'.$this->get_field_name('message').'" id="'.$this->get_field_id('message').'" class="widefat" rows="5">'.$message.'</textarea>';
    echo '</p>';

    echo '<p>';
    echo '<label for="' . $this->get_field_id('btn_class') . '">Buttons class name:</label>';
    echo '<input class="widefat" id="' . $this->get_field_id('btn_class') . '" name="' . $this->get_field_name('btn_class') . '" type="text" value="' . esc_attr($btn_class) . '" />';
    echo '</p>';
  }

  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = (empty($new_instance['title'])) ? '' : strip_tags( $new_instance['title'] );
    $instance['content'] = (empty($new_instance['content'])) ? '' : strip_tags( $new_instance['content'] );
    $instance['message'] = (empty($new_instance['message'])) ? '' : $new_instance['message'];
    $instance['btn_class'] = (empty($new_instance['btn_class'])) ? '' : $new_instance['btn_class'];
    return $instance;
  }
}
