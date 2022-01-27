<?php
    $S_NameQuery = $_POST['S_NameQuery'];        // Выбранное имя запроса из списка !
    $DescQuery = $_POST['descquery'];            // Описание запроса - текст с экрана !
    $TableVar = $_POST['typevar'];               // Типы переменных - текст с экрана !
    $GoalList = $_POST['targetlist'];            // Целевой список - текст с экрана !
    $QueryBody = $_POST['algquery'];             // Тело запроса - текст с экрана !
    $QuerySQL = $_POST['query'];                 // Тело SQL - текст с экрана !
    //$QueryRez = $_POST['Qrez'];                // Результат запроса - текст с экрана !
        
    $Gr = $_POST['group']; 
    $Fio = $_POST['lastname']; 
    $Nq = $_POST['nq'];
    $N_Q_New = $Nq . "_" . $Gr . "_" . $Fio; 	  // Имя нового запроса!

function TV($tv)
// На входе строка TypeVar, на выходе ассоциативный массив TVX($s)['X'] = Студенты AS X и т.д. !
{
	$p = []; $w = [];
	$b = substr_count($tv, ',', 0);
	$p = explode(",", $tv);

	for ($i = 0; $i <= $b; $i++) { $p[$i] = ltrim($p[$i]); }

	for ($i = 0; $i <= $b; $i++) { $w[explode(" ", $p[$i])[2]] = $p[$i]; }
    return $w;
}

function TVX($tv)
// На входе строка TypeVar, на выходе ассоциативный массив TV($s)[0] = X и т.д. !
{
	$p = [];
	$b = substr_count($tv, ',', 0);
	for ($i = 0; $i <= $b; $i++) { $p[$i] = substr(explode(",", $tv)[$i], -1); }
    return $p;
}

function TVT($tv)
// На входе строка TypeVar, на выходе ассоциативный массив TVT($s)['X'] = Студенты и т.д. !
{
	$p = []; $w = [];
	$b = substr_count($tv, ',', 0);
	$p = explode(",", $tv);

	for ($i = 0; $i <= $b; $i++) { $p[$i] = ltrim($p[$i]); }

	for ($i = 0; $i <= $b; $i++) { $w[explode(" ", $p[$i])[2]] = explode(" ", $p[$i])[0]; }
    return $w;
}

function GL($gl,$x)
// На входе строка GoalList, на выходе ассоциативный массив GL : [0] = X и т.д. GLX($a, 1)[0] = Нз!
// Параметр $x = 0 => GL($gl, 0)[0] = X ; $x = 1 => GL($gl, 1)[0] = Нз !
{
	$p = []; $w = []; $ww = [];
	$b = substr_count($gl, ',', 0);
	$p = explode(",", $gl);

	for ($i = 0; $i <= $b; $i++) { $p[$i] = ltrim($p[$i]); }

	for ($i = 0; $i <= $b; $i++) {
	    If ($x == 0) $ww[$i] = explode(".", $p[$i])[0];		// X
	    If ($x == 1) $w[$i] = explode(".", $p[$i])[1];      // Нз
	    }
// Избавление от повторных значений переменных в массиве :
	$j = 0;
	for ($i = 0; $i <= $b; $i++) { If ( $ww[$i] !== $ww[$i + 1] ) {$w[$j] = $ww[$i]; $j++; } }
    return $w;
}

function QB($qb)
{
//На входе строка QueryBody, на выходе ассоциативный массив QBO($s)[0] = Левая; QBO($s)[1] = Правая; QBO($s)[2] = Операция!
	
	$qb = preg_replace('/\s+/', ' ', $qb);
	$o = [' EXCEPT ', '#', ' UNION ', ' INTERSECT '];
	$p = [$qb, '', 'A'];
	If (strpos($qb, $o[0]) !== false) { $p = explode( $o[0], $qb ); $p[2] = ' EXCEPT '; }
	If (strpos($qb, $o[1]) !== false) { $p = [$qb, '', ' DELETE ']; }
	If (strpos($qb, $o[2]) !== false) { $p = explode( $o[2], $qb ); $p[2] = ' UNION '; }
	If (strpos($qb, $o[3]) !== false) { $p = explode( $o[3], $qb ); $p[2] = ' INTERSECT '; }
	return $p;
}

