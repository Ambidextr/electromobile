<?php

// функции обработки bbCode
function print_page($message)
{
	// обазрезаем слишком длинные слова
    $message = preg_replace_callback(
              "|([a-zа-¤\d!]{35,})|i",
              "split_text",
              $message);
			  
  // тэги - [code], [php], [sql]
  preg_match_all( "#\[php\](.+)\[\/php\]#isU", $message, $matches );
  $cnt = count( $matches[0] );
  for ( $i = 0; $i < $cnt; $i++ ) {
    $phpBlocks[] = '<div class="codePHP">'.highlight_string( $matches[1][$i], true ).'</div>';
    $uniqidPHP = '[php_'.uniqid('').']';
    $uniqidsPHP[] = $uniqidPHP;
    $message = str_replace( $matches[0][$i], $uniqidPHP, $message ); 
  }

  $spaces = array( ' ', "\t" );
  $entities = array( '&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;' );
	
  preg_match_all( "#\[code\](.+)\[\/code\]#isU", $message, $matches );
  $cnt = count( $matches[0] );

  for ( $i = 0; $i < $cnt; $i++ ) {
	$codeBlocks[] = '<div class="code">'.nl2br( str_replace( $spaces, $entities, htmlspecialchars( $matches[1][$i] ) ) ).'</div>';
	$codeBlocks[$i] = str_replace( '<div class="code"><br />', '<div class="code">', $codeBlocks[$i] );
	$uniqidCode = '[code_'.uniqid('').']';
	$uniqidsCode[] = $uniqidCode;
    $message = str_replace( $matches[0][$i], $uniqidCode, $message ); 
  }
	
  preg_match_all( "#\[sql\](.+)\[\/sql\]#isU", $message, $matches );
  $cnt = count( $matches[0] );
  for ( $i = 0; $i < $cnt; $i++ ) {
    $sqlBlocks[] = '<div class="codeSQL">'.highlight_sql( $matches[1][$i] ).'</div>';
    $sqlBlocks[$i] = str_replace( '<div class="codeSQL"><br />', '<div class="codeSQL">', $sqlBlocks[$i] );
    $uniqidSQL = '[sql_'.uniqid('').']';
    $uniqidsSQL[] = $uniqidSQL;
    $message = str_replace( $matches[0][$i], $uniqidSQL, $message ); 
  }

  preg_match_all( "#\[js\](.+)\[\/js\]#isU", $message, $matches );
  $cnt = count( $matches[0] );
  for ( $i = 0; $i < $cnt; $i++ ) {
    $jsBlocks[] = '<div class="codeJS">'.geshi_highlight($matches[1][$i], 'javascript', '', true).'</div>';
    $jsBlocks[$i] = str_replace( '<div class="codeJS"><code><br />', '<div class="codeJS"><code>', $jsBlocks[$i] );
    $uniqidJS = '[js_'.uniqid('').']';
    $uniqidsJS[] = $uniqidJS;
    $message = str_replace( $matches[0][$i], $uniqidJS, $message ); 
  } 
	
  preg_match_all( "#\[css\](.+)\[\/css\]#isU", $message, $matches );
  $cnt = count( $matches[0] );
  for ( $i = 0; $i < $cnt; $i++ ) {
    $cssBlocks[] = '<div class="codeCSS">'.geshi_highlight($matches[1][$i], 'css', '', true).'</div>';
    $cssBlocks[$i] = str_replace( '<div class="codeCSS"><code><br />', '<div class="codeCSS"><code>', $cssBlocks[$i] );
    $uniqidCSS = '[css_'.uniqid('').']';
    $uniqidsCSS[] = $uniqidCSS;
    $message = str_replace( $matches[0][$i], $uniqidCSS, $message ); 
  } 

  preg_match_all( "#\[html\](.+)\[\/html\]#isU", $message, $matches );
  $cnt = count( $matches[0] );
  for ( $i = 0; $i < $cnt; $i++ ) {
    $htmlBlocks[] = '<div class="codeHTML">'.geshi_highlight($matches[1][$i], 'html4strict', '', true).'</div>';
    $htmlBlocks[$i] = str_replace( '<div class="codeHTML"><br />', '<div class="codeHTML">', $htmlBlocks[$i] );
    $uniqidHTML = '[html_'.uniqid('').']';
    $uniqidsHTML[] = $uniqidHTML;
    $message = str_replace( $matches[0][$i], $uniqidHTML, $message ); 
  }
	
  $message = htmlspecialchars( $message );
  $message = preg_replace("#\[b\](.+)\[\/b\]#isU", '<b>\\1</b>', $message);
  $message = preg_replace("#\[i\](.+)\[\/i\]#isU", '<i>\\1</i>', $message);
  $message = preg_replace("#\[u\](.+)\[\/u\]#isU", '<u>\\1</u>', $message);
  $message = preg_replace("#\[quote\](.+)\[\/quote\]#isU",'<div class="quoteHead">÷итата</div><div class="quoteContent">\\1</div>',$message);
  $message = preg_replace("#\[quote=&quot;([- 0-9a-zа-¤ј-я]{1,30})&quot;\](.+)\[\/quote\]#isU", '<div class="quoteHead">\\1 пишет:</div><div class="quoteContent">\\2</div>', $message);
  $message = preg_replace("#\[url\][\s]*([\S]+)[\s]*\[\/url\]#isU",'<a href="\\1" target="_blank">\\1</a>',$message);
  $message = preg_replace("#\[url[\s]*=[\s]*([\S]+)[\s]*\][\s]*([^\[]*)\[/url\]#isU",
                             '<a href="\\1" target="_blank">\\2</a>',
                             $message);
  $message = preg_replace("#\[img\][\s]*([\S]+)[\s]*\[\/img\]#isU",'<img src="\\1" alt="" />',$message);
  $message = preg_replace("#\[color=red\](.+)\[\/color\]#isU",'<span style="color:#FF0000">\\1</span>',$message);
  $message = preg_replace("#\[color=green\](.+)\[\/color\]#isU",'<span style="color:#008000">\\1</span>',$message);
  $message = preg_replace("#\[color=blue\](.+)\[\/color\]#isU",'<span style="color:#0000FF">\\1</span>',$message);
  $message = preg_replace_callback("#\[list\]\s*((?:\[\*\].+)+)\[\/list\]#siU",'getUnorderedList',$message);
  $message = preg_replace_callback("#\[list=([a|1])\]\s*((?:\[\*\].+)+)\[\/list\]#siU", 'getOrderedList',$message);
	
  $message = nl2br( $message);
	
  if ( isset( $uniqidCode ) ) $message = str_replace( $uniqidsCode, $codeBlocks, $message );
  if ( isset( $uniqidPHP ) ) $message = str_replace( $uniqidsPHP, $phpBlocks, $message );
  if ( isset( $uniqidSQL ) ) $message = str_replace( $uniqidsSQL, $sqlBlocks, $message );
  if ( isset( $uniqidJS ) ) $message = str_replace( $uniqidsJS, $jsBlocks, $message );
  if ( isset( $uniqidCSS ) ) $message = str_replace( $uniqidsCSS, $cssBlocks, $message );
  if ( isset( $uniqidHTML ) ) $message = str_replace( $uniqidsHTML, $htmlBlocks, $message );
	
  $message = str_replace( '</div><br />', '</div>', $message );
	
  return $message;
}

