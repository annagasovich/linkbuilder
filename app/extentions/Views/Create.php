<?php

namespace App\extentions\Views;

class Create {

    private $data = [];
    private $render = FALSE;
    private $config = false;

    public function __construct($template, $data = null, $config = null)
    {
        if($config)
            $this->config = $config;
        try {
            $file = dirname(__FILE__) . '/templates/' . strtolower($template) . '.php';
            if(isset($config['tpl']))
                $file = dirname(__FILE__) . '/'.$config['tpl'].'/' . strtolower($template) . '.php';
            if (file_exists($file)) {
                $this->render = $file;
            } else {
                var_dump($file);
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
