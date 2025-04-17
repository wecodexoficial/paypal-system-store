<?php
/**
 * Created by PhpStorm.
 * User: JMartinez
 * Date: 05/09/2017
 * Time: 10:03 AM
 */

class FileParser
{
    public static function toArray($file)
    {

        return parse_ini_file($file);

    }

    /**
     *
     * @param $file
     * @param array $data
     */
    public static function writeArrayToIni($file, $data = array())
    {
        $res=null;
        $array = self::toArray($file);
        foreach ($data as $key => $value) {
            $result = array_key_exists($key, $array);
            if ($result) {
                $explotion = explode(',', $array[$key]);
                if (!in_array($value, $explotion)) {
                    $array[$key] = $array[$key] . ',' . $value;
                }
            } else {
                $array[$key] = $value;
            }
        }
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $res[] = "[$key]";
                foreach ($val as $skey => $sval) {
                    $res[] = "$skey = " . (is_numeric($sval) ? $sval : '' . $sval . '');
                }
            } else {
                $res[] = "$key = " . (is_numeric($val) ? $val : '' . $val . '');
            }
        }
        unset($res[0]);
        self::safefilerewrite($file, implode("\r\n", $res));
    }

    /**
     *
     * @param $fileName
     * @param $dataToSave
     */
    private static function safefilerewrite($fileName, $dataToSave)
    {
        if ($fp = fopen($fileName, 'w')) {
            $startTime = microtime(TRUE);
            do {
                $canWrite = flock($fp, LOCK_EX);
                if (!$canWrite) {
                    usleep(round(rand(0, 100) * 1000));
                }
            } while ((!$canWrite) && ((microtime(TRUE) - $startTime) < 5));

            if ($canWrite) {
                fwrite($fp,"[ACCESS]\npolicy = deny\n[ACCESS.rules]\n");
                fwrite($fp, $dataToSave);
                flock($fp, LOCK_UN);
            }
            fclose($fp);
        }

    }

}