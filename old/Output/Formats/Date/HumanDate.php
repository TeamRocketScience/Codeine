<?php

function F_HumanDate_Format($Date)
{
    $day = '';
    $time = '';
    $y = '';
    $m = '';
    $d = '';
    // получаем значение даты и времени
    $day = date('Y-m-d', $Date);

    switch( $day )
    {
	// Если дата совпадает с сегодняшней
	case date('Y-m-d'):
          	    $result = 'Сегодня';
           	    break;

	//Если дата совпадает со вчерашней
	case date( 'Y-m-d', mktime(0, 0, 0, date("m")  , date("d")-1, date("Y")) ):
         	    $result = 'Вчера';
           	    break;

	 default:
	 {
	   	// Разделяем отображение даты на составляющие
	     	list($y, $m, $d)  = explode('-', $day);

		$month_str = array(
			 	'января', 'февраля', 'марта',
			 	'апреля', 'мая', 'июня',
			 	'июля', 'августа', 'сентября',
			 	'октября', 'ноября', 'декабря'
			 );
		$month_int = array(
			 	'01', '02', '03',
			 	'04', '05', '06',
			 	'07', '08', '09',
			 	'10', '11', '12'
			 );

             	// Замена числового обозначения месяца на словесное (склоненное в падеже)
             	$m = str_replace($month_int, $month_str, $m);
             	// Формирование окончательного результата
		$result = $d.' '.$m;
	}
    }
     return $result;
}