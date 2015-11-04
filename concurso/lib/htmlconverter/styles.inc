<?php

// Example styles
// This is an example of how a "style sheet" should be structured
// to asign PHPWord styles to HTML elements, classes, and inline
// styles.
function h2d_styles() {
  
  // Set of default styles - 
  // to set initially whatever the element is:
  // NB - any defaults not set here will be provided by PHPWord.
  $styles['default'] = 
    array (
      'size' => 10,
    );
  
  // Element styles:
  // The keys of the elements array are valid HTML tags;
  // The arrays associated with each of these tags is a set
  // of PHPWord style definitions.
  $styles['elements'] = 
    array (
      'h1' => array (
        'bold' => TRUE,
        'size' => 20,
        ),
      'h2' => array (
        'bold' => TRUE,
        'size' => 15,
        'spaceAfter' => 150,
        ),
      'h3' => array (
        'size' => 12,
        'bold' => TRUE,
        'spaceAfter' => 100,
        ),
      'b' => array (
        'bold' => TRUE,
        ),
      'em' => array (
        'italic' => TRUE,
        ),
      'i' => array (
        'italic' => TRUE,
        ),
      'strong' => array (
        'bold' => TRUE,
        ),
      'b' => array (
        'bold' => TRUE,
        ),
      'sup' => array (
        'superScript' => TRUE,
        'size' => 6,
        ), // Superscript not working in PHPWord 
      'u' => array (
        'underline' => PHPWord_Style_Font::UNDERLINE_SINGLE,
        ),
      'a' => array (
        'color' => '0000FF',
        'underline' => PHPWord_Style_Font::UNDERLINE_SINGLE,
        ),
      );
      
  // Classes:
  // The keys of the classes array are valid CSS classes;
  // The array associated with each of these classes is a set
  // of PHPWord style definitions.
  // Classes will be applied in the order that they appear here if
  // more than one class appears on an element.
  $styles['classes'] = 
    array (
      'underline' => array (
        'underline' => PHPWord_Style_Font::UNDERLINE_SINGLE,
        ),
       'purple' => array (
        'color' => '901391',
       ),
       'green' => array (
        'color' => '00A500',
       ),
      );
  
  // Inline style definitions, of the form:
  // array(css attribute-value - separated by a colon and a single space => array of
  // PHPWord attribute value pairs.    
  $styles['inline'] = 
    array(
      'text-decoration: underline' => array (
        'underline' => PHPWord_Style_Font::UNDERLINE_SINGLE,
      ),
      'vertical-align: left' => array (
        'align' => 'left',
      ),
      'vertical-align: middle' => array (
        'align' => 'center',
      ),
      'vertical-align: right' => array (
        'align' => 'right',
      ),
    );
    
  return $styles;
}