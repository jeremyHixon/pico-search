<?php

/**
 * Search plugin for Pico CMS
 *
 * @author Jeremy Hixon
 * @link http://jeremyhixon.com
 * @license http://opensource.org/licenses/MIT
 */

class Pico_Search {
	private $results = array();
	private $base_url = '';
	private $query = false;
	private $placeholder = 'Keyword or terms&hellip;';
	private $is_search = false;
	private $search_terms = false;
	private $total_results = 0;

/* Pagination (Later)
	private $current_page = false;
	private $per_page = 2;
	private $offset = 0;
*/

	public function config_loaded(&$settings) {
		$this->base_url = $settings['base_url'];
	}
	
	public function before_load_content(&$file) {
		if (preg_match('/\/search\//', $file)) {
			$this->is_search = true;
			$file_pieces = explode('/', $file);
			if ($file_pieces[count($file_pieces) - 1] !== 'index.md') {
				$search_index = $this->pico_search_find($file_pieces);
				
				$query = $file_pieces[$search_index + 1];
				$query = preg_replace('/\.md/', '', $query);
				$this->query = urldecode($query);
				
				$current_page = (isset($file_pieces[$search_index + 3])) ? $file_pieces[$search_index + 3] : 1;
				$this->current_page = $current_page;
				array_pop($file_pieces);
				$file_new = implode('/', $file_pieces);
				$file_new .= '/index.md';
				$file = $file_new;
			}
		}
	}

	public function get_pages(&$pages, &$current_page, &$prev_page, &$next_page) {
		if (isset($_POST['query']) && !empty($_POST['query'])) {
			header('Location: ' . $this->base_url . '/search/' . urlencode($_POST['query']));
		}
		if ($this->query) {
			$results = array();
			foreach ($pages as $page) {
				if (stripos($page['content'], $this->query) || stripos($page['title'], $this->query)) {
					$results[] = $page;
				}
			}
			$this->total_results = count($results);

/* Pagination (Later)
			$results = array_slice($results, $this->offset, $this->per_page);
*/
			$this->results = $results;
		}
	}

	public function before_render(&$twig_vars, &$twig, &$template) {
		$twig_vars['pico_search']['search_form'] = $this->pico_search_form_build($this->results, $this->base_url);
		$twig_vars['pico_search']['total_results'] = $this->total_results;
		$twig_vars['is_search'] = $this->is_search;
		
		if ($twig_vars['is_search']) {
			$template = 'search';
			$twig_vars['pico_search']['results'] = $this->results;
		}
	}
	
	private function pico_search_form_build($results = array(), $base_url = '') {
		$form =		'<form method="post" action="' . $base_url . '/search/">' . "\n";
		$form .=	'<input type="text" name="query" id="query" placeholder="' . $this->placeholder . '" value="' . $this->query . '">' . "\n";
		$form .=	'<button name="submit" title="Search"><span class="icon-search"></span></button>' . "\n";
		$form .=	'</form>' . "\n";
		return $form;
	}
	
	private function pico_search_find($pieces = array()) {
		$i = 0;
		foreach ($pieces as $piece) {
			if (strtolower($piece) === 'search') {
				return $i;
				break;
			}
			$i++;
		}
	}
}