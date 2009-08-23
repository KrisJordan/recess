<?php
Library::import('recess.framework.AbstractHelper');
Library::import('recess.framework.helpers.Url');

/**
 * HTML helper class.
 * @author Joshua Paine
 * @author Kris Jordan
 * @todo Add protocol parameters back in to anchor and url::..
 * 
 * Based upon Kohana's HTML helper:
 * @author     Kohana Team
 * @copyright  (c) 2007-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Html extends AbstractHelper {

	/**
	 * Convert special characters to HTML entities
	 *
	 * @param   string   string to convert
	 * @param   boolean  encode existing entities
	 * @return  string
	 */
	public static function specialchars($str, $double_encode = true) {
		// Force the string to be a string
		$str = (string) $str;

		// Do encode existing HTML entities (default)
		if ($double_encode === true) {
			$str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
		} else {
			// Do not encode existing HTML entities
			// From PHP 5.2.3 this functionality is built-in, otherwise use a regex
			if (version_compare(PHP_VERSION, '5.2.3', '>=')) {
				$str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8', false);
			} else {
				$str = preg_replace('/&(?!(?:#\d++|[a-z]++);)/ui', '&amp;', $str);
				$str = str_replace(array('<', '>', '\'', '"'), array('&lt;', '&gt;', '&#39;', '&quot;'), $str);
			}
		}

		return $str;
	}

	/**
	 * Create HTML link anchors.
	 *
	 * @param   string  URL or URI string
	 * @param   string  link text
	 * @param   array   HTML anchor attributes
	 * @return  string
	 */
	public static function anchor($uri, $title = NULL, $attributes = NULL) {
		if ($uri === '') {
			$siteUrl = url::base();
		} else {
			$siteUrl = $uri;
		}

		return
		// Parsed URL
		'<a href="'.html::specialchars($siteUrl, false).'"'
		// Attributes empty? Use an empty string
		.(is_array($attributes) ? html::attributes($attributes) : '').'>'
		// Title empty? Use the parsed URL
		.(($title === NULL) ? $siteUrl : $title).'</a>';
	}


	/**
	 * Creates a stylesheet link.
	 *
	 * @param   string|array  filename, or array of filenames to match to array of medias
	 * @param   string|array  media type of stylesheet, or array to match filenames
	 * @return  string
	 */
	public static function css($style, $media = FALSE) {
		if(is_array($media)) {
			$media = implode(', ', $media);
		}
		return html::link($style, 'stylesheet', 'text/css', '.css', $media);
	}

	/**
	 * Creates a link tag.
	 *
	 * @param   string|array  filename
	 * @param   string|array  relationship
	 * @param   string|array  mimetype
	 * @param   string        specifies suffix of the file
	 * @param   string|array  specifies on what device the document will be displayed
	 * @return  string
	 */
	public static function link($href, $rel, $type, $suffix = FALSE, $media = FALSE) {
		$compiled = '';

		if (is_array($href)) {
			foreach ($href as $_href) {
				$_rel   = is_array($rel) ? array_shift($rel) : $rel;
				$_type  = is_array($type) ? array_shift($type) : $type;
				$_media = is_array($media) ? array_shift($media) : $media;

				$compiled .= html::link($_href, $_rel, $_type, $suffix, $_media);
			}
		} else {
			// Add the suffix only when it's not already present
			$suffix   = ( ! empty($suffix) AND strpos($href, $suffix) === FALSE) ? $suffix : '';
			$media    = empty($media) ? '' : ' media="'.$media.'"';
			$compiled = '<link rel="'.$rel.'" type="'.$type.'" href="'.url::asset(($type=="text/css" ? 'css/' : '').$href.$suffix).'"'.$media.' />';
		}

		return $compiled."\n";
	}

	/**
	 * Creates a script link.
	 *
	 * @param   string|array  filename
	 * @return  string
	 */
	public static function js($script) {
		$compiled = '';

		if (is_array($script)) {
			foreach ($script as $name) {
				$compiled .= html::js($name);
			}
		} else {
			// Do not touch full URLs
			if (strpos($script, '://') === FALSE) {
				// Add the suffix only when it's not already present
				$suffix = (substr($script, -3) !== '.js') ? '.js' : '';
				$script = url::asset('js/'.$script.$suffix);
			}
			$compiled = '<script type="text/javascript" src="'.$script.'"></script>';
		}

		return $compiled."\n";
	}

	/**
	 * Creates a image link.
	 *
	 * @param   string        image source, or an array of attributes
	 * @param   string|array  image alt attribute, or an array of attributes
	 * @return  string
	 */
	public static function img($src = NULL, $alt = NULL) {
		// Create attribute list
		$attributes = is_array($src) ? $src : array('src' => $src);

		if (is_array($alt)) {
			$attributes += $alt;
		} elseif ( ! empty($alt)) {
			// Add alt to attributes
			$attributes['alt'] = $alt;
		}
		if(!isset($attributes['alt'])) $attributes['alt'] = '';
		if (strpos($attributes['src'], '://') === FALSE) {
			// Make the src attribute into an absolute URL
			$attributes['src'] = url::asset('img/'.$attributes['src']);
		}

		return '<img'.html::attributes($attributes).'>';
	}

	/**
	 * Compiles an array of HTML attributes into an attribute string.
	 *
	 * @param   string|array  array of attributes
	 * @return  string
	 */
	public static function attributes($attrs) {
		if (empty($attrs))
			return '';

		if (is_string($attrs))
			return ' '.$attrs;

		$compiled = '';
		foreach ($attrs as $key => $val) {
			$compiled .= ' '.$key.'="'.$val.'"';
		}

		return $compiled;
	}

} // End html

function h($var,$encode_entities=true){ return html::specialchars($var,$encode_entities); }
?>