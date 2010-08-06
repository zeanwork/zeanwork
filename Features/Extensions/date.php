<?php
/**
 * Contem a classe Date
 * 
 * Zeanwork Framework PHP <http://www.zeanwork.com.br>
 * Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * 
 * Licenciado sob a licença MIT
 * Redistribuições de arquivos e/ou partes de códigos devem manter o aviso de copyright acima.
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 * @since			Zeanwork v 0.1.0
 * @version 		$LastChangedRevision: 153 $
 * @lastModified	$LastChangedDate: 2010-04-07 21:04:50 -0300 (Qua, 07 Abr 2010) $
 * @copyright		Copyright 2009-2010, Zeanwork Framework <http://www.zeanwork.com.br>
 * @license 		http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/**
 * Manipulação de datas, converções, operações ( +, - e diferença entre duas datas)...
 * 
 * @author			Josemar Davi Luedke <josemarluedke@gmail.com>
 * @package			Zeanwork
 * @subpackage		Zeanwork.Zeanwork.Libs
 */
class Date extends Zeanwork {
	
	/**
	 * Expreções regurares para datas, keys: [dd/mm/aaaa, dd-mm-aaaa, aaaa-mm-dd, H:i:s e H:i]
	 * @var
	 */
	public static $regs = array(
							  'dd/mm/aaaa' 	=> '((0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/[12][0-9]{3})' 			  /* Format Brazil dd/mm/aaaa */
							, 'dd-mm-aaaa' 	=> '((0[1-9]|[12][0-9]|3[01])\-(0[1-9]|1[012])\-[12][0-9]{3})' 			  /* Format Brazil dd-mm-aaaa*/
							, 'aaaa-mm-dd' 	=> '([12][0-9]{3}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01]))' 			  /* Format EUA aaaa-mm-dd*/
							, 'H:i:s'		=> '(0[0-9]|1[0-9]|2[0-4])\:(0[0-9]|[12345][0-9])\:(0[0-9]|[12345][0-9])' /* Format H:i:s */
							, 'H:i'			=> '(0[0-9]|1[0-9]|2[0-4])\:(0[0-9]|[12345][0-9])' 						  /* Format H:i */
							, '00/00/0000'	=> '((0[0])\/(0[0])\/(0[0]{3}))'
							, '00-00-0000'	=> '((0[0])\-(0[0])\-(0[0]{3}))'
							, '0000-00-00'	=> '((0[0]{3})\-(0[0])\-(0[0]))'
						);
	/**
	 * Formatos de datas para converção
	 * @var
	 */
	private static $format = array(
								  'br' 					=> '%s/%s/%s'			/* 02/10/2009 */
								, 'dd/mm/aaaa' 			=> '%s/%s/%s'			/* 02/10/2009 */
								, 'dd/mm/aa' 			=> '%s/%s/%s'			/* 02/10/09 */
								, 'dd-mm-aaaa' 			=> '%s-%s-%s'			/* 02-10-2009 */
								, 'dd-mm-aa' 			=> '%s-%s-%s'			/* 02-10-09 */
								, 'eua' 				=> '%s-%s-%s'			/* 2009-10-02 */
								, 'date' 				=> '%s-%s-%s'			/* 2009-10-02 */
								, 'aaaa-mm-dd' 			=> '%s-%s-%s'			/* 2009-10-02 */
								, 'aaaa/mm/dd' 			=> '%s/%s/%s'			/* 2009/10/02 */
								, 'dd/mm/aaaa H:i:s' 	=> '%s/%s/%s %s:%s:%s'	/* 02/10/2009 09:05:20 */
								, 'dd/mm/aaaa H:i' 		=> '%s/%s/%s %s:%s'		/* 02/10/2009 09:05 */
								, 'brtime' 				=> '%s/%s/%s %sh %smin'	/* 02/10/2009 09h 05min */
								, 'dd/mm/aaaa Hh imin' 	=> '%s/%s/%s %sh %smin'	/* 02/10/2009 09h 05min */
								, 'dd/mm/aaaa H:ih' 	=> '%s/%s/%s %s:%sh'	/* 02/10/2009 09:05h */
								, 'dd-mm-aaaa H:i:s' 	=> '%s-%s-%s %s:%s:%s'	/* 02-10-2009 09:05:20 */
								, 'dd-mm-aaaa H:i' 		=> '%s-%s-%s %s:%s'		/* 02-10-2009 09:05 */
								, 'dd-mm-aaaa Hh imin' 	=> '%s-%s-%s %sh %smin'	/* 02-10-2009 09h 05min */
								, 'dd-mm-aaaa H:ih' 	=> '%s-%s-%s %s:%sh'	/* 02-10-2009 09:05h */
								, 'datetime' 			=> '%s-%s-%s %s:%s:%s'	/* 2009-10-02 09:05:20 */
								, 'aaaa-mm-dd H:i:s' 	=> '%s-%s-%s %s:%s:%s'	/* 2009-10-02 09:05:20 */
								, 'aaaa-mm-dd H:i' 		=> '%s-%s-%s %s:%s'		/* 2009-10-02 09:05 */
								, 'aaaa-mm-dd Hh imin' 	=> '%s-%s-%s %sh %smin' /* 2009-10-02 09h 05min */
								, 'aaaa-mm-dd H:ih' 	=> '%s-%s-%s %s:%sh'	/* 2009-10-02 09:05h */
								, 'H:i:s'				=> '%s:%s:%s'			/* 09:05:20 */
								, 'H:i'					=> '%s:%s'				/* 09:05 */
								, 'Hh imin'				=> '%sh %smin'			/* 09h 05min */
								, 'H:ih'				=> '%s:%sh'				/* 09:05h */
							);
	