function split_text($matches) 
{
  return wordwrap($matches[1], 35, ' ',1);
}

function getUnorderedList( $matches )
{
  $list = '<ul>';
  $tmp = trim( $matches[1] );
  $tmp = substr( $tmp, 3 );
  $tmpArray = explode( '[*]', $tmp );	 
  $elements = '';
  foreach ( $tmpArray as $value ) {
	$elements = $elements.'<li>'.trim($value).'</li>';
  }
  $list = $list.$elements;
  $list = $list.'</ul>';
  return $list;
}

function getOrderedList( $matches )
{
  if ( $matches[1] == '1' )
	$list = '<ol type="1">';
  else
	$list = '<ol type="a">';
  $tmp = trim( $matches[2] );
  $tmp = substr( $tmp, 3 );
  $tmpArray = explode( '[*]', $tmp );
 
  $elements = '';
  foreach ( $tmpArray as $value ) {
	$elements = $elements.'<li>'.trim($value).'</li>';
  }
  $list = $list.$elements;
  $list = $list.'</ol>';
  return $list;
}

function highlight_sql( $sql ) 
{
  $sql = preg_replace("#(\"|'|`)(.+?)\\1#i", "<span style='color:red'>\\0</span>", $sql );
  $sql = preg_replace("#\b(SELECT|INSERT|UPDATE|DELETE|ALTER|TABLE|DROP|CREATE|ADD|WHERE|MODIFY|CHANGE|AS|DISTINCT|IN|ASC|DESC|ORDER|BY|GROUP|SET|FROM|INTO|LIKE|NOT|REGEXP|MAX|AVG|SUM|COUNT|MIN|AND|OR|VALUES|INDEX|HAVING|NULL|ON|BETWEEN|UNION|CONCAT|LIMIT|ANY|ALL|KEY|INNER|LEFT|RIGHT|JOIN|IFNULL|DEFAULT|CHARSET|PRIMARY|ENGINE)\b#i", "<span style='color:teal;font-weight:bold'>\\1</span>", $sql );

  $spaces = array( ' ', "\t" );
  $entities = array( '&nbsp;', '&nbsp;&nbsp;&nbsp;&nbsp;' );

  $sql = nl2br( str_replace( $spaces, $entities, $sql ) );
  $sql = str_replace( 'span&nbsp;style', 'span style', $sql );

  return $sql;
}