function QB_01($qb, $gl, $x) 
{
// функция QB_0 обрабатывает строку QueryBody (1), выдавая разные массивы в зависимости от параметра $x 
// $x = 1 массив $m_qb1 (0 => X) переменных QueryBody, НЕ входящих в массив переменных GoalList
// $x = 2 массив $m_qb2 (0 => X) переменных QueryBody (1)

// Количество открывающих операционных скобок ([):
	$qb_qty = substr_count($qb, '[', 0);
// Преобразуем строку QueryBody в массив $m_qb1 по скобке '[' :	
	$m_qb0 = explode('[', $qb);
	for ($i = 0; $i <= $qb_qty; $i++)
//	 { $m_qb0[$i] = ltrim($m_qb0[$i], '('); $m_qb0[$i] = rtrim($m_qb0[$i], ')'); }
	{ $m_qb0[$i] = trim ( $m_qb0[$i], $character_mask = "\(\)" ); }
// Заполняем 0-строку массива $mqb2, получая "нулевую" переменную ( 0 => X ) :
	If ($qb_qty == 0) { $m_qb2[0] = $qb; goto EndQB_01; }
	If ($qb_qty > 0) 
	{	for ($i = 0; $i <= $qb_qty; $i++) { 
			If ($i == 0) { $m_qb2[0] = $m_qb0[0]; }
			else { {$m_qb2[$i] = explode(']', $m_qb0[$i])[1]; }}}
	}
	foreach ($m_qb2 as $key => $value) 
	{   if (empty($value)) { //проверяем если пустой
           unset($m_qb2[$key]); }
	}
EndQB_01: $m_qb1 = array_slice(array_diff ( $m_qb2, GL($gl, 0)), 0, $qb_qty);
	If ($x == 1) { return $m_qb1; } 
	If ($x == 2) { return $m_qb2; }
}

function QB_02($qb, $x) 
{
// функция QB_02 обрабатывает строку QueryBody (2), выдавая разные массивы в зависимости от параметра $x
// $x = 0 строку $GoalList (2), обращение QB_02($wQ2, 0) 
// $x = 1 массив $m_qb1 (0 => X) переменных QueryBody, НЕ входящих в массив переменных GoalList
// $x = 2 массив $m_qb2 (0 => X) переменных QueryBody (1)

// Количество открывающих операционных скобок ([):
	$qb_qty = substr_count($qb, '[', 0);
// Преобразуем строку QueryBody в массив $m_qb1 по скобке '[' :	
	$m_qb0 = explode('[', $qb);
	for ($i = 0; $i <= $qb_qty; $i++)
//	 { $m_qb0[$i] = ltrim($m_qb0[$i], '('); $m_qb0[$i] = rtrim($m_qb0[$i], ')'); }
	{ $m_qb0[$i] = trim ( $m_qb0[$i], $character_mask = "\(\)" ); }
	$s_qb0 = rtrim($m_qb0[$qb_qty], ']');
// Заполняем 0-строку массива $mqb2, получая "нулевую" переменную ( 0 => X ) :
	for ($i = 0; $i <= $qb_qty - 1; $i++) { 
			If ($i == 0) { $m_qb2[0] = $m_qb0[0]; }
			else { {$m_qb2[$i] = explode(']', $m_qb0[$i])[1]; }}}
	foreach ($m_qb2 as $key => $value) 
	{   if (empty($value)) { 				//проверяем если пустой
           unset($m_qb2[$key]); }
	}
	$m_qb1 = array_slice(array_diff ( $m_qb2, GL($s_qb0, 0)), 0, $qb_qty);
	If ($x == 0) { return $s_qb0; }
	If ($x == 1) { return $m_qb1; } 
	If ($x == 2) { return $m_qb2; }
}

function QB_0($qb, $gl, $x) {
// функция QB_0 обрабатывает строку QueryBody (1), выдавая разные массивы в зависимости от параметра $x 
// $x = 1 массив $m_qb0 (0 => X) переменных QueryBody, НЕ входящих в массив переменных GoalList ($m_gl)
// $x = 2 массив $m_qb2 (0 => X) переменных QueryBody
// Количество открывающих операционных скобок ([):
	$qb_qty = substr_count($qb, '[', 0);
// Преобразуем строку QueryBody в массив $m_qb1 по скобке '[' :	
	$m_qb1 = explode('[', $qb);
	for ($i = 0; $i <= $qb_qty; $i++)
	{ $m_qb1[$i] = trim ( $m_qb1[$i], $character_mask = "\(\)" ); }
// Заполняем 0-строку массива $mqb2, получая "нулевую" переменную ( 0 => X ) :
	$m_qb2[0] = $m_qb1[0];
	for ($i = 1; $i <= $qb_qty; $i++) { $m_qb2[$i] = explode(']', $m_qb1[$i])[1]; }

	$m_gl = GL($gl, 0);
	$m_qb0 = array_slice(array_diff ( $m_qb2, $m_gl), 0, $qb_qty);

	If ($x == 1) { return $m_qb0; } 
	If ($x == 2) { return $m_qb2; }
	}