	/**
	 * Converter datas e horarios, Obs: Não concidera como dia, mês e ano, quando toda a data for zero e no formato aaaa-mm-dd H:i:s ou somente a data sem o horário então retornará $isNull
	 * @param string $date, Date or Time, Formats [dd/mm/aaaa, dd-mm-aaaa, aaaa-mm-dd, dd/mm/aaaa H:i:s, dd/mm/aaaa H:i, dd-mm-aaaa H:i:s, dd-mm-aaaa H:i, aaaa-mm-dd H:i:s, aaaa-mm-dd H:i, H:i:s, H:i]
	 * @param string $format [optional]
	 * @param string $isNull [optional]
	 * @return string
	 */
	public static function convert($date, $format = false, $isNull = '--'){
		$calledFrom = debug_backtrace();
		$dateType = null;
		$day = null;
		$month = null;
		$year = null;
		$hour = null;
		$minutes = null;
		$seconds = null;
		$output = null;
		
		if($format === false) $format = self::getDateFormat();
		
		/* Format Brazil dd/mm/aaaa */
		if(preg_match('/^'.self::$regs['dd/mm/aaaa'].'$/', $date)){
			$dateArr = explode('/', $date);
			$day = $dateArr[0];
			$month = $dateArr[1];
			$year = $dateArr[2];
			
			$dateType =  'dd/mm/aaaa';
		
		/* Format Brazil dd-mm-aaaa */
		}elseif(preg_match('/^'.self::$regs['dd-mm-aaaa'].'$/', $date)){
			$dateArr = explode('-', $date);
			$day = $dateArr[0];
			$month = $dateArr[1];
			$year = $dateArr[2];
			
			$dateType =  'dd-mm-aaaa';
		
		/* Format EUA aaaa-mm-dd */
		}elseif(preg_match('/^'.self::$regs['aaaa-mm-dd'].'$/', $date)){
			$dateArr = explode('-', $date);
			$day = $dateArr[2];
			$month = $dateArr[1];
			$year = $dateArr[0];
			
			$dateType =  'aaaa-mm-dd';
		
		/* Format Brazil dd/mm/aaaa H:i:s */
		}elseif(preg_match('/^'.self::$regs['dd/mm/aaaa'].'\s'.self::$regs['H:i:s'].'|'.self::$regs['00/00/0000'].'\s'.self::$regs['H:i:s'].'$/', $date)){
			$dateArr = explode('/', $date);
			$day = $dateArr[0];
			$month = $dateArr[1];
			$year = substr($dateArr[2], 0, 4);
			$hour = substr($dateArr[2], 5, 2);
			$minutes = substr($dateArr[2], 8, 2);
			$seconds = substr($dateArr[2], 11, 2);
			
			$dateType =  'dd/mm/aaaa H:i:s';
		
		/* Format Brazil dd/mm/aaaa H:i */
		}elseif(preg_match('/^'.self::$regs['dd/mm/aaaa'].'\s'.self::$regs['H:i'].'|'.self::$regs['00/00/0000'].'\s'.self::$regs['H:i'].'$/', $date)){
			$dateArr = explode('/', $date);
			$day = $dateArr[0];
			$month = $dateArr[1];
			$year = substr($dateArr[2], 0, 4);
			$hour = substr($dateArr[2], 5, 2);
			$minutes = substr($dateArr[2], 8, 2);

			$dateType =  'dd/mm/aaaa H:i';
		
		/* Format Brazil dd-mm-aaaa H:i:s */
		}elseif(preg_match('/^'.self::$regs['dd-mm-aaaa'].'\s'.self::$regs['H:i:s'].'|'.self::$regs['00-00-0000'].'\s'.self::$regs['H:i:s'].'$/', $date)){
			$dateArr = explode('-', $date);
			$day = $dateArr[0];
			$month = $dateArr[1];
			$year = substr($dateArr[2], 0, 4);
			$hour = substr($dateArr[2], 5, 2);
			$minutes = substr($dateArr[2], 8, 2);
			$seconds = substr($dateArr[2], 11, 2);
			
			$dateType =  'dd-mm-aaaa H:i:s';
		
		/* Format Brazil dd-mm-aaaa H:i */
		}elseif(preg_match('/^'.self::$regs['dd-mm-aaaa'].'\s'.self::$regs['H:i'].'|'.self::$regs['00-00-0000'].'\s'.self::$regs['H:i'].'$/', $date)){
			$dateArr = explode('-', $date);
			$day = $dateArr[0];
			$month = $dateArr[1];
			$year = substr($dateArr[2], 0, 4);
			$hour = substr($dateArr[2], 5, 2);
			$minutes = substr($dateArr[2], 8, 2);
			
			$dateType =  'dd-mm-aaaa H:i';
		
		/* Format EUA aaaa-mm-dd H:i:s */
		}elseif(preg_match('/^'.self::$regs['aaaa-mm-dd'].'\s'.self::$regs['H:i:s'].'|'.self::$regs['0000-00-00'].'\s'.self::$regs['H:i:s'].'$/', $date)){
			$dateArr = explode('-', $date);
			$day = substr($dateArr[2], 0, 2);
			$month = $dateArr[1];
			$year = $dateArr[0];
			$hour = substr($dateArr[2], 3, 2);
			$minutes = substr($dateArr[2], 6, 2);
			$seconds = substr($dateArr[2], 9, 2);
			
			$dateType =  'aaaa-mm-dd H:i:s';
		
		/* Format EUA aaaa-mm-dd H:i */ 
		}elseif(preg_match('/^'.self::$regs['aaaa-mm-dd'].'\s'.self::$regs['H:i'].'|'.self::$regs['0000-00-00'].'\s'.self::$regs['H:i'].'$/', $date)){
			$dateArr = explode('-', $date);
			$day = substr($dateArr[2], 0, 2);
			$month = $dateArr[1];
			$year = $dateArr[0];
			$hour = substr($dateArr[2], 3, 2);
			$minutes = substr($dateArr[2], 6, 2);
			
			$dateType =  'aaaa-mm-dd H:i';
			
		/* Format H:i:s */
		}elseif(preg_match('/^'.self::$regs['H:i:s'].'$/', $date)){
			$dateArr = explode(':', $date);
			$hour = $dateArr[0];
			$minutes = $dateArr[1];
			$seconds = $dateArr[2];
			
			$dateType =  'H:i:s';
			
		/* Format H:i */
		}elseif(preg_match('/^'.self::$regs['H:i'].'$/', $date)){
			$dateArr = explode(':', $date);
			$hour = $dateArr[0];
			$minutes = $dateArr[1];
			
			$dateType =  'H:i';
		
		/* Date null or 0000-00-00.... */
		}elseif(
				   ($date == '0000-00-00')
				|| ($date == '00/00/0000')
				|| ($date == '00-00-0000')
				|| ($date == '')
				|| ($date == false)
				|| ($date == null)
				){
			return $isNull;
			
			$dateType =  'Date null or 0000-00-00....';
		}else{
			echo Debugger::errorTrigger(E_NOTICE, 'N&atilde;o foi possivel encontrar o formato da data informada', $calledFrom[0]['file'], $calledFrom[0]['line']);
			return false;
		}
		
		if(!array_key_exists($format, self::$format)){
			echo Debugger::errorTrigger(E_NOTICE, 'N&atilde;o foi possivel encontrar o formato de data para conver&ccedil;&atilde;o solicitada ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
			return false;
		}
		
		if($dateType == 'aaaa-mm-dd H:i:s'){
			if(($day === '00') && ($month === '00') && ($year === '0000') && ($hour === '00') && ($minutes === '00') && ($seconds === '00')){
				return $isNull;
			}
		}
		
		switch($format){
			case 'br':
			case 'dd/mm/aaaa':
				if(($day !== null) && ($month !== null) && ($year !== null)){
					$output = sprintf(self::$format['dd/mm/aaaa'], $day, $month, $year);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado [dd/mm/aaaa]', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'dd/mm/aa':
				if(($day !== null) && ($month !== null) && ($year !== null)){
					$output = sprintf(self::$format['dd/mm/aa'], $day, $month, substr($year, 2, 4));
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'dd-mm-aaaa':
				if(($day !== null) && ($month !== null) && ($year !== null)){
					$output = sprintf(self::$format['dd-mm-aaaa'], $day, $month, $year);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'dd-mm-aa':
				if(($day !== null) && ($month !== null) && ($year !== null)){
					$output = sprintf(self::$format['dd-mm-aa'], $day, $month, substr($year, 2, 4));
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'eua':
			case 'date':
			case 'aaaa-mm-dd':
				if(($day !== null) && ($month !== null) && ($year !== null)){
					$output = sprintf(self::$format['aaaa-mm-dd'], $year, $month, $day);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado [aaaa-mm-dd]', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'aaaa/mm/dd':
				if(($day !== null) && ($month !== null) && ($year !== null)){
					$output = sprintf(self::$format['aaaa/mm/dd'], $year, $month, $day);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado [aaaa-mm-dd]', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'dd/mm/aaaa H:i:s':
				if(($day !== null) && ($month !== null) && ($year !== null) && ($hour !== null) && ($minutes !== null) && ($seconds !== null)){
					$output = sprintf(self::$format['dd/mm/aaaa H:i:s'], $day, $month, $year, $hour, $minutes, $seconds);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'dd/mm/aaaa H:i':
				if(($day !== null) && ($month !== null) && ($year !== null) && ($hour !== null) && ($minutes !== null)){
					$output = sprintf(self::$format['dd/mm/aaaa H:i'], $day, $month, $year, $hour, $minutes);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'brtime':
			case 'dd/mm/aaaa Hh imin':
				if(($day !== null) && ($month !== null) && ($year !== null) && ($hour !== null) && ($minutes !== null)){
					$output = sprintf(self::$format['dd/mm/aaaa Hh imin'], $day, $month, $year, $hour, $minutes);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado [dd/mm/aaaa Hh imin]', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'dd/mm/aaaa H:ih':
				if(($day !== null) && ($month !== null) && ($year !== null) && ($hour !== null) && ($minutes !== null)){
					$output = sprintf(self::$format['dd/mm/aaaa H:ih'], $day, $month, $year, $hour, $minutes);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'dd-mm-aaaa H:i:s':
				if(($day !== null) && ($month !== null) && ($year !== null) && ($hour !== null) && ($minutes !== null) && ($seconds !== null)){
					$output = sprintf(self::$format['dd-mm-aaaa H:i:s'], $day, $month, $year, $hour, $minutes, $seconds);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'dd-mm-aaaa H:i':
				if(($day !== null) && ($month !== null) && ($year !== null) && ($hour !== null) && ($minutes !== null)){
					$output = sprintf(self::$format['dd-mm-aaaa H:i'], $day, $month, $year, $hour, $minutes);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'dd-mm-aaaa Hh imin':
				if(($day !== null) && ($month !== null) && ($year !== null) && ($hour !== null) && ($minutes !== null)){
					$output = sprintf(self::$format['dd-mm-aaaa Hh imin'], $day, $month, $year, $hour, $minutes);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'dd-mm-aaaa H:ih':
				if(($day !== null) && ($month !== null) && ($year !== null) && ($hour !== null) && ($minutes !== null)){
					$output = sprintf(self::$format['dd-mm-aaaa H:ih'], $day, $month, $year, $hour, $minutes);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'datetime':
			case 'aaaa-mm-dd H:i:s':
				if(($day !== null) && ($month !== null) && ($year !== null) && ($hour !== null) && ($minutes !== null) && ($seconds !== null)){
					$output = sprintf(self::$format['aaaa-mm-dd H:i:s'], $year, $month, $day, $hour, $minutes, $seconds);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado [aaaa-mm-dd H:i:s]', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'aaaa-mm-dd H:i':
				if(($day !== null) && ($month !== null) && ($year !== null) && ($hour !== null) && ($minutes !== null)){
					$output = sprintf(self::$format['aaaa-mm-dd H:i'], $year, $month, $day, $hour, $minutes);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'aaaa-mm-dd Hh imin':
				if(($day !== null) && ($month !== null) && ($year !== null) && ($hour !== null) && ($minutes !== null)){
					$output = sprintf(self::$format['aaaa-mm-dd Hh imin'], $year, $month, $day, $hour, $minutes);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'aaaa-mm-dd H:ih':
				if(($day !== null) && ($month !== null) && ($year !== null) && ($hour !== null) && ($minutes !== null)){
					$output = sprintf(self::$format['aaaa-mm-dd H:ih'], $year, $month, $day, $hour, $minutes);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'H:i:s':
				if(($hour !== null) && ($minutes !== null) && ($seconds !== null)){
					$output = sprintf(self::$format['H:i:s'], $hour, $minutes, $seconds);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'H:i':
				if(($hour !== null) && ($minutes !== null)){
					$output = sprintf(self::$format['H:i'], $hour, $minutes);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'Hh imin':
				if(($hour !== null) && ($minutes !== null)){
					$output = sprintf(self::$format['Hh imin'], $hour, $minutes);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			case 'H:ih':
				if(($hour !== null) && ($minutes !== null)){
					$output = sprintf(self::$format['H:ih'], $hour, $minutes);
				}else{
					echo Debugger::errorTrigger(E_NOTICE, 'Dados insuficiente para realizar a conver&ccedil;&atilde;o da data, para o formato solicitado ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				}
			break;
			default:
				echo Debugger::errorTrigger(E_NOTICE, 'N&atilde;o foi possivel encontrar o formato de data para conver&ccedil;&atilde;o solicitada ['.$format.']', $calledFrom[0]['file'], $calledFrom[0]['line']);
				return false;
			break;
		}
		return $output;		
	}
	
	/**
	 * Retorna o formato default de data para converções 
	 * @return array
	 */
	public static function getDateFormat(){
		return Configure::read('dateFormat');
	}

	/**
	 * Soma tantos dias de uma data - Retorna no formato dd/mm/aaaa
	 * @param string $data
	 * @param numeric $dias
	 * @return string
	 */
	public static function sum($data, $dias){
		$data = self::convert($data, 'eua');
		
		$data_e = explode("-",$data);
		$data2 = date("m/d/Y", mktime(0,0,0,$data_e[1],$data_e[2] + $dias,$data_e[1]));
		$data2_e = explode("/",$data2);
		$data_final = $data2_e[1] . "/". $data2_e[0] . "/" . $data2_e[2];
		return $data_final;
	}
	
	/**
	 * Diminui tantos dias de uma data - Retorna no formato brasifeiro
	 * @param string $date
	 * @param numeric $dias
	 * @return string
	 */
	public static function min($date, $dias){
		$date = self::convert($date, 'eua');
		
		$date_e = explode("-",$date);
		$date2 = date("m/d/Y", mktime(0,0,0,$date_e[1],$date_e[2] - $dias,$date_e[1]));
		$date2_e = explode("/",$date2);
		$date_final = $date2_e[1] . "/". $date2_e[0] . "/" . $date2_e[2];
		return $date_final;
	}
	
	
	/**
	 * Retorna a quantia de dias de um determinado mês
	 * @param numeric $month
	 * @param numeric $year
	 * @return numeric
	 */
	public static function daysInMonth($month, $year){
		return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
	} 
	
	/**
	 * Retorna a quantia de dias de um determinado ano
	 * @param numeric $year
	 * @return numeric
	 */
	public static function daysInYear($year){
		$daysInYear = 365;
		if(date('L', mktime(0, 0, 0, 1, 1, $year))) $daysInYear = 366;
		return $daysInYear;
	}
	
	/**
	 * Retorna a diferença de dias entre duas datas.
	 *
	 * @param string $startDate
	 * @param string $endDate
	 * @return numeric
	 */
	public static function diff($startDate, $endDate){
		$startDate = self::convert($startDate, 'eua');
		$endDate = self::convert($endDate, 'eua');
	    $startArry = explode('-', $startDate);
		$endArry = explode('-', $endDate);
	    $start_date = gregoriantojd($startArry['1'], $startArry['2'], $startArry['0']);
	    $end_date = gregoriantojd($endArry['1'], $endArry['2'], $endArry['0']);
	    return round(($end_date - $start_date), 0);
	}
	
	/**
	 * Retorna o dia da semana a partir de um data ex: 19-08-2009 quarta-feira return = 4
	 * @param string $date
	 * @return numeric
	 */
	public static function dayWeeks($date){
		$date = self::convert($date, 'eua');
		
		$year =  substr($date, 0, 4);
		$month =  substr($date, 5, -3);
		$day =  substr($date, 8, 9);
		$dayWeeks = date('w', mktime(0, 0, 0,$month, $day, $year));
		return $dayWeeks + 1;
	}
	
	/**
	 * Monta uma matriz como um calendario
	 * @example
	 * ******************** *
	 * D  S  T  Q  Q  S  S  *
	 * ******************** *
	 *             1  2  3  *
	 * 4  5  6  7  8  9  10 *
	 * 11 12 13 14 15 16 17 *
	 * 18 19 20 21 22 23 24 *
	 * 25 26 27 28 29 30 31 *
	 * ******************** *
	 * 
	 * @return array $calendario
	 * @param numeric $month
	 * @param numeric $year
	 */
	public static function calendar($month, $year){
		$firstDay = mktime(0, 0, 0, $month, 1,$year);
		$numDays = date('t', $firstDay);
		$firstDayWeeks = date('w', $firstDay);
		if($firstDayWeeks > 0){
			$calendar = array(0 => array_fill(0, $firstDayWeeks, null));
		}
		$day = 1;
		$weeks = 0;
		$dayWeeks = $firstDayWeeks;
		while ($day <= $numDays) {
			if ($dayWeeks >= 7) {
			$dayWeeks = 0;
			$weeks++;
			}
			$calendar[$weeks][$dayWeeks] = $day;
			$day++;
			$dayWeeks++;
		}
		if($dayWeeks < 7){
			$calendar[$weeks] += array_fill($dayWeeks, 7 - $dayWeeks, null);
		}
		return $calendar;
	}
	
	/**
	 * Retorna a data atual ex: 24/11/2009
	 * @param string $format [optional]
	 * @return string
	 */
	public static function now($format = null){
		if($format == null){
			$format = self::getDateFormat();
		}
		
		return self::convert(date('Y-m-d H:i:s'), $format);
	}
}