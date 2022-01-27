<?php
    $S_NameQuery = $_POST['S_NameQuery'];        // Выбранное имя запроса из списка !
    $DescQuery = $_POST['descquery'];            // Описание запроса - текст с экрана !
    $TableVar = $_POST['typevar'];               // Типы переменных - текст с экрана !
    $GoalList = $_POST['targetlist'];            // Целевой список - текст с экрана !
    $QueryBody = $_POST['algquery'];             // Тело запроса - текст с экрана !
    $QuerySQL = $_POST['query'];                 // Тело SQL - текст с экрана !
        
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


function TPL($qb, $z)
{
// функция TPL обрабатывает строку QueryBody ($qb), и в зависимости от параметра $z выдает
// $z=1 массив элементов, разделенных "[", в которых FORALL=>NOT EXISTS, IMPLY=>AND NOT.
// $z=2 массив переменных, которых нет в целевом списке !

	$mw = []; $m_qb2 = []; $m_qb3 = [];
	$w = substr_count($qb, '[', 0);				// Количество открывающих операционных скобок ([):
	$mw = explode('[', $qb);					// Преобразуем строку QueryBody в массив $mw по скобке '[' :
	$j = 0; 
	foreach ($mw as $x){						// $x => string 
		$x = trim($x); $x = trim($x, ']');
		$x = str_replace ( 'FORALL' , 'NOT EXISTS' , $x );
		$x = str_replace ( 'IMPLY' , 'AND NOT' , $x );
		If ($j < $w) { $m_qb1[$j] = substr($x, 0, -1); }
		else { $m_qb1[$j] = $x; }
		If ($j < $w) {$m_qb2[$j] = substr($x, -1);}
		$j++; }

	If ($z == 1) {return $m_qb1;}				// TPL($qb,1)
	If ($z == 2) {return $m_qb2;}				// TPL($qb,2)
}

    $mV = []; $mW = [];
	$QueryBody = trim ($QueryBody);
	$s = preg_replace('/\s+/', ' ', $QueryBody);			// Поиск и замена по регулярному выражению !
    $mW = TPL($s, 1);  $mV = TPL($s, 2); $w = count ( $mW ) - 1; $wG = count ( GL($GoalList,0) ) - 1;
	$QuerySQL = '';
	$QuerySQL .= "SELECT " . $GoalList . "\n FROM  ";
	for ($i = 0; $i <= $wG; $i++) { 
		If ($i < $wG) { $QuerySQL .= TV($TableVar)[GL($GoalList,0)[$i]] . ", "; }
		else { $QuerySQL .= TV($TableVar)[GL($GoalList,0)[$i]]; }
	}
	for ($i = 0; $i <= $w; $i++) { 
		If ($i < $w) { $QuerySQL .= "\n WHERE " . $mW[$i] . "\n(SELECT * \n FROM  " . TV($TableVar)[$mV[$i]]; }
		else { $QuerySQL .= "\n WHERE " . $mW[$i]; }
	}
	$QuerySQL .= str_repeat(")", $w);
	echo $QuerySQL;
?>