function QB_1($qb, $gl, $x) {
// функция QB_2 обрабатывает строку QueryBody (2), выдавая разные массивы в зависимости от параметра $x 
// $x = 1 массив $m_qb0 (0 => X) переменных QueryBody, НЕ входящих в массив переменных GoalList ($m_gl)
// $x = 2 массив $m_qb2 (0 => X) переменных QueryBody
// Количество открывающих операционных скобок ([):
	$qb_qty = substr_count($qb, '[', 0) - 1;
// Преобразуем строку QueryBody в массив $m_qb1 по скобке '[' :	
	$m_qb1 = explode('[', $qb);
	for ($i = 0; $i <= $qb_qty; $i++) { $m_qb1[$i] = trim ( $m_qb1[$i], $character_mask = "\(\)" ); }
// Заполняем 0-строку массива $mqb2, получая "нулевую" переменную ( 0 => X ) :
	$m_qb2[0] = $m_qb1[0];
	for ($i = 1; $i <= $qb_qty; $i++) { $m_qb2[$i] = explode(']', $m_qb1[$i])[1]; }

	$m_gl = GL($gl, 0);
	$m_qb0 = array_slice(array_diff ( $m_qb2, $m_gl), 0, $qb_qty);

	If ($x == 1) { return $m_qb0; } 
	If ($x == 2) { return $m_qb2; }
	}

function QB_Old($qb, $gl, $x)
{
// функция F_QB_1 обрабатывает строку QueryBody ($s_qb), выдавая разные массивы в зависимости от параметра $x 
// $x = 1 массив $m_qb0 (0 => X) переменных QueryBody, НЕ входящих в массив переменных GoalList ($m_gl)
// $x = 2 массив $m_qb2 (0 => X) переменных QueryBody
// $x = 3 массив $m_qb3 (1 => X.ИдК=Y.ИдК) предикатов QueryBody
	$m_qb0 = []; $m_qb1 = []; $m_qb2 = []; $m_gl = [];
// Количество открывающих операционных скобок ([):
	$qb_qty = substr_count($qb, '[', 0);
// Начинаем разбор строки $s_qb. 
// Преобразуем строку QueryBody в массив $m_qb1 по скобке '[' :	
	$m_qb1 = explode('[', $qb);
// Заполняем 0-строку массива $mqb2, получая "нулевую" переменную ( 0 => X ) :
//	$m_qb2[0] = ltrim($m_qb1[0], '('); $m_qb2[0] = rtrim($m_qb2[0], ')');
	$m_qb2[0] = trim ( $m_qb1[0], $character_mask = "\(\)" );
// Получаем остальные переменные :
	$j = 1;
	for ($i = 1; $i <= $qb_qty; $i++) { If ( strlen(explode(']', $m_qb1[$i])[1] ) > 1 ) { $m_qb2[$j] = rtrim( explode(']', $m_qb1[$i])[1], ')' ); $j++; } }

	$m_gl = GL($gl, 0);
	$m_qb0 = array_slice(array_diff ( $m_qb2, $m_gl), 0, $qb_qty);

	for ($i = 1; $i <= $qb_qty; $i++) { $m_qb3[$i] = explode(']', $m_qb1[$i])[0]; }

	If ($x == 1) { return $m_qb0; } 
	If ($x == 2) { return $m_qb2; }
	If ($x == 3) { return $m_qb3; }
}

function QB_3($z)
// Функция V1($z) преобразует массив $z с элементами 1 => X*Y в пару V1(0) => X; V1(1) => Y
{ 
	$j = 0; $mv = []; $w = [];
    foreach ($z as $x) 
       { $w = explode("*", $x);
       If (count($w) == 1) { $mv[$j] = $w[0]; $j++; }
       else {for ($i = 0; $i <= count($w) - 1; $i++) { $mv[$j] = $w[$i]; $j++; } } }
 return $mv;	
}

