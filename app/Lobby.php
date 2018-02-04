<?php

namespace App;

class Lobby
{
    // объявление свойства
    private static $dir = '/home/vagrant/code/auth/dota2-roulette/storage/app/public/';
    public static $fullPath;

    /*
        Ищем свободный файл
    */
    static public function checkDir()
    {
        $files = scandir(self::$dir);
        foreach ($files as $file) {
            $check = preg_match('/^[0-9]+o/', $file);
            if ($check == 1) {
                $fileName       = $file;
                self::$fullPath = self::$dir . $fileName;

                return $fileName;
            }
        }
    }

    /*
        Генерируем индекс для названия файла
    */
    static public function newFile()
    {
        $num      = random_int(1000, 9999);
        $fileName = $num . 'o.txt';

        return 'public\\' . $fileName;
    }

    static public function places()
    {
        if (self::checkDir() != null) {
            $str = file_get_contents(self::$fullPath);
            $str = str_replace("\n", "", $str);
            $arr = explode(' ', $str);
            array_pop($arr);
            for ($i = 0; $i < 20; $i += 2) {
                $playersID[$arr[$i + 1]] = $arr[$i];
            }

            /*var_dump($playersID);
            die();*/

            return $playersID;
        } else {
            ($arr = 0);
        }
    }
}
	