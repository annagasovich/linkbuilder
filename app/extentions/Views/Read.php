<?php

namespace App\extentions\Views;

class Read {

    private $data = [];
    private $render = FALSE;

    public function __construct($template, $data = null)
    {
        try {
            $file = dirname(__FILE__) . '/templates/' . strtolower($template) . '.php';
            if (file_exists($file)) {
                $this->render = $file;
            } else {
                throw new customException('Template ' . $template . ' not found!');
            }
        }
        catch (customException $e) {
            echo $e->errorMessage();
        }
        $this->data = $data;
    }

    public function render() {
        ob_start();
        if($this->data)
            extract($this->data);
        include($this->render);
        return ob_get_clean();
    }

}
