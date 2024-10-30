<?php

namespace BlueCube;

class ContentFeedback {

  protected $possible_answers = array(
    'yes',
    'no'
  );

  const VER = '1.0';
  public $messages = [];

  function __construct() {
//    add_action( 'admin_menu', array($this, 'createMenu') );
    add_action( 'widgets_init', array($this, 'loadWidgets') );
    add_action( 'wp_head', array($this, 'defineAjaxUrlVar') );
    add_action( 'wp_ajax_bc-submit-content-feedback', array($this, 'contentFeedbackAjaxCallController') );
    add_action( 'wp_ajax_nopriv_bc-submit-content-feedback', array($this, 'contentFeedbackAjaxCallController') );
    add_filter( 'admin_comment_types_dropdown', array($this, 'addCommentFilterItem'));
  }

  /**
   * To define the ajaxurl variable for ajax calls
   */
  function defineAjaxUrlVar() {
    echo '<script type="text/javascript">var ajaxurl = "'.admin_url('admin-ajax.php').'";</script>';
  }

  public function loadWidgets() {
    register_widget( 'ContentFeedbackFormWidget' );
  }

  public function contentFeedbackAjaxCallController() {

    check_ajax_referer( 'bc-content-feedback-form', 'security' );

    $post_id = ( isset($_REQUEST['post_id']) ) ? $_REQUEST['post_id'] : '';
    $widget_instance = ( isset($_REQUEST['widget_instance']) ) ? $_REQUEST['widget_instance'] : '';

    $feedback = ( isset($_REQUEST['feedback']) ) ? $_REQUEST['feedback'] : '';

    if (!empty($post_id) && !empty($widget_instance) && $this->feedbackIsAmongAllowedValue($feedback)) {

      $widget_settings = self::getWidgetInstanceSettings($widget_instance);
      $feedback_question_line = 'Question: ' . $widget_settings['content'];
      $feedback_answer_line = 'Answer: ' . ucfirst($feedback);

      $comment_content = $feedback_question_line . "\r\n";
      $comment_content .= $feedback_answer_line;

      $comment_args = array(
        'comment_post_ID'  => $post_id,
        'comment_content'  => $comment_content,
        'comment_type'     => 'bc-content-feedback',
        'comment_approved' => 0,
      );
      if (isset($_SERVER['REMOTE_ADDR'])) {
        $comment_args['comment_author_IP'] = $_SERVER['REMOTE_ADDR'];
      }

      $email_headers  = '';

      $current_user_id = get_current_user_id();
      if ($current_user_id) {
        $wp_user = get_user_by('id', $current_user_id);
        $comment_args['user_id'] = $current_user_id;
        $comment_args['comment_author_email'] = $wp_user->user_email;
        $comment_args['comment_author_url'] = $wp_user->user_url;
        $comment_args['comment_author'] = $wp_user->display_name;
        $email_headers .= "From: $wp_user->user_email <$wp_user->user_email>\r\n";
        $email_headers .= "Reply-To: $wp_user->user_email\r\n";
      }
      $email_headers .= "MIME-Version: 1.0\r\n";
      $email_headers .= "Content-Type: text/html; charset=UTF-8\r\n";

      $comment_id = wp_insert_comment($comment_args);
      $comment_edit_page_uri = admin_url('comment.php?action=editcomment&c='.$comment_id);
      $admin_email = get_option( 'admin_email' );
      $email_subject = 'BlueCube Content Feedback';

      /**
      You have received feedback on the xxxxx page, please see below.
      Question: We value your feedback. Was this information useful to you?
      Answer: xxx (yes or no)
       */

      $wp_post = get_post($post_id);
      $wp_post_permalink = get_permalink($post_id);

      $email_content = "<p>Heads Up - A user has submitted feedback on your website, page \"<a href='$wp_post_permalink'>$wp_post->post_title</a>\". Please see below:</p>";
      $email_content .= "<p>$feedback_question_line<br>$feedback_answer_line</p>";
      $email_content .= "<p><a href='$comment_edit_page_uri'>Click here</a> to go to the comment edit page.</p>";

      wp_mail($admin_email, $email_subject, $email_content, $email_headers);
      wp_send_json_success();
    }

    wp_die();
  }

  /**
   * Extracts the widget instance id (number) from the widget instance name
   * @param $instance
   * @return mixed
   */
  public static function getWidgetInstanceIdFromInstance($instance) {
    $regex = '@(.*\-)([0-9]+)@';
    return preg_replace($regex, '$2', $instance);
  }

  public static function getWidgetInstanceSettings($widget_instance) {
    $widget_class_instance = new \ContentFeedbackFormWidget();
    $widget_instance_id = self::getWidgetInstanceIdFromInstance($widget_instance);
    $active_widget_instances_settings = $widget_class_instance->get_settings();
    return $active_widget_instances_settings[$widget_instance_id];
  }

  public function feedbackIsAmongAllowedValue($feedback) {
    return in_array($feedback, $this->possible_answers);
  }

  public function createMenu() {
    $parent = 'options-general.php';
    add_submenu_page( $parent, 'BlueCube Content Feedback', 'Content Feedback', 'administrator', 'bluecube-content-feedback', array($this, 'settingsPage') );
  }

  public function settingsPage() {
    include dirname(__FILE__) . '/../controller/settings.php';
    include dirname(__FILE__) . '/../template/settings.php';
  }

  public function addErrorMsg($msg = '') {
    $this->messages['error'][] = $msg;
  }

  public function addSuccessMsg($msg = '') {
    $this->messages['success'][] = $msg;
  }

  public function showMessages() {
    if (!empty($this->messages)) {
      if (!empty($this->messages['error'])) {
        echo '<div class="error">';
        foreach ($this->messages['error'] as $error) {
          echo '<p>' . $error . '</p>';
        }
        echo '</div>';
      }
      if (!empty($this->messages['success'])) {
        echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible">';
        foreach ($this->messages['success'] as $success) {
          echo  '<p><strong>'.$success.'</strong></p>';
        }
        echo '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
        echo '</div>';
      }
    }
  }

  public function addCommentFilterItem($items_array) {
    $items_array['bc-content-feedback'] = 'Content Feedback';
    return $items_array;
  }

}
