<?php

class Helpers {
    
    /*
    * Time to seconds
    */
    static public function timeToSec ($time) {
        $hours = substr($time, 0, -6);
        $minutes = substr($time, -5, 2);
        $seconds = substr($time, -2);

        return $hours * 3600 + $minutes * 60 + $seconds;
    }

    /*
    * Seconds to time
    *  25*60*60 -> 25:00:00
    */    
    static function secToTime ($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor($seconds % 3600 / 60);
        $seconds = $seconds % 60;
        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }
    

    /* A function to take a date in ($date) in specified inbound format (eg mm/dd/yy for 12/08/10) and
     * return date in $outFormat (eg yyyymmdd for 20101208)
     *    datefmt (
     *                        string $date - String containing the literal date that will be modified
     *                        string $inFormat - String containing the format $date is in (eg. mm-dd-yyyy)
     *                        string $outFormat - String containing the desired date output, format the same as date()
     *                    )
     *
     *
     *    ToDo:
     *        - Add some error checking and the sort?
     */    
    static function datefmt($date, $inFormat, $outFormat) {
        $order = array('mon' => NULL, 'day' => NULL, 'year' => NULL);

        for ($i=0; $i<strlen($inFormat);$i++) {
            switch ($inFormat[$i]) {
                case "m":
                    $order['mon'] .= substr($date, $i, 1);
                    break;
                case "d":
                    $order['day'] .= substr($date, $i, 1);
                    break;
                case "y":
                    $order['year'] .= substr($date, $i, 1);
                    break;
            }
        }

        $unixtime = mktime(0, 0, 0, $order['mon'], $order['day'], $order['year']);
        $outDate = date($outFormat, $unixtime);

        if ($outDate == False) {
            return False;
        } else {
            return $outDate;
        }
    }
        
        /**
         * Возвращает расширение файла
         *
         * @param string $filename - Путь с именем файла
         * @return string расширение файла
         */
        static function getExtension($filename) {
                return end(explode(".", $filename));
        }
    
        /**
         * Удаляет 'соль' из ключей массива
         *
         * @param array $data
         * @param string $salt
     * @return void
         */    
    static function removeSalt( &$data, $salt) {
        if (!empty($salt)) {
            foreach($data as $key => $value) {
                $pos = strpos( $key, $salt);
                if ( $pos !== false) {
                    $key_ = substr($key, 0, $pos);
                    $data[$key_] = $value;
                    unset($data[$key]);
                }
            }
        }
    }
    
    /*
     * scandir() with regexp matching on file name and sorting options based on stat().
     * 
     * files can be sorted on name and stat() attributes, ascending and descending:
        name    file name
        dev     device number
        ino     inode number
        mode    inode protection mode
        nlink   number of links
        uid     userid of owner
        gid     groupid of owner
        rdev    device type, if inode device *
        size    size in bytes
        atime   time of last access (Unix timestamp)
        mtime   time of last modification (Unix timestamp)
        ctime   time of last inode change (Unix timestamp)
        blksize blocksize of filesystem IO *
        blocks  number of blocks allocated
     * 
     */
    static function scandir($dir, $exp, $how = 'name', $desc=0) {
        $r = array();
        $dh = @opendir($dir);
        if ($dh) {
            while (($fname = readdir($dh)) !== false) {
                if (preg_match($exp, $fname)) {
                    $stat = stat("$dir/$fname");
                    $r[$fname] = ($how == 'name') ? $fname: $stat[$how];
                }
            }
            closedir($dh);
            if ($desc) {
                arsort($r);
            } else {
                asort($r);
            }
        }
        return(array_keys($r));
    }

    /*
     * Fast list of files ordered by its ctime
     */
    static function scandirFast($dir, $exp = "", $desc = false, $limit = null) {

        $dir = rtrim($dir, '/') . '/';
        $order = $desc == false ? '-t' : '-tr';

        $sLimit = '';
//        if ($limit) {
//            $sLimit = " | head -n$limit";
//        }

        $cmd = "cd $dir; ls $exp $order -1$sLimit";
        //echo $cmd . PHP_EOL;

        exec($cmd, $output);

        if ($limit && $output) {
            $output = array_slice($output, 0, $limit);
        }


        return $output;
    }

    static function checkHostReachable( $host, $port, $timeout = 5 ) {
        exec(sprintf('ping -c 1 -w 2 %s', escapeshellarg($host)), $res, $rval);
        return $rval===0;
    }
}