function QBX($qb)
{
// функция QBX обрабатывает строку QueryBody ($qb), 
// выдавая массив элементов в [] , разделенных строкой " AND ". 

// Количество открывающих операционных скобок ([):
	$m_qb1 = []; $m_qb2 = []; $m_qb3 = [];
	$qb_qty = substr_count($qb, '[', 0);
// Преобразуем строку QueryBody в массив $m_qb1 по скобке '[' :	
	$m_qb1 = explode('[', $qb);
	$j = 0; 
	foreach ($m_qb1 as $x){
	If (strpos( $x, ']' ) !== false) {$m_qb2[$j] = explode(']', $x)[0]; $j++;}}

	$j = 0; 
	foreach ($m_qb2 as $x1){
		If (strpos( $x1, ' AND ' ) === false) { $m_qb3[$j] = $x1; $j++; }
		else {$t = []; $t = explode(' AND ', $x1);
			foreach ($t as $x2){$m_qb3[$j] = $x2; $j++; }}}
	return $m_qb3;
}

function QBY($tv, $gl)
// Создается массив из переменных $tv, котоорых нет в $gl !
{
	$j = 0; $q = [];
	foreach (TVX($tv) as $x){
		If (!(in_array($x, GL($gl,0)))) 
		{$q[$j] = $x; $j++;} }
    	return $q;
	}	 

	function SQL_1($qb, $tv, $gl)
{
		$w1 = QBY($tv, $gl);						 // массив переменных $tv не входящих в $gl
		$w2 = GL($gl,0);							 // массив переменных  $gl
		$w3 = QBX($qb);								 // массив элементарных предикатов в $qb
		$tvQty = substr_count($tv, ',', 0) + 1;      // $tvQty - кол-во таблиц в $tv !
		$glQty = count(GL($gl, 0));                  // $glQty - кол-во переменных в $gl !
		$qbQty = $tvQty - $glQty;                    // $qbQty - кол-во переменных в $qb не входящих в $gl !
		$prQty = Count(QBX($qb));                    // $prQty - кол-во элементарных предикатов в $qb !
		$QuerySQL .= "SELECT " . $gl . "\n FROM ";
		If ( $prQty == 0 ) { $QuerySQL .= $tv; goto EndSQL_1; }
// Формируется строка FROM : 
		for ($i = 0; $i <= $glQty - 1; $i++) 
		{
	    	If ($i < $glQty - 1) { $QuerySQL .= TV($tv)[$w2[$i]] . ", "; }
			else { $QuerySQL .= TV($tv)[$w2[$i]]; }
		}
// Формируется строка WHERE :
		$QuerySQL .= "\n WHERE ";

		If ( empty ($w1) )		// в $gl все переменные $tv
		{ 
			If ( strpos ($w3[$prQty - 1], ",") === false ) {$wprQty = $prQty - 1;} else {$wprQty = $prQty - 2;} 
			for ($i = 0; $i <= $wprQty; $i++)
			{ If ($i < $wprQty) { $QuerySQL .= $w3[$i] . " AND "; }
			else { $QuerySQL .= $w3[$i]; } }
			goto EndSQL_1;  
		}
		else
		{
// массив с элементарными предикатами (QBX) делим на M1 (только переменные из $gl) и M2 (есть и другие из $tv)
			$j = 0; $k = 0;  $M1 = []; $M2 = [];
			foreach ($w3 as $x) 
			{
  				for ($i = 0; $i <= $qbQty - 1; $i++)
    				{ If (strpos( $x, $w1[$i] ) !== false AND !in_array($x, $M2)) { $M2[$j] = $x; $j++; } } 
			}
			$cM2 = count($M2);
			If ( $prQty == $cM2 )         // $M1 - пустой !
			{ 
				$QuerySQL .= " EXISTS \n ( SELECT * \n FROM ";
				include "M2.php";
				goto EndSQL_1;
			}
			foreach ( array_diff($w3, $M2) as $x ) { $M1[$k] = $x; $k++; }

			$cM1 = count($M1); 
			for ($i = 0; $i <= $cM1 - 1; $i++) 
				{
	    			If ($i < $cM1 - 1) { $QuerySQL .= $M1[$i] . " AND "; }
					else { $QuerySQL .= $M1[$i]; }
				}

		If ($cM2 > 0) 
			{
				If ($cM1 !== 0)	{$QuerySQL .= " AND EXISTS \n ("; }
				else { $QuerySQL .= " EXISTS \n ("; }
				$QuerySQL .= " SELECT * \n FROM ";

				for ($i = 0; $i <= $qbQty - 1; $i++) 
					{
	    				If ($i < $qbQty - 1) { $QuerySQL .= TV($tv)[$w1[$i]] . ", "; }
						else { $QuerySQL .= TV($tv)[$w1[$i]]; }
					}
			$QuerySQL .= "\n WHERE ";
				for ($i = 0; $i <= $cM2 - 1; $i++)
					{ 
						If ($i < $cM2 - 1) { $QuerySQL .= $M2[$i]. " AND "; }	
						else { $QuerySQL .= $M2[$i]. " ) "; }
					}

			}
		}
	EndSQL_1: return $QuerySQL;
}

    $QueryBody = trim ($QueryBody);
	$wQ0 = preg_replace('/\s+/', ' ', QB($QueryBody)[0]); $wQ0 = str_replace ( ') ' , ')' , $wQ0);
	$wQ2 = QB($QueryBody)[2];
	If ($wQ2 == "A") { $sql = SQL_1($wQ0, $TableVar, $GoalList); }
	If ($wQ2 == " EXCEPT " OR $wQ2 == " INTERSECT " OR $wQ2 == " UNION ")
		{
// Первая половина запрса $QueryBody ! 
			$qb_qty_0 = substr_count($wQ0, '[', 0);
			$GoalList_0 = $GoalList;
			$TableVar_0 = '';
			If ( $qb_qty_0 == 0 ) { $TableVar_0 .= TV($TableVar)[trim ( $wQ0, $character_mask = " \(\)" )]; }
			else {
			for ($i = 0; $i <= $qb_qty_0; $i++) { 
			If ($i < $qb_qty_0 ) { $TableVar_0 .= TV($TableVar)[QB_01($wQ0, '', 2)[$i]] . ","; }
			else { $TableVar_0 .= TV($TableVar)[QB_01($wQ0, '', 2)[$i]]; } } }
// Вторая половина запрса $QueryBody !
			$wQ1 = preg_replace('/\s+/', ' ', QB($QueryBody)[1]); $wQ1 = str_replace ( ') ' , ')' , $wQ1);
			$wQ1 = trim ($wQ1);
			$qb_qty_1 = substr_count($wQ1, '[', 0);
	        $GoalList_1 = QB_02($wQ1,0);
			$mwQ2 = explode('[', $wQ1);
			$mwQty = count($mwQ2);
    		$wQ1_2	= '';
			If ($mwQty == 1) { $wQ1_2 .= $mwQ2[0] . ')'; goto EndQ1; }
			If ($mwQty > 1) {
				for ($i = 0; $i <= $mwQty - 2; $i++) { 
					If ( $i < $mwQty - 2 ) { $wQ1_2 .= $mwQ2[$i] . ')['; }
					else {$wQ1_2 .= $mwQ2[$i] . ')'; }
				}
			}
		EndQ1:
			for ($i = 0; $i <= $qb_qty_1 - 1; $i++) { 
				If ($i < $qb_qty_1 - 1) { $TableVar_1 .= TV($TableVar)[QB_02($wQ1,2)[$i]] . ","; }
				else { $TableVar_1 .= TV($TableVar)[QB_02($wQ1,2)[$i]]; } 
			}
		$sql = SQL_1($wQ0, $TableVar_0, $GoalList_0) . "\n" . $wQ2 . "\n" . SQL_1($wQ1_2, $TableVar_1, $GoalList_1); 
		}
	
