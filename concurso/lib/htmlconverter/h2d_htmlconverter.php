<?php

/**
* 
* HTMLtodocx
* HTML to docx Converter
* - HTML converter for use with PHPWord
* Copyright (C) 2011  Commtap CIC
* 
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2.1
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
* 
* @copyright  Copyright (c) 2011 Commtap CIC
* @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
* @version    Devel 0.5.0, 22.11.2011
* 
*/

// Functions for converting and adding HTML into PHPWord objects
// for creating a docx document.

/**
* 
* These are the elements which can be processed by this converter
* 
* This will tell us when to stop when parsing HTML.
* Anything still remaining after a stop (i.e. no more
* parsable tags) to be returned as is (with any tags filtered out).
* 
* @param string $tag - optional - the tag for the element for which
* its possible children are required.
* @return mixed
*/
function h2d_html_allowed_children($tag = NULL) {

  $allowed_children = array(
    'body' => array('p', 'ul', 'ol', 'table', 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'),
    'h1' => array('a', 'em', 'i', 'strong', 'b', 'br', 'span', 'u', 'sup', 'text'),
    'h2' => array('a', 'em', 'i', 'strong', 'b', 'br', 'span', 'u', 'sup', 'text'),
    'h3' => array('a', 'em', 'i', 'strong', 'b', 'br', 'span', 'u', 'sup', 'text'),
    'h4' => array('a', 'em', 'i', 'strong', 'b', 'br', 'span', 'u', 'sup', 'text'),
    'h5' => array('a', 'em', 'i', 'strong', 'b', 'br', 'span', 'u', 'sup', 'text'),
    'h6' => array('a', 'em', 'i', 'strong', 'b', 'br', 'span', 'u', 'sup', 'text'),
    'p' => array('a', 'em', 'i', 'strong', 'b', 'ul', 'ol', 'img', 'table', 'br', 'span', 'u', 'sup', 'text', 'div', 'p'), // p does not nest - simple_html_dom will create a flat set of paragraphs if it finds nested ones.
    'div' => array('a', 'em', 'i', 'strong', 'b', 'ul', 'ol', 'img', 'table', 'br', 'span', 'u', 'sup', 'text', 'div', 'p'),
    'a' => array('text'), // PHPWord doesn't allow elements to be placed in link elements
    'em' => array('a', 'strong', 'b', 'br', 'span', 'u', 'sup', 'text'), // Italic
    'i' => array('a', 'strong', 'b', 'br', 'span', 'u', 'sup', 'text'), // Italic
    'strong' => array('a', 'em', 'i', 'br', 'span', 'u', 'sup', 'text'), // Bold
    'b' => array('a', 'em', 'i', 'br', 'span', 'u', 'sup', 'text'), // Bold
    'sup' => array('a', 'em', 'i', 'br', 'span', 'u', 'text'), // Superscript
    'u' => array('a', 'em', 'strong', 'b', 'i', 'br', 'span', 'sup', 'text'), // Underline - deprecated - but could be encountered.
    'ul' => array('li'),
    'ol' => array('li'),
    'li' => array('a', 'em', 'i', 'strong', 'b', 'ul', 'ol', 'img', 'br', 'span', 'u', 'sup', 'text'),
    'img' => array(),
    'table' => array('tbody', 'tr'),
    'tbody' => array('tr'),
    'tr' => array('td'),
    'td' => array('p', 'a', 'em', 'i', 'strong', 'b', 'ul', 'ol', 'img', 'br', 'span', 'u', 'sup', 'text', 'table'), // PHPWord does not allow you to insert a table into a table cell
    'br' => array(),
    'span' => array('a', 'em', 'i', 'strong', 'b', 'img', 'br', 'span', 'sup', 'text'), // Used for styles - underline
    'text' => array(), // The tag name used for elements containing just text in SimpleHtmlDom.
  );
  
  if (!$tag) {
    return $allowed_children;
  }
  elseif (isset($allowed_children[$tag])) {
    return $allowed_children[$tag];
  }
  else {
    return array();
  }
}

/**
* Clean up text:
* 
* @param string $text
*/
function h2d_clean_text($text) {
  
  // Replace each &nbsp; with a single space:
  $text = str_replace('&nbsp;', ' ', $text);
  if (strpos($text, '<') !== FALSE) {
    // We only run strip_tags if it looks like there might be some tags in the text
    // as strip_tags is expensive:
    $text = strip_tags($text);
  }
  // Strip out extra spaces:
  $text = preg_replace('/\s+/u', ' ', $text);
  
  return $text;
}

/**
* Compute the styles that should be applied for the 
* current element.
* We start with the default style, and successively override
* this with the current style, style set for the tag, classes
* and inline styles.
* 
* @param mixed $element
* @param mixed $state
* @return array
*/
function h2d_get_style($element, $state) {
 
  // Lists:
  $state['pseudo_list'] = TRUE; // This converter only supports "pseudo" lists at present.
  
  $style_sheet = $state['style_sheet'];
  
  // Get the default styles
  $phpword_style = $style_sheet['default'] ? $style_sheet['default'] : array();
  
  // Update with the current style
  $current_style = $state['current_style'] ? $state['current_style'] : array();
  $phpword_style = array_merge($phpword_style, $current_style);
  
  // Update with any styles defined by the element tag
  $tag_style = $style_sheet['elements'][$element->tag] ? $style_sheet['elements'][$element->tag] : array();
  $phpword_style = array_merge($phpword_style, $tag_style);
  
  // Find any classes defined for this element:
  $class_list = array();
  if (!empty($element->class)) {
    $classes = explode(' ', $element->class);
    foreach ($classes as $class) {
      $class_list[] = trim($class);
    } 
  }
  
  // Look for any style definitions for these classes:
  $classes_style = array();
  if (!empty($class_list) && !empty($style_sheet['classes'])) {
    foreach ($style_sheet['classes'] as  $class => $attributes) {
      if (in_array($class, $class_list)) {
        $classes_style = array_merge($classes_style, $attributes); 
      }
    }
  }
  
  $phpword_style = array_merge($phpword_style, $classes_style);
  
  // Find any inline styles:
  $inline_style_list = array();
  if (!empty($element->attr['style'])) {
    $inline_styles = explode(';', rtrim(rtrim($element->attr['style']), ';'));
    foreach ($inline_styles as $inline_style) {
      $style_pair = explode(':', $inline_style); 
      $inline_style_list[] = trim($style_pair[0]) . ': ' . trim($style_pair[1]);
    }
  }
  
  // Look for style definitions of these inline styles:
  $inline_styles = array();
  if (!empty($inline_style_list) && !empty($style_sheet['inline'])) {
    foreach ($style_sheet['inline'] as  $inline_style => $attributes) {
      if (in_array($inline_style, $inline_style_list)) {
        $inline_styles = array_merge($inline_styles, $attributes); 
      }
    } 
  }
  
  $phpword_style = array_merge($phpword_style, $inline_styles);
  
  return $phpword_style;
  
}


/**
* Populate PHPWord element
* This recursive function processes all the elements and child elements
* from the DOM array of objects created by SimpleHTMLDom.
* 
* @param object phpword_element - the object from PHPWord in which to place the converted html
* @param array $html_dom_array - array of nodes generated by simple HTML dom
* @param array $state - variables for the current run
*/
function h2d_insert_html(&$phpword_element, $html_dom_array, &$state = array()) {
  
  // Set some defaults:
  $state['current_style'] = $state['current_style'] ? $state['current_style'] : array('size' => '11');
  $state['parents'] = $state['parents'] ? $state['parents'] : array(0 => 'body'); // Our parent is body
  $state['list_depth'] = $state['list_depth'] ? $state['list_depth'] : 0;
  $state['context'] = $state['context'] ? $state['context'] : 'section'; // Possible values - section, footer or header
  
  
  // Go through the html_dom_array, adding bits to go in the PHPWord element
  $allowed_children = h2d_html_allowed_children($state['parents'][0]);
 
  // Go through each element:
  foreach ($html_dom_array as $element) {

    $old_style = $state['current_style'];
    
    $state['current_style'] = h2d_get_style($element, $state);
    
    switch($element->tag) {
      
      case 'p':
      case 'div': // Treat a div as a paragraph
      case 'h1':
      case 'h2':
      case 'h3':
      case 'h4':
      case 'h5':
      case 'h6':
        // Everything in this element should be in the same text run
        // we need to initiate a text run here and pass it on:
        $state['textrun'] = $phpword_element->createTextRun($state['current_style']);
        if (in_array($element->tag, $allowed_children)) {
          array_unshift($state['parents'], $element->tag);
          h2d_insert_html($phpword_element, $element->nodes, $state);
          array_shift($state['parents']);
        }
        else {
          $state['textrun']->addText(h2d_clean_text($element->innertext),  $state['current_style']);
        }
        unset($state['textrun']);
        if (!isset($state['current_style']['spaceAfter'])) {
          // For better usability for the end user of the Word document, we 
          // separate paragraphs and headings with an empty line. You can 
          // override this behaviour by setting the spaceAfter parameter for
          // the current element.
          $phpword_element->addTextBreak();
        }
      break;
      
      case 'table':
        if (in_array('table', $allowed_children)) {
          $old_table_state = $state['table_allowed'];
          if (in_array('td', $state['parents'])) {
            $state['table_allowed'] = FALSE; // This is a PHPWord constraint
          }
          else {
            $state['table_allowed'] = TRUE;
            $state['table'] = $phpword_element->addTable();
          }
          array_unshift($state['parents'], 'table');
          h2d_insert_html($phpword_element, $element->nodes, $state);
          array_shift($state['parents']);
          // Reset table state to what it was before a table was added:
          $state['table_allowed'] = $old_table_state; 
          $phpword_element->addTextBreak(); 
        }
        else {
          $state['textrun'] = $phpword_element->createTextRun();
          $state['textrun']->addText(h2d_clean_text($element->innertext),  $state['current_style']);
        }     
      break;
      
      case 'tbody':
        if (in_array('tbody', $allowed_children)) {
          array_unshift($state['parents'], 'tbody');
          h2d_insert_html($phpword_element, $element->nodes, $state);
          array_shift($state['parents']); 
        }
        else {
          $state['textrun'] = $phpword_element->createTextRun();
          $state['textrun']->addText(h2d_clean_text($element->innertext),  $state['current_style']);
        }
      break;
      
      case 'tr':
        if (in_array('tr', $allowed_children)) {
          if ($state['table_allowed']) {
            $state['table']->addRow();
          }
          else {
            // Simply add a new line if a table is not possible in this context:
            $state['textrun'] = $phpword_element->createTextRun();
          }
          array_unshift($state['parents'], 'tr');
          h2d_insert_html($phpword_element, $element->nodes, $state);
          array_shift($state['parents']); 
        }
        else {
          $state['textrun'] = $phpword_element->createTextRun();
          $state['textrun']->addText(h2d_clean_text($element->innertext),  $state['current_style']);
        }     
      break;

      case 'td':
        // Unset any text run there may happen to be:
        // unset($state['textrun']);
        if (in_array('td', $allowed_children) && $state['table_allowed']) {
          unset($state['textrun']);
          if (isset($element->width)) {
            $cell_width = $element->width * 15; // Converting at 15 TWIPS per pixel
          }
          else {
            $cell_width = 800;
          }
          $state['table_cell'] = $state['table']->addCell($cell_width);
          array_unshift($state['parents'], 'td');
          h2d_insert_html($state['table_cell'], $element->nodes, $state);
          array_shift($state['parents']); 
        }
        else {
          if (!isset($state['textrun'])) {
            $state['textrun'] = $phpword_element->createTextRun();
          }
          $state['textrun']->addText(h2d_clean_text($element->innertext),  $state['current_style']);
        }
      break;
      
      case 'a':
        // Create a new text run if we aren't in one already:
        if (!$state['textrun']) {
          $state['textrun'] = $phpword_element->createTextRun();
        }
        if ($state['context'] == 'section') {
          
          if (strpos($element->href, 'http://') === 0) {
            $href = $element->href;
          }
          elseif (strpos($element->href, '/') === 0) {
            $href = $state['base_root'] . $element->href;
          }
          else {
            $href = $state['base_root'] . $state['base_path'] . $element->href; 
          }
          
          $state['textrun']->addLink($href, h2d_clean_text($element->innertext), $state['current_style']);
        }
        else {
          // Links can't seem to be included in headers or footers with PHPWord:
          // trying to include them causes an error which stops Word from opening the 
          // file - in Word 2003 with the converter at least.
          // So add the link styled as a link only.
          $state['textrun']->addText(h2d_clean_text($element->innertext), $state['current_style']);
        }
      break;
      
      case 'ul':
        if (in_array('ul', $allowed_children)) {
          if (!$state['pseudo_list']) {
            // Unset any existing text run:
            unset($state['textrun']); // PHPWord lists cannot appear in a text run. If we leave a text run active then subsequent text will go in that text run (if it isn't re-initialised), which would mean that text after this list would appear before it in the Word document.
          }
          array_unshift($state['parents'], 'ul');
          h2d_insert_html($phpword_element, $element->nodes, $state);
          array_shift($state['parents']);
        }
        else {
          $state['textrun'] = $phpword_element->createTextRun();
          $state['textrun']->addText(h2d_clean_text($element->innertext),  $state['current_style']);
        }
      break;
      
      case 'ol':
        $state['list_number'] = 0; // Reset list number. 
        if (in_array('ol', $allowed_children)) {
          if (!$state['pseudo_list']) {
            // Unset any existing text run:
            unset($state['textrun']); // Lists cannot appear in a text run. If we leave a text run active then subsequent text will go in that text run (if it isn't re-initialised), which would mean that text after this list would appear before it in the Word document.
          }
          array_unshift($state['parents'], 'ol');
          h2d_insert_html($phpword_element, $element->nodes, $state);
          array_shift($state['parents']);
        }
        else {
          $state['textrun'] = $phpword_element->createTextRun();
          $state['textrun']->addText(h2d_clean_text($element->innertext),  $state['current_style']);
        }
      break;
      
      case 'li': 
        // You cannot style individual pieces of text in a list element so we do it
        // with text runs instead. This does not allow us to indent lists at all, so
        // we can't show nesting.
        
        // Create a new text run for each element:
        $state['textrun'] = $phpword_element->createTextRun();
        
        if (in_array('li', $allowed_children)) {
          if ($state['parents'][0] == 'ol') {
            $state['list_number']++;
            $item_indicator = $state['list_number'] . '. ';
            $style = $state['current_style'];
          }
          else {
            $style = $state['current_style'];
            $style['name'] = $state['pseudo_list_indicator_font_name']; 
            $style['size'] = $state['pseudo_list_indicator_font_size'];
            $item_indicator = $state['pseudo_list_indicator_character'];
          }
          array_unshift($state['parents'], 'li');
          $state['textrun']->addText($item_indicator, $style);
          h2d_insert_html($phpword_element, $element->nodes, $state);
          array_shift($state['parents']);
        }
        else {
          $state['textrun']->addText(h2d_clean_text($element->innertext),  $state['current_style']);
        }
//        $phpword_element->addTextBreak();
        unset($state['textrun']);
      break;
      
      case 'text':
      // We may get some empty text nodes - containing just a space -
      // in simple HTML dom - we want
      // to exclude those, as these can cause extra line returns. However
      // we don't want to exclude spaces between styling elements (these will be within
      // a text run).
        if (!$state['textrun']) {
          $text = h2d_clean_text(trim($element->innertext));
        }
        else {
          $text = h2d_clean_text($element->innertext);
        }
        if (!empty($text)) {
          if (!$state['textrun']) {
            $state['textrun'] = $phpword_element->createTextRun();
          }
          $state['textrun']->addText($text, $state['current_style']);
        }
      break;
      
      // Style tags:
      case 'strong':
      case 'b':
      case 'sup': // Not working in PHPWord
      case 'em':
      case 'i':
      case 'u':      
      case 'span':
        
        // Create a new text run if we aren't in one already:
        if (!$state['textrun']) {
          $state['textrun'] = $phpword_element->createTextRun();
        }
        if (in_array($element->tag, $allowed_children)) {
          array_unshift($state['parents'], $element->tag);
          h2d_insert_html($phpword_element, $element->nodes, $state);
          array_shift($state['parents']);
        }
        else {
          $state['textrun']->addText(h2d_clean_text($element->innertext), $state['current_style']);
        }
      break;
      
      case 'br':
        // Simply create a new text run: 
        $state['textrun'] = $phpword_element->createTextRun();
      break;
      
      case 'img':
        $image_style = array();
        if ($element->height && $element->width) {
         $state['current_style']['height'] = $element->height;
         $state['current_style']['width'] = $element->width; 
        }
        $phpword_element->addImage(ltrim($element->src, '/'), $state['current_style']);
      break;

      default:
        $state['textrun'] = $phpword_element->createTextRun();
        $state['textrun']->addText(h2d_clean_text($element->innertext),  $state['current_style']);
      break; 
    } 
    
    // Reset the style back to what it was:
    $state['current_style'] = $old_style;
  }
}