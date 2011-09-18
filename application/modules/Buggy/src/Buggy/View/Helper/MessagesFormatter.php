<?php

namespace Buggy\View\Helper;

use Zend\View\Helper\AbstractHelper;

class MessagesFormatter extends AbstractHelper
{
    /**
     * Formats given messages in a paragraph with given class
     * input format can be string, array, multi dimensional array
     * 
     * With array use following notation: array('error', 'message')
     * -> first child is class for paragraph 
     *      - use success|notice|error for blueprint
     *      - use (alert-message) warning|error|success|info for bootstrap
     * -> second child is printed message
     * 
     * @param  array  $messages
     * @param  string $tag (default=p)
     * @param  string $format (default=bootstrap)
     * @return string
     */
    public function direct($messages, $tag = 'div', $format = 'bootstrap')
    {
        $return = '';
        
        if (is_array($messages) && count($messages) > 0) {
            if (is_array($messages[0])) {
                foreach ($messages AS $msg) {
                    if (is_array($msg)) {
                        if ($format == 'bootstrap') {
                            $class = 'class="alert-message '.$msg[0].'"';
                        } else {
                            $class = 'class="notice"';
                        }
                        $return .= '<'.$tag.' '.$class.'>';
                        if ($format == 'bootstrap') {
                            $return .= '<p>';  
                        }
                        $return .= $msg[1];
                        if ($format == 'bootstrap') {
                            $return .= '</p>';  
                        }
                        $return .= '</'.$tag.'>';
                    }
                }
            } else {
                if ($format == 'bootstrap') {
                    $class = 'class="alert-message '.$messages[0].'"';
                } else {
                    $class = 'class="notice"';
                }
                $return .= '<'.$tag.' '.$class.'>';
                if ($format == 'bootstrap') {
                    $return .= '<p>';  
                }
                $return .= $messages[1];
                if ($format == 'bootstrap') {
                    $return .= '</p>';  
                }
                $return .= '</'.$tag.'>';
            }
        } else if (is_string($messages)) {
            if ($format == 'bootstrap') {
                $class = 'class="alert-message warning"';
            } else {
                $class = 'class="notice"';
            }
            $return .= '<'.$tag.' '.$class.'>';
            if ($format == 'bootstrap') {
              $return .= '<p>';  
            }
            $return .= $messages;
            if ($format == 'bootstrap') {
              $return .= '</p>';  
            }
            $return .= '</'.$tag.'>';
        }
        
        return $return;
    }
}