If ( $wQ2 == " DELETE " )
		{   $wj = 0;
	        $s = preg_replace('/\s+/', ' ', $QueryBody);
	        $mw = QBX($s);
			$w = count ( $mw );
			for ($i = 0; $i <= $w; $i++) 
   				{
					If ( strpos ($mw[$i], '#') > 0 ) 
	   					{ $wj = $i; $mw[$i] = str_replace ('#', '=', $mw[$i]); }
   				}
			$QuerySQL = '';
			$QuerySQL .= "SELECT " . $GoalList . 
			"\n FROM  ". TV($TableVar)[GL($GoalList,0)[0]] . 
			"\n WHERE NOT EXISTS " . 
			"\n(SELECT * \n FROM  ";
			$ws = substr($mw[$wj+1],0,1);
			If ($ws !== '(') { $QuerySQL .= TV($TableVar)[substr($mw[$wj+1],0,1)]; }
			else { $QuerySQL .= TV($TableVar)[substr($mw[$wj+1],1,1)]; }
			$QuerySQL .= "\n WHERE " . $mw[$wj+1]  . " AND NOT EXISTS" .
			"\n(SELECT * \n FROM  "	. TV($TableVar)[substr($mw[0],0,1)] .
			"\n WHERE ";
			for ($i = 0; $i <= $wj; $i++) 
   				{ 
					If ($i < $wj) { $QuerySQL .= $mw[$i] . " AND "; }
					else { $QuerySQL .= $mw[$i] . " ))"; }
   				}
			$sql = $QuerySQL; 
		}
		$QuerySQL = $sql;
        echo $QuerySQL;
?>