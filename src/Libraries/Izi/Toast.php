<?php

/**
 * Plugin.php - Adapter for the jQuery Modal library.
 *
 * @package jaxon-dialogs
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @copyright 2016 Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-dialogs
 */

namespace Jaxon\Dialogs\Libraries\Izi;

use Jaxon\Dialogs\Libraries\Library;
use Jaxon\Dialogs\Contracts\Modal;
use Jaxon\Contracts\Dialogs\Message;
use Jaxon\Contracts\Dialogs\Question;

class Toast extends Library implements Message, Question
{
    use \Jaxon\Features\Dialogs\Message;

    /**
     * The constructor
     */
    public function __construct()
    {
        parent::__construct('izi-toast', '1.1.1');
    }

    /**
     * @inheritDoc
     */
    public function getJs()
    {
        return $this->getJsCode('iziToast.min.js');
    }

    /**
     * @inheritDoc
     */
    public function getCss()
    {
        return $this->getCssCode('iziToast.min.css');
    }

    /**
     * @inheritDoc
     */
    public function getScript()
    {
        return $this->render('izi/alert.js');
    }

    /**
     * @inheritDoc
     */
    public function getReadyScript()
    {
        return $this->render('izi/ready.js.php');
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
    protected function alert($message, $title, $type)
    {
        $options = array('message' => $message);
        // Show the alert
        $this->addCommand(array('cmd' => "izitoast.$type"), $options);
    }

    /**
     * @inheritDoc
     */
    public function success($message, $title = null)
    {
        $this->alert($message, $title, 'success');
    }

    /**
     * @inheritDoc
     */
    public function info($message, $title = null)
    {
        $this->alert($message, $title, 'info');
    }

    /**
     * @inheritDoc
     */
    public function warning($message, $title = null)
    {
        $this->alert($message, $title, 'warning');
    }

    /**
     * @inheritDoc
     */
    public function error($message, $title = null)
    {
        $this->alert($message, $title, 'error');
    }

    /**
     * @inheritDoc
     */
    public function confirm($question, $yesScript, $noScript)
    {
        $title = $this->getQuestionTitle();
        if(!$noScript)
        {
            return "jaxon.dialogs.izi.confirm(" . $question . ",'" . $title . "',function(){" . $yesScript . ";})";
        }
        else
        {
            return "jaxon.dialogs.izi.confirm(" . $question . ",'" . $title . "',function(){" . $yesScript . ";},function(){" . $noScript . ";})";
        }
    }
}
