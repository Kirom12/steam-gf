<?php

namespace App;

/**
 * Class SteamFinder
 */
class SteamFinder
{
    /**
     * @var string
     */
	private static $_STEAM_KEY = STEAM_KEY;

    /**
     * @var string
     */
	private static $_getOwnedGamesUrl = 'http://api.steampowered.com/IPlayerService/GetOwnedGames/v0001/';

    /**
     * @var array
     */
	private static $_Players = [];

    /**
     * @var array
     */
	private static $_PlayersIntersectGames = [];

    /**
     * @var bool
     */
	private static $debug = false;

    /**
     * @param $id
     */
	private static function _findOneAllGames($id)
	{
		$json = file_get_contents(self::$_getOwnedGamesUrl . '?key=' . self::$_STEAM_KEY . '&steamid=' . $id . '&format=json');

		//For debug
        if (self::$debug === true) {
            $json = file_get_contents(ROOT . '/test/' . $id);
        }

		$data = json_decode($json);

		$Players = [
			"name" => "name",
			"game_count" => $data->response->game_count,
			"GameList" => [],
			"PlayTime" => []
		];

		foreach ($data->response->games as $key => $game) {
            $Players['GameList'][] = $game->appid;
            $Players['PlayTime'][] = $game->playtime_forever;
		}

		self::$_Players[] = $Players;
	}

    /**
     * @return boolean
     */
	private static function _getIntersectGames()
	{
	    if(!self::$_Players) {
	        return false;
        }

		$ArrayIntersect = self::$_Players[0]['GameList'];

		for ($i = 1; $i < count(self::$_Players); $i++) {
			$ArrayIntersect = array_intersect($ArrayIntersect, self::$_Players[$i]['GameList']);
		}

		foreach ($ArrayIntersect as $key => $value) {
			$totalPlayTime = 0;

			foreach (self::$_Players as $keyPlayer => $Player) {
				$totalPlayTime += $Player['PlayTime'][array_search($value, $Player['GameList'])];
			}

			$oneGame = [
				"appid" => $value,
				"totalPlayTime" => $totalPlayTime
			];

			self::$_PlayersIntersectGames[] = $oneGame;
		}

		return true;
	}

    /**
     * @param $Ids
     *
     * @return array
     */
	public static function findAll($Ids)
	{
		foreach ($Ids as $key => $id) {
			self::_findOneAllGames($id);
		}

		self::_getIntersectGames();

		return self::$_PlayersIntersectGames;
	}

}
