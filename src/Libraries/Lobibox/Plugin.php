<?php

/**
 * Plugin.php - Adapter for the Lobibox library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Lobibox;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Contracts\Modal;
use Jaxon\Contracts\Dialogs\Message;
use Jaxon\Contracts\Dialogs\Question;

class Plugin extends Library implements Modal, Message, Question
{
    use \Jaxon\Features\Dialogs\Message;

    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('lobibox', '1.2.4');
    }

    /**
     * Get the javascript header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Plugin interface.
     *
     * @return string
     */
    public function getJs()
    {
        return $this->getJsCode('lobibox.min.js');
    }

    /**
     * Get the CSS header code and file includes
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Plugin interface.
     *
     * @return string
     */
    public function getCss()
    {
        return $this->getCssCode('lobibox.min.css');
    }

    /**
     * Get the javascript code to be printed into the page
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Plugin interface.
     *
     * @return string
     */
    public function getScript()
    {
        return $this->render('lobibox/alert.js');
    }

    /**
     * Show a modal dialog.
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Modal interface.
     *
     * @param string            $title                  The title of the dialog
     * @param string            $content                The content of the dialog
     * @param array             $buttons                The buttons of the dialog
     * @param array             $options                The options of the dialog
     *
     * @return void
     */
    public function show($title, $content, array $buttons, array $options = array())
    {
        // Fill the options array with the parameters
        $options['title'] = (string)$title;
        $options['content'] = (string)$content;
        $options['buttons'] = array();
        $ind = 0;
        foreach($buttons as $button)
        {
            $_button = [
                'text' => $button['title'],
                'action' => $button['click'],
                'class' => $button['class'],
            ];
            // Optional attributes
            foreach($button as $attr => $value)
            {
                if(!in_array($attr, ['title', 'class', 'click']))
                {
                    $_button[$attr] = $value;
                }
            }
            $options['buttons']['btn' . $ind] = $_button;
            $ind++;
        }
        // Show the modal dialog
        $this->addCommand(array('cmd' => 'lobibox.show'), $options);
    }

    /**
     * Hide the modal dialog.
     *
     * It is a function of the Jaxon\Dialogs\Contracts\Modal interface.
     *
     * @return void
     */
    public function hide()
    {
        // Hide the modal dialog
        $this->addCommand(array('cmd' => 'lobibox.hide'), array());
    }

    /**
     * Print an alert message.
     *
     * @param string              $message              The text of the message
     * @param string              $title                The title of the message
     * @param string              $type                 The type of the message
     *
     * @return void
     */
    protected function notify($message, $title, $type)
    {
        if($this->getReturn())
        {
            return "Lobibox.notify('" . $type . "', {title:'" . $title . "', msg:" . $message . "})";
        }
        $options = array('message' => $message, 'type' => $type, 'title' => (($title) ?: false));
        // Show the alert
        $this->addCommand(array('cmd' => 'lobibox.notify'), $options);
    }

    /**
     * Print a success message.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Message interface.
     *
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function success($message, $title = null)
    {
        return $this->notify($message, $title, 'success');
    }

    /**
     * Print an information message.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Message interface.
     *
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function info($message, $title = null)
    {
        return $this->notify($message, $title, 'info');
    }

    /**
     * Print a warning message.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Message interface.
     *
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function warning($message, $title = null)
    {
        return $this->notify($message, $title, 'warning');
    }

    /**
     * Print an error message.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Message interface.
     *
     * @param string              $message              The text of the message
     * @param string|null         $title                The title of the message
     *
     * @return void
     */
    public function error($message, $title = null)
    {
        return $this->notify($message, $title, 'error');
    }

    /**
     * Get the script which makes a call only if the user answers yes to the given question.
     *
     * It is a function of the Jaxon\Contracts\Dialogs\Question interface.
     *
     * @return string
     */
    public function confirm($question, $yesScript, $noScript)
    {
        $title = $this->getQuestionTitle();
        if(!$noScript)
        {
            return "jaxon.dialogs.lobibox.confirm(" . $question . ",'" . $title . "',function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.dialogs.lobibox.confirm(" . $question . ",'" . $title . "',function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
