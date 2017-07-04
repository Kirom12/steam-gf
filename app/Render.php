<?php
/**
 * Created by PhpStorm.
 * User: le-dams
 * Date: 28/06/2017
 * Time: 4:35 AM
 */

namespace App;

/**
 * Class Render
 *
 * @package App
 */
class Render
{
    /**
     * @var Render
     */
    static private $instance;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @return string
     */
    private function getCacheDir()
    {
        return ROOT.'/var/cache/'.ENV.'/twig';
    }

    static public function boot()
    {
        if(!self::$instance) {

            self::$instance = new self;
            self::$instance->init();
        }
    }

    /**
     * @return Render
     */
    static public function getInstance()
    {
        self::boot();

        return self::$instance;
    }

    /**
     * @return bool
     */
    public function init()
    {
        try {

            $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem(ROOT.'/view/'));
            $this->twig->addGlobal('env',ENV);
            if (CACHING === true) {

                if(!file_exists(self::getCacheDir())) {
                    mkdir(self::getCacheDir(), 0755, true);
                }

                $this->twig->setCache(new \Twig_Cache_Filesystem(self::getCacheDir()));
            }

            return true;
        } catch(\Exception $e) {

            return false;
        }
    }

    /**
     * @param $view
     * @param $parameters
     *
     * @return string
     */
    public function getRender($view, $parameters)
    {
        return $this->twig->render($view, $parameters);
    }
}