class Lingua_Stem_Ru
{
    var $VERSION = "0.02";
    var $Stem_Caching = 0;
    var $Stem_Cache = array();
    var $VOWEL = '/аеиоуыэю¤/';
    var $PERFECTIVEGROUND = '/((ив|ивши|ившись|ыв|ывши|ывшись)|((?<=[а¤])(в|вши|вшись)))$/';
    var $REFLEXIVE = '/(с[¤ь])$/';
    var $ADJECTIVE = '/(ее|ие|ые|ое|ими|ыми|ей|ий|ый|ой|ем|им|ым|ом|его|ого|ему|ому|их|ых|ую|юю|а¤|¤¤|ою|ею)$/';
    var $PARTICIPLE = '/((ивш|ывш|ующ)|((?<=[а¤])(ем|нн|вш|ющ|щ)))$/';
    var $VERB = '/((ила|ыла|ена|ейте|уйте|ите|или|ыли|ей|уй|ил|ыл|им|ым|ен|ило|ыло|ено|¤т|ует|уют|ит|ыт|ены|ить|ыть|ишь|ую|ю)|((?<=[а¤])(ла|на|ете|йте|ли|й|л|ем|н|ло|но|ет|ют|ны|ть|ешь|нно)))$/';
    var $NOUN = '/(а|ев|ов|ие|ье|е|и¤ми|¤ми|ами|еи|ии|и|ией|ей|ой|ий|й|и¤м|¤м|ием|ем|ам|ом|о|у|ах|и¤х|¤х|ы|ь|ию|ью|ю|и¤|ь¤|¤)$/';
    var $RVRE = '/^(.*?[аеиоуыэю¤])(.*)$/';
    var $DERIVATIONAL = '/[^аеиоуыэю¤][аеиоуыэю¤]+[^аеиоуыэю¤]+[аеиоуыэю¤].*(?<=о)сть?$/';

    function s(&$s, $re, $to)
    {
        $orig = $s;
        $s = preg_replace($re, $to, $s);
        return $orig !== $s;
    }

    function m($s, $re)
    {
        return preg_match($re, $s);
    }

    function stem_word($word)
    {
        $word = strtolower($word);
        $word = strtr($word, 'Є', 'е');
        if ($this->Stem_Caching && isset($this->Stem_Cache[$word])) {
            return $this->Stem_Cache[$word];
        }
        $stem = $word;
        do {
          if (!preg_match($this->RVRE, $word, $p)) break;
          $start = $p[1];
          $RV = $p[2];
          if (!$RV) break;

          # шаг 1
          if (!$this->s($RV, $this->PERFECTIVEGROUND, '')) {
              $this->s($RV, $this->REFLEXIVE, '');

              if ($this->s($RV, $this->ADJECTIVE, '')) {
                  $this->s($RV, $this->PARTICIPLE, '');
              } else {
                  if (!$this->s($RV, $this->VERB, ''))
                      $this->s($RV, $this->NOUN, '');
              }
          }

          # шаг 2
          $this->s($RV, '/и$/', '');

          # шаг 3
          if ($this->m($RV, $this->DERIVATIONAL))
              $this->s($RV, '/ость?$/', '');

          # шаг 4
          if (!$this->s($RV, '/ь$/', '')) {
              $this->s($RV, '/ейше?/', '');
              $this->s($RV, '/нн$/', 'н');
          }

          $stem = $start.$RV;
        } while(false);
        if ($this->Stem_Caching) $this->Stem_Cache[$word] = $stem;
        return $stem;
    }

    function stem_caching($parm_ref)
    {
        $caching_level = @$parm_ref['-level'];
        if ($caching_level) {
            if (!$this->m($caching_level, '/^[012]$/')) {
                die(__CLASS__ . "::stem_caching() - Legal values are '0','1' or '2'. '$caching_level' is not a legal value");
            }
            $this->Stem_Caching = $caching_level;
        }
        return $this->Stem_Caching;
    }

    function clear_stem_cache()
    {
        $this->Stem_Cache = array();
    }
}

?>