<?php

namespace App;

/**
 * Class Searcher
 *
 * @package App
 */
class Searcher
{
    /**
     * @return string
     */
	static public function indexAction()
	{
	    return Render::getInstance()->getRender('index.html.twig',[
	        'app_dir'=>APP_DIR
        ]);
	}

    /**
     * @return string
     */
	static public function searchAction()
	{
		$Ids = [];
		foreach ($_POST as $k => $id) {
			if ($id != '') {
				$Ids[] = $id;
			}
		}

		$Games = SteamFinder::findAll($Ids);

		return Render::getInstance()->getRender('search.html.twig',[
            'app_dir'=>APP_DIR, 'result'=>print_r(compact('Games'), true)
        ]);
	}
}