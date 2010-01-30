<?php
// $Id: views_plugin_row_comment_rss.inc,v 1.3.6.2 2010/01/27 23:28:35 dereine Exp $
/**
 * @file
 * Contains the comment RSS row style plugin.
 */

/**
 * Plugin which formats the comments as RSS items.
 */
class views_plugin_row_comment_rss extends views_plugin_row {
   var $base_table = 'comments';
   var $base_field = 'cid';

  function render($row) {
    global $base_url;

    // Load the specified comment:
    $comment = comment_load($row->{$this->field_alias});

    $item = new stdClass();
    $item->title = $comment->subject;
    $item->link = url('node/' . $comment->nid, array('absolute' => TRUE, 'fragment' => 'comment-' . $comment->cid));
    $item->description = check_markup($comment->comment, $comment->format);
    $item->elements = array(
      array('key' => 'pubDate', 'value' => gmdate('r', $comment->timestamp)),
      array('key' => 'dc:creator', 'value' => $comment->name),
      array(
        'key' => 'guid',
        'value' => 'comment ' .  $row->cid . ' at ' . $base_url,
        'attributes' => array('isPermaLink' => 'false'),
        'namespace' => array('xmlns:dc' => 'http://purl.org/dc/elements/1.1/'),
      ),
    );

    foreach ($item->elements as $element) {
      if (isset($element['namespace'])) {
        $this->view->style_plugin->namespaces = array_merge($this->view->style_plugin->namespaces, $element['namespace']);
      }
    }

    return theme($this->theme_functions(), array(
      'view' => $this->view,
      'options' => $this->options,
      'row' => $item
    ));
  }
}