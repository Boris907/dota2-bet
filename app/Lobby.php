<?php

namespace App;

class Lobby
{
     // объявление свойства
	private static $dir = '/home/vagrant/code/auth/storage/app/public/';
	public static $fullPath;

    /*
        Ищем свободный файл
    */
	static public function checkDir() 
	{        
		$files = scandir(self::$dir);
		foreach ($files as $file) 
		{
			$check = preg_match('/^[0-9]+o/', $file);
			if($check == 1)
			{
				$fileName = $file;
				self::$fullPath = self::$dir.$fileName;
				return $fileName;
			}
		} 	
	}
	/*
		Генерируем индекс для названия файла
	*/
	static public function newFile()
	{
		$num = random_int(1000, 9999);
        $fileName = $num.'o.txt';
		return 'public\\'.$fileName;
	}
	/*
		Все ид из файла
	*/
	static public function allID()
	{
		$str = file_get_contents(Lobby::$fullPath);
        $arr = explode(' ', $str);
        return $arr;
	}
}
