<?php
/**
* Posts thumbnails actions
*
* 
* @package      Customizr
* @subpackage   classes
* @since        3.0.5
* @author       Nicolas GUILLAUME <nicolas@themesandco.com>
* @copyright    Copyright (c) 2013, Nicolas GUILLAUME
* @link         http://themesandco.com/customizr
* @license      http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/
if ( ! class_exists( 'TC_post_thumbnails' ) ) :
  class TC_post_thumbnails {
      static $instance;
      function __construct () {
          self::$instance =& $this;
          //Set thumbnails hooks and options (since 3.2.0)
          add_action ( 'init'                           , array( $this , 'tc_set_thumb_options') );
      }

      

      /**
      * Gets the thumbnail or the first images attached to the post if any
      * @return array( $tc_thumb(image object), $tc_thumb_width(string), $tc_thumb_height(string) )
      *
      * @package Customizr
      * @since Customizr 1.0
      */
      function tc_get_thumbnail_data( $requested_size = null ) {

        //output vars declaration
        $tc_thumb                       = '';
        $tc_thumb_height                = '';
        $tc_thumb_width                 = '';
        $image                          = array();
        $_class_attr                    = array();
        
        //define a filtrable boolean to set if attached images can be used as thumbnails
        //1) must be a non single post context
        //2) user option should be checked in customizer
        $_use_attachment_as_thumb = ! TC_post::$instance -> tc_single_post_display_controller() 
        && ( 0 != esc_attr( tc__f( '__get_option' , 'tc_post_list_use_attachment_as_thumb' ) ) );

        //define the default thumb size
        $tc_thumb_size                  = is_null($requested_size) ? apply_filters( 'tc_thumb_size_name' , 'tc-thumb' ) : $requested_size ;

        //define the default thumnail if has thumbnail
        if (has_post_thumbnail()) {
            $tc_thumb_id                = get_post_thumbnail_id();

            //check if tc-thumb size exists and has not been filtered
            $image                      = wp_get_attachment_image_src( $tc_thumb_id, $tc_thumb_size);

            //check if the size exists
            if ( false == $image[3] && 'tc-thumb' == $tc_thumb_size ) {
              $tc_thumb_size            = 'large';
              $_filtered_thumb_size     = apply_filters( 'tc_thumb_size' , TC_init::$instance -> tc_thumb_size );

              //IMPORTANT : si pas de taille tc-thumb définie, utiliser un style dynamique
              //width: auto;max-width: none;
              //calculer automatiquement
              $_width                   = $_filtered_thumb_size['width'];
              $_height                  = $_filtered_thumb_size['height'];
              $_class_attr              = array( 
                'class' => "attachment-{$tc_thumb_size} no-tc-thumb-size wp-post-image" , 
                'style' => "min-width:{$_width}px;min-height:{$_height}px"
              );
            }

            //check if the size exists
            if ( false == $image[3] && 'tc_rectangular_size' == $tc_thumb_size ) {
              $tc_thumb_size            = 'slider';
            }

            $tc_thumb                   = get_the_post_thumbnail( get_the_ID(), $tc_thumb_size , $_class_attr);

            //get height and width
            $tc_thumb_height            = $image[2];
            $tc_thumb_width             = $image[1];
        }

        //check if no thumbnail then uses the first attached image if any
        elseif ( false != $_use_attachment_as_thumb ) {
          //Case if we display a post or a page
           if ( 'attachment' != tc__f('__post_type') ) {
             //look for attachements in post or page
            $tc_args = apply_filters('tc_attachment_as_thumb_query_args' , array(
                'numberposts'             =>  1,
                'post_type'               =>  'attachment' ,
                'post_status'             =>  null,
                'post_parent'             =>  get_the_ID(),
                'post_mime_type'          =>  array( 'image/jpeg' , 'image/gif' , 'image/jpg' , 'image/png' ),
                'orderby'                 => 'post_date',
                'order'                   => 'DESC'
              )
            );

              $attachments              = get_posts( $tc_args);
            }

            //case were we display an attachment (in search results for example)
            elseif ( 'attachment' == tc__f('__post_type') && wp_attachment_is_image() ) {
              $attachments = array( get_post() );
            }


          if ( isset($attachments) ) {
            foreach ( $attachments as $attachment) {
               //check if tc-thumb size exists for attachment and return large if not
              $image                    = wp_get_attachment_image_src( $attachment->ID, $tc_thumb_size);
              $tc_thumb_size            = (false == $image[3] && 'tc-thumb' == $tc_thumb_size) ? 'medium' : $tc_thumb_size;
              $_class_attr              = (false == $image[3] && 'tc-thumb' == $tc_thumb_size) ? array( 'class' => "attachment-{$tc_thumb_size} no-tc-thumb-size wp-post-image" ) : $_class_attr ;
              //check if the size exists
              if ( false == $image[3] && 'tc_rectangular_size' == $tc_thumb_size ) {
                $tc_thumb_size            = 'slider';
              }
              $tc_thumb                 = wp_get_attachment_image( $attachment->ID, $tc_thumb_size, $_class_attr );

              //get height and width
              $tc_thumb_height          = $image[2];
              $tc_thumb_width           = $image[1];
            }
          }
        }

        //the current post id is included in the array of parameters for a better granularity.
        return apply_filters( 'tc_get_thumbnail_data' , array( $tc_thumb, $tc_thumb_width, $tc_thumb_height ), tc__f('__ID') );

      }//end of function
          



      /**
      * Displays the thumbnail or the first images attached to the post if any
      * Takes 2 parameters : thumbnail data array (img, width, height) and layout value
      * 
      * @package Customizr
      * @since Customizr 3.0.10
      */
      function tc_display_post_thumbnail( $thumb_data , $layout = 'span3' ) {
        $thumb_img                  = !isset( $thumb_data) ? false : $thumb_data[0];
        $thumb_img                  = apply_filters( 'tc_post_thumb_img', $thumb_img, tc__f('__ID') );
        if ( ! $thumb_img )
          return;

        //handles the case when the image dimensions are too small
        $thumb_size                 = apply_filters( 'tc_thumb_size' , TC_init::$instance -> tc_thumb_size, tc__f('__ID')  );
        $no_effect_class            = ( isset($thumb_data[0]) && isset($thumb_data[1]) && ( $thumb_data[1] < $thumb_size['width']) ) ? 'no-effect' : '';
        $no_effect_class            = apply_filters( 'tc_no_round_thumb', $no_effect_class, tc__f('__ID') );

        //default hover effect
        $thumb_wrapper              = sprintf('<div class="%5$s %1$s"><div class="round-div"></div><a class="round-div %1$s" href="%2$s" title="%3$s"></a>%4$s</div>',
                                      $no_effect_class,
                                      get_permalink( get_the_ID() ),
                                      get_the_title( get_the_ID() ),
                                      $thumb_img,
                                      implode( " ", apply_filters( 'tc_thumb_wrapper_class', array('thumb-wrapper') ) )
        );

        $thumb_wrapper              = apply_filters_ref_array( 'tc_post_thumb_wrapper', array( $thumb_wrapper, $thumb_img, tc__f('__ID') ) );

        //renders the thumbnail
        $html = sprintf('<section class="tc-thumbnail %1$s">%2$s</section>',
          apply_filters( 'tc_post_thumb_class', $layout ),
          $thumb_wrapper
        );

        echo apply_filters_ref_array( 'tc_display_post_thumbnail', array( $html, $thumb_data, $layout ) );

      }//end of function


      function tc_set_thumb_options() {
        //Set thumb shape with customizer options (since 3.2.0)
        add_filter ( 'tc_post_thumb_wrapper'          , array( $this , 'tc_set_thumb_shape'), 10 , 2);
        //Set thumb size depending on the customizer thumbnail position options (since 3.2.0)
        add_filter ( 'tc_thumb_size_name'             , array( $this , 'tc_set_thumb_size') );
      }



      /**
      * Callback of filter tc_post_thumb_wrapper
      * ! 2 cases here : posts lists and single posts
      *
      * @package Customizr
      * @since Customizr 3.2.0
      */
      function tc_set_thumb_shape( $thumb_wrapper, $thumb_img ) {
        //Post Lists
        if ( TC_post_list::$instance -> tc_post_list_controller() ) {
          $_shape = esc_attr( tc__f( '__get_option' , 'tc_post_list_thumb_shape') );
          if ( ! $_shape || false !== strpos($_shape, 'rounded') || false !== strpos($_shape, 'squared') )
            return $thumb_wrapper;
          
          $_position = esc_attr( tc__f( '__get_option' , 'tc_post_list_thumb_position' ) );
          return sprintf('<div class="%4$s"><a class="tc-rectangular-thumb" href="%1$s" title="%2s">%3$s</a></div>',
                get_permalink( get_the_ID() ),
                get_the_title( get_the_ID() ),
                $thumb_img,
                ( 'top' == $_position || 'bottom' == $_position ) ? '' : implode( " ", apply_filters( 'tc_thumb_wrapper_class', array('thumb-wrapper') ) )
          );
        }
        if ( TC_post::$instance -> tc_single_post_display_controller() ) {
          return sprintf('<div class="%4$s"><a class="tc-rectangular-thumb" href="%1$s" title="%2s">%3$s</a></div>',
                get_permalink( get_the_ID() ),
                get_the_title( get_the_ID() ),
                $thumb_img,
                implode( " ", apply_filters( 'tc_thumb_wrapper_class', array() ) )
          );
        }
      }



      /**
      * Callback of filter tc_thumb_size_name
      * 
      *
      * @package Customizr
      * @since Customizr 3.2.0
      */
      function tc_set_thumb_size( $_default_size ) {
        $_shape = esc_attr( tc__f( '__get_option' , 'tc_post_list_thumb_shape') );
        if ( ! $_shape || false !== strpos($_shape, 'rounded') || false !== strpos($_shape, 'squared') )
          return $_default_size;
        
        $_position                  = esc_attr( tc__f( '__get_option' , 'tc_post_list_thumb_position' ) );
        return ( 'top' == $_position || 'bottom' == $_position ) ? 'tc_rectangular_size' : $_default_size;
      }

  }//end of class
endif;