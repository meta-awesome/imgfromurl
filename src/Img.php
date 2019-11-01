<?php
namespace Metawesome\ImgFromUrl;

use Metawesome\ImgFromUrl\Commands\Command;

class Img
{
    /**
     * @var string the name of the `wkhtmltoimage` binary. Default is
     * `wkhtmltoimage`. You can also configure a full path here.
     */
    public $binary = 'wkhtmltoimage';

    /**
     * @var Command the command instance that executes wkhtmltoimage
     */
    protected $_command;

    /**
     * @var filename the file name can be set or auto generated
     */
    protected $filename;

    /**
     * @var url the url to generate a Image file
     */
    protected $url;

    /**
     * @var string the detailed error message. Empty string if none.
     */
    protected $_error = '';

    /**
     * @var bool whether the Image was created
     */
    protected $_isCreated = false;

    protected $options = [];

    public function fromUrl(string $url)
    {
        if(empty($url)) {
            return false;
        }

        $this->url = $url;

        return $this;
    }

    /**
     * @return Command the command instance that executes wkhtmltoimage
     */
    public function getCommand()
    {
        if ($this->_command === null) {
            if (!isset($options['command'])) {
                $options['command'] = $this->binary;
            }
            $this->_command = new Command($options);
        }
        return $this->_command;
    }

    /**
     * Save the Image to given filename (triggers Image creation)
     *
     * @param string $filename to save Image as
     * @return bool whether Image was created successfully
     */
    public function saveAs(string $filename)
    {
        if(empty($filename)) {
            return false;
        }

        if ($this->_isCreated) {
            return false;
        }

        $command = $this->getCommand();

        if (!empty($this->options)) {
            foreach($this->options as $option) {
                $command->addArg($option);
            }
        }

        $command->addArg("'{$this->url}'", $filename, null, true);    // Always escape filename
        if (!$command->execute()) {
            $this->_error = $command->getError();
            if (!(file_exists($filename) && filesize($filename) == 0)) {
                return false;
            }

            if ($this->_error) {
                return $this->_error;
            }
        }

        $this->_isCreated = true;

        return true;
    }

    public function addOption(string $option)
    {
        if (!empty($option)) {
            array_push($this->options, $option);
        }
    }

    /**
     * @return string the detailed error message. Empty string if none.
     */
    public function getError()
    {
        return $this->_error;
    }
}