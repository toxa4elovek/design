<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dmitriynyu
 * Date: 12/15/11
 * Time: 2:23 PM
 * To change this template use File | Settings | File Templates.
 */

namespace app\extensions\helper;

class MoneyFormatter extends \lithium\template\Helper
{

    public function formatMoney($price, $options = [])
    {
        $defaults = ['suffix' => '.-', 'dropspaces' => false];
        $options += $defaults;
        $price = preg_replace('/(.*)\.00/', "$1", $price);

        if (!$options['dropspaces']) {
            while (preg_match('/\w\w\w\w/', $price)) {
                $price = preg_replace('/^(\w*)(\w\w\w)(\W.*)?$/', "$1 $2$3", $price);
            }
        }
        return $price . $options['suffix'];
    }

    public function num2str($inn, $stripkop=false)
    {
        $nol = 'ноль';

        $str[100]= ['','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот', 'восемьсот','девятьсот'];

        $str[11] = ['','десять','одиннадцать','двенадцать','тринадцать', 'четырнадцать','пятнадцать','шестнадцать','семнадцать', 'восемнадцать','девятнадцать','двадцать'];

        $str[10] = ['','десять','двадцать','тридцать','сорок','пятьдесят', 'шестьдесят','семьдесят','восемьдесят','девяносто'];

        $sex = [

            ['','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'],// m

            ['','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'] // f

        ];

        $forms = [

            ['копейка', 'копейки', 'копеек', 1], // 10^-2

            ['рубль', 'рубля', 'рублей',  0], // 10^ 0

            ['тысяча', 'тысячи', 'тысяч', 1], // 10^ 3

            ['миллион', 'миллиона', 'миллионов',  0], // 10^ 6

            ['миллиард', 'миллиарда', 'миллиардов',  0], // 10^ 9

            ['триллион', 'триллиона', 'триллионов',  0], // 10^12

        ];

        $out = $tmp = [];

        // Поехали!

        $tmp = explode('.', str_replace(',', '.', $inn));

        $rub = number_format($tmp[ 0], 0, '', '-');

        if ($rub== 0) {
            $out[] = $nol;
        }

        // нормализация копеек

        $kop = isset($tmp[1]) ? substr(str_pad($tmp[1], 2, '0', STR_PAD_RIGHT), 0, 2) : '00';

        $segments = explode('-', $rub);

        $offset = sizeof($segments);

        if ((int)$rub== 0) { // если 0 рублей

            $o[] = $nol;

            $o[] = $this->morph(0, $forms[1][ 0], $forms[1][1], $forms[1][2]);
        } else {
            foreach ($segments as $k=>$lev) {
                $sexi= (int) $forms[$offset][3]; // определяем род

                $ri = (int) $lev; // текущий сегмент

                if ($ri== 0 && $offset>1) {
                    // если сегмент==0 & не последний уровень(там Units)

                    $offset--;

                    continue;
                }

                // нормализация

                $ri = str_pad($ri, 3, '0', STR_PAD_LEFT);

                // получаем циферки для анализа

                $r1 = (int)substr($ri, 0, 1); //первая цифра

                $r2 = (int)substr($ri, 1, 1); //вторая

                $r3 = (int)substr($ri, 2, 1); //третья

                $r22= (int)$r2.$r3; //вторая и третья

                // разгребаем порядки

                if ($ri>99) {
                    $o[] = $str[100][$r1];
                } // Сотни

                if ($r22>20) {
                    // >20

                    $o[] = $str[10][$r2];

                    $o[] = $sex[ $sexi ][$r3];
                } else { // <=20

                    if ($r22>9) {
                        $o[] = $str[11][$r22-9];
                    } // 10-20

                    elseif ($r22> 0) {
                        $o[] = $sex[ $sexi ][$r3];
                    } // 1-9
                }

                // Рубли

                $o[] = $this->morph($ri, $forms[$offset][ 0], $forms[$offset][1], $forms[$offset][2]);

                $offset--;
            }
        }

        // Копейки

        if (!$stripkop) {
            $o[] = $kop;

            $o[] = $this->morph($kop, $forms[ 0][ 0], $forms[ 0][1], $forms[ 0][2]);
        }

        return preg_replace("/\s{2,}/", ' ', implode(' ', $o));
    }



    /**

     * Склоняем словоформу

     */

    public function morph($n, $f1, $f2, $f5)
    {
        $n = abs($n) % 100;

        $n1= $n % 10;

        if ($n>10 && $n<20) {
            return $f5;
        }

        if ($n1>1 && $n1<5) {
            return $f2;
        }

        if ($n1==1) {
            return $f1;
        }

        return $f5;
    }

    /**
     * Метод вовзращяет процент $discount от числа $value
     *
     * @param $value
     * @param $discount
     * @return mixed
     */
    public function applyDiscount($value, $discount)
    {
        return $value - ($value * ($discount * 0.01));
    }
}
