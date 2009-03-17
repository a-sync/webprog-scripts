<?PHP

if(!class_exists('googleChart')) {
	class GoogleChart {
		private $chart,$temp;
		
		// Attributes
		public $chartTitle = array('My Chart');
		public $chartSize = '200x125';
		public $chartType = 'lc';
		
		public $chartAxisLabels = array();
		public $chartAxisPositions = array();
		public $chartAxisType = 'x,y';
		public $chartAxisRanges = array();
		
		public $dataSetColors = array('red','00FF00','0000FF');
		public $dataSetEncoding = 'extended';
		public $dataSetLegend = array('one','two','three');
		
		private $dataSets = array();
		
		public $yAxisMin = null;
		public $yAxisMax = null;
		
		// Constants
		private $googleChartUrl = 'http://chart.apis.google.com/chart';
		private $chartParams = array(
			'chd' => 'dataSets',

			'chtt' => 'chartTitle',
			'chs' => 'chartSize',
			'cht' => 'chartType',
			
			'chxp' => 'chartAxisPositions',
			'chxl' => 'chartAxisLabels',
			'chxt' => 'chartAxisType',
			'chxr' => 'chartAxisRanges',
			
			'chco' => 'dataSetColors',
			'chdl' => 'dataSetLegend'
			);
		
		/*==================================================================
			CRUD Functionality
			==================================================================*/
		
		/*----------------------------------------------------------- constructor
			
			*/
		public function __construct() {
		}
		
		/*----------------------------------------------------------- getImg
			
			*/
		public function getImg($echo=false) {
			$return = '<img src="' . $this->googleChartUrl . '?';
			$return .= $this->getQueryString();
			$return .= '"/>';
			if($echo) echo $return;
			else return $return;
		}
		
		/*----------------------------------------------------------- getQueryString
			
			*/
		public function getQueryString() {
			foreach($this->chartParams as $key => $alias) {
				eval("\$value = \$this->{$alias};");
				switch($alias) {
					
					case 'chartTitle' :
					case 'dataSetLegend' :
						$value = $this->prepChartParam($value,'|');
						break;
					
					case 'dataSetColors' :
						foreach($value as &$item) $item = $this->colorHex($item);
						$value = $this->prepChartParam($value,',');
						break;
					
					case 'chartAxisLabels' :
						$value = $this->prepChartParam($value,'|',':');
						break;
					
					case 'chartAxisRanges' :
						$Axis = explode(',',$this->chartAxisType);
						$index=-1;
						while(++$index<count($Axis)) {
							if(!isset($value[$index]) && !isset($this->chartAxisLabels[$index])) {
								switch($Axis[$index]) {
									case 'x' :
									case 't' :
										$value[$index] = '0,' . count($this->dataSets[$index]);
										break;
										
									case 'y' :
									case 'r' :
										$value[$index] = $this->temp['rangeMin'] . ',' . $this->temp['rangeMax'];
										break;
								}
							}
						}
						$value = $this->prepChartParam($value,'|',',');
						break;
						
					case 'chartAxisPositions' :
						$value = $this->prepChartParam($value,'|',',');
						break;
						
					case 'dataSets' :
						if(is_array($value)) {
							$value = $this->normalizeDataSets($value);
							$value = $this->encodeDataSets($value);
							switch($this->dataSetEncoding) {
								case 'simple' : 
									$value = 's:' . $this->prepChartParam($value,',');
									break;
									
								case 'text' : 
									$value = 't:' . $this->prepChartParam($value,'|');
									break;
									
								case 'extended' :
									$value = 'e:' . $this->prepChartParam($value,',');
							}
						}
						break;
					
					default:
						// do nothing
						break;
				}
				eval("\$return['{$key}'] = \$value;");
			}
			return http_build_query($return);
		}
		
		/*----------------------------------------------------------- addParams
			
		public function addParams($params,$merge=true) {
			foreach($params as $alias => $value) {
				if(array_key_exists($alias,$this->chartParams)) {
					if($merge) {
						
					}
					else eval(
				}
			}
		} */
		
		/*----------------------------------------------------------- addData
			
			*/
		public function addData($values,$offset=null) {
			if(!isset($offset)) $offset = count($this->dataSets);
			if($offset===false) $this->dataSets = $values;
			else {
				if(!is_array($values[key($values)])) $values = array($values);
				foreach($values as $index => $valueSet) {
					$this->dataSets[$index+$offset] = $valueSet;
				}
			}
		}
		
		/*==================================================================
			Public Functionality
			==================================================================*/
		
		/*------------------------------------------------------------- addMonthAxis
			Adds a relative month axis as specified by $minDate and $maxDate.
			*/
		public function addMonthAxis($minDate,$maxDate,$pos=null) {
			
			// Convert dates to timestamps if necessary
			if(($date=strtotime($minDate))!==false) $minDate = $date;
			if(($date=strtotime($maxDate))!==false) $maxDate = $date;
			
			$chxp = $chxl = '';
			if(!isset($pos)) $pos = max(count($this->chartAxisLabels),count($this->chartAxisPositions));
			
			// TODO: Dynamically calculate res based on number of months.
			$res = 3;
			$maxPos = datediff('d',$minDate,$maxDate,true);
			$monthIndex = ((date('d',$minDate) > 1) ? date('m',$minDate) + 1 : date('m',$minDate)) - $res;
			$year = date('Y',$minDate);
			while(($date = mktime(0,0,0,$monthIndex+=$res,1,$year)) <= $maxDate) {
				$relPos = floor(datediff('d',$minDate,$date,true) / $maxPos * 100);
				$chxp .= (strlen($chxp)?',':'') . $relPos;
				$chxl .= '|' . date('M',$date);
			}
			
			$this->chartAxisLabels[$pos] = $chxl;
			$this->chartAxisPositions[$pos] = $chxp;
		}
		
		/*==================================================================
			Protected Functionality
			==================================================================*/
		
		/*----------------------------------------------------------- prepChartParam
			
			*/
		private function prepChartParam($data,$setDelimiter,$indexDelimiter=null) {
			if(is_array($data)) {
				$return = '';
				foreach($data as $index => $value) {
					$return .= (strlen($return)?$setDelimiter:'') . (isset($indexDelimiter)?$index . $indexDelimiter:'') . $value;
				}
			}
			else $return = $data;
			
			return $return;
		}
			
		/*----------------------------------------------------------- encodeDataSets
			
			*/
		private function encodeDataSets($dataSets) {
			$return = array();
			foreach($dataSets as $dataSet) {
				switch($this->dataSetEncoding) {
					case 'simple': $return[] = $this->encodeSimple($dataSet); break;
					case 'text': $return[] = $this->encodeText($dataSet); break;
					default : $return[] = $this->encodeExtended($dataSet); break;
				}
			}
			return $return;
		}
		
		/*----------------------------------------------------------- encodeSimple
			
			*/
		private function encodeSimple($values,$max=61,$min=0) {
			$simpleTable = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
			$return = '';
			foreach($values as $value) {
				if($value >= $min && $value <= $max) $return .= substr($simpleTable,$$value,1);
				else $return .= '_';
			}
			return $return;
		}
		
		/*----------------------------------------------------------- encodeText
			
			*/
		private function encodeText($values,$max=100,$min=0) {
			$return = '';
			foreach($values as $value) {
				if(strlen($return)) $return .= ',';
				if($value >= $min && $value <= $max) $return .= sprintf("%.1f",$value);
				else $return .= '-1';
			}
			return $return;
		}
		
		/*----------------------------------------------------------- encodeExtended
			
			*/
		private function encodeExtended($values, $max = 4095, $min = 0) {
			$extended_table = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-.';
			$chardata = ''; //'e:';
			$delta = $max - $min;
			$size = (strlen($extended_table));
			foreach($values as $k => $v) {
				if($v >= 0 && $v <= 4095) {
					$first = substr($extended_table, floor(($v - $min) / $size * $max / $delta),1);
					$second = substr($extended_table, ((($v - $min) % $size) * $max / $delta), 1);
					$chardata .= "$first$second";
				} else {
					$chardata .= '__'; // Value out of max range;
				}
			}
			return($chardata);
		}
		
		/*------------------------------------------------------------- normalizeDataSets
			Normalizes a set of $dataSets.
			*/
		private function normalizeDataSets($dataSets,$normalMin=null,$normalMax=null) {
			
			// Determine normal limits
			if(!isset($normalMax)) switch($this->dataSetEncoding) {
				default: $normalMax = 4095; break;
			}
			$normalDelta = $normalMax - $normalMin;
						
			// Determine value limits
			if(isset($this->temp['rangeMin'])) unset($this->temp['rangeMin']);
			if(isset($this->temp['rangeMax'])) unset($this->temp['rangeMax']);
			if(!isset($this->yAxisMin) || !isset($this->yAxisMax)) {
				foreach($dataSets as $dataSet) {
					if(!isset($this->temp['rangeMin'])) $this->temp['rangeMin'] = min($dataSet);
					elseif(($min = min($dataSet)) < $this->temp['rangeMin']) $this->temp['rangeMin'] = $min;
					if(!isset($this->temp['rangeMax'])) $this->temp['rangeMax'] = max($dataSet);
					elseif(($max = max($dataSet)) > $this->temp['rangeMax']) $this->temp['rangeMax'] = $max;
				}
			}
			$this->temp['rangeMin'] = isset($this->yAxisMin) ? $this->yAxisMin : $this->temp['rangeMin'];
			$this->temp['rangeMax'] = isset($this->yAxisMax) ? $this->yAxisMax : $this->temp['rangeMax'];
			$rangeDelta = $this->temp['rangeMax'] - $this->temp['rangeMin'];
			
			foreach($dataSets as $dataSet) {
				$arr = array();
				foreach($dataSet as $value) {
					$normalValue = ($value - $this->temp['rangeMin']) / $rangeDelta;
					$arr[] = $normalDelta * $normalValue + $normalMin;
				}
				$return[] = $arr;
			}

			return $return;
		}
		
		/*----------------------------------------------------------- colorHex
			
			*/
		function colorHex($alias) {
			$colors = array(
				'ALICEBLUE' => 'F0F8FF',
				'ANTIQUEWHITE' => 'FAEBD7',
				'AQUA' => '00FFFF',
				'AQUAMARINE' => '7FFFD4',
				'AZURE' => 'F0FFFF',
				'BEIGE' => 'F5F5DC',
				'BISQUE' => 'FFE4C4',
				'BLACK' => '000000',
				'BLANCHEDALMOND' => 'FFEBCD',
				'BLUE' => '0000FF',
				'BLUEVIOLET' => '8A2BE2',
				'BROWN' => 'A52A2A',
				'BURLYWOOD' => 'DEB887',
				'CADETBLUE' => '5F9EA0',
				'CHARTREUSE' => '7FFF00',
				'CHOCOLATE' => 'D2691E',
				'CORAL' => 'FF7F50',
				'CORNFLOWERBLUE' => '6495ED',
				'CORNSILK' => 'FFF8DC',
				'CRIMSON' => 'DC143C',
				'CYAN' => '00FFFF',
				'DARKBLUE' => '00008B',
				'DARKCYAN' => '008B8B',
				'DARKGOLDENROD' => 'B8860B',
				'DARKGRAY' => 'A9A9A9',
				'DARKGREY' => 'A9A9A9',
				'DARKGREEN' => '006400',
				'DARKKHAKI' => 'BDB76B',
				'DARKMAGENTA' => '8B008B',
				'DARKOLIVEGREEN' => '556B2F',
				'DARKORANGE' => 'FF8C00',
				'DARKORCHID' => '9932CC',
				'DARKRED' => '8B0000',
				'DARKSALMON' => 'E9967A',
				'DARKSEAGREEN' => '8FBC8F',
				'DARKSLATEBLUE' => '483D8B',
				'DARKSLATEGRAY' => '2F4F4F',
				'DARKSLATEGREY' => '2F4F4F',
				'DARKTURQUOISE' => '00CED1',
				'DARKVIOLET' => '9400D3',
				'DEEPPINK' => 'FF1493',
				'DEEPSKYBLUE' => '00BFFF',
				'DIMGRAY' => '696969',
				'DIMGREY' => '696969',
				'DODGERBLUE' => '1E90FF',
				'FIREBRICK' => 'B22222',
				'FLORALWHITE' => 'FFFAF0',
				'FORESTGREEN' => '228B22',
				'FUCHSIA' => 'FF00FF',
				'GAINSBORO' => 'DCDCDC',
				'GHOSTWHITE' => 'F8F8FF',
				'GOLD' => 'FFD700',
				'GOLDENROD' => 'DAA520',
				'GRAY' => '808080',
				'GREY' => '808080',
				'GREEN' => '008000',
				'GREENYELLOW' => 'ADFF2F',
				'HONEYDEW' => 'F0FFF0',
				'HOTPINK' => 'FF69B4',
				'INDIANRED' => 'CD5C5C',
				'INDIGO' => '4B0082',
				'IVORY' => 'FFFFF0',
				'KHAKI' => 'F0E68C',
				'LAVENDER' => 'E6E6FA',
				'LAVENDERBLUSH' => 'FFF0F5',
				'LAWNGREEN' => '7CFC00',
				'LEMONCHIFFON' => 'FFFACD',
				'LIGHTBLUE' => 'ADD8E6',
				'LIGHTCORAL' => 'F08080',
				'LIGHTCYAN' => 'E0FFFF',
				'LIGHTGOLDENRODYELLOW' => 'FAFAD2',
				'LIGHTGRAY' => 'D3D3D3',
				'LIGHTGREY' => 'D3D3D3',
				'LIGHTGREEN' => '90EE90',
				'LIGHTPINK' => 'FFB6C1',
				'LIGHTSALMON' => 'FFA07A',
				'LIGHTSEAGREEN' => '20B2AA',
				'LIGHTSKYBLUE' => '87CEFA',
				'LIGHTSLATEGRAY' => '778899',
				'LIGHTSLATEGREY' => '778899',
				'LIGHTSTEELBLUE' => 'B0C4DE',
				'LIGHTYELLOW' => 'FFFFE0',
				'LIME' => '00FF00',
				'LIMEGREEN' => '32CD32',
				'LINEN' => 'FAF0E6',
				'MAGENTA' => 'FF00FF',
				'MAROON' => '800000',
				'MEDIUMAQUAMARINE' => '66CDAA',
				'MEDIUMBLUE' => '0000CD',
				'MEDIUMORCHID' => 'BA55D3',
				'MEDIUMPURPLE' => '9370D8',
				'MEDIUMSEAGREEN' => '3CB371',
				'MEDIUMSLATEBLUE' => '7B68EE',
				'MEDIUMSPRINGGREEN' => '00FA9A',
				'MEDIUMTURQUOISE' => '48D1CC',
				'MEDIUMVIOLETRED' => 'C71585',
				'MIDNIGHTBLUE' => '191970',
				'MINTCREAM' => 'F5FFFA',
				'MISTYROSE' => 'FFE4E1',
				'MOCCASIN' => 'FFE4B5',
				'NAVAJOWHITE' => 'FFDEAD',
				'NAVY' => '000080',
				'OLDLACE' => 'FDF5E6',
				'OLIVE' => '808000',
				'OLIVEDRAB' => '6B8E23',
				'ORANGE' => 'FFA500',
				'ORANGERED' => 'FF4500',
				'ORCHID' => 'DA70D6',
				'PALEGOLDENROD' => 'EEE8AA',
				'PALEGREEN' => '98FB98',
				'PALETURQUOISE' => 'AFEEEE',
				'PALEVIOLETRED' => 'D87093',
				'PAPAYAWHIP' => 'FFEFD5',
				'PEACHPUFF' => 'FFDAB9',
				'PERU' => 'CD853F',
				'PINK' => 'FFC0CB',
				'PLUM' => 'DDA0DD',
				'POWDERBLUE' => 'B0E0E6',
				'PURPLE' => '800080',
				'RED' => 'FF0000',
				'ROSYBROWN' => 'BC8F8F',
				'ROYALBLUE' => '4169E1',
				'SADDLEBROWN' => '8B4513',
				'SALMON' => 'FA8072',
				'SANDYBROWN' => 'F4A460',
				'SEAGREEN' => '2E8B57',
				'SEASHELL' => 'FFF5EE',
				'SIENNA' => 'A0522D',
				'SILVER' => 'C0C0C0',
				'SKYBLUE' => '87CEEB',
				'SLATEBLUE' => '6A5ACD',
				'SLATEGRAY' => '708090',
				'SLATEGREY' => '708090',
				'SNOW' => 'FFFAFA',
				'SPRINGGREEN' => '00FF7F',
				'STEELBLUE' => '4682B4',
				'TAN' => 'D2B48C',
				'TEAL' => '008080',
				'THISTLE' => 'D8BFD8',
				'TOMATO' => 'FF6347',
				'TURQUOISE' => '40E0D0',
				'VIOLET' => 'EE82EE',
				'WHEAT' => 'F5DEB3',
				'WHITE' => 'FFFFFF',
				'WHITESMOKE' => 'F5F5F5',
				'YELLOW' => 'FFFF00',
				'YELLOWGREEN' => '9ACD32'
				);
			return empty($colors[strtoupper($alias)]) ? $alias : $colors[strtoupper($alias)];
		}
	} // class googleChart
} // if(!class_exists('googleChart'))

?>
