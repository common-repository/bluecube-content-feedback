<?php

/**
 * Plugin Name: BlueCube Content Feedback
 * Plugin URI: https://thebluecube.com
 * Description: This plugin adds a content feedback system to your website
 * Author: Blue Cube Communications Ltd
 * Version: 1.0
 * Author URI: https://thebluecube.com
 * License: GPLv2 or later
 */

if (!defined('ABSPATH')) {
  exit;
}

require_once __DIR__ . '/class/ContentFeedback.php';
require_once __DIR__ . '/class/ContentFeedbackFormWidget.php';

$bluecube_content_feedback = new BlueCube\ContentFeedback();
