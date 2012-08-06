<?php

namespace Bootstrap;

class Html_Table {
	
	protected $fields			= array(); // champs avec les champs manuels
	protected $pagination	= array();
	protected $mfields		= array(); //  champs issues du modele
	protected $parsed			= array(); // fields avec le contenu traité
	protected $data				= array(); // données query
	protected $model			= null; // nom du model
	protected $header			= ''; // html header
	protected $body				= ''; // html body
	protected $footer			= ''; // html footer
	protected $config			= array(
		'paginate'		=> true,
		'language'		=> '', // namespace du language
		'model'				=> null, // modèle utilisé
		'data'				=> null,
		'display'			=> null,
		'per_page'		=> 20,
		'offset'			=> 'page',
		'search'			=> 'search',
		'order_by'		=> 'order', // clé input
		'direction'		=> 'dir', // clé input
		'sort'				=> true,
		'alternator'	=> array('odd', 'even'),
		'attributes'	=> array()
	);
	
	public function __construct(array $config = array())
	{
		foreach ($config as $key => $conf) $this->config[$key] = $conf;
		
		if ($this->config['model'])
		{
			$this->set_model($this->config['model'], $this->config['data']);
		}
		
		if ($this->config['display'] and is_array($this->config['display']))
		{
			$elems = '';
			foreach ($this->config['display'] as $v) $elems .= ' table-'.$v;

			$this->config['attributes']['class'] = $elems;
		}
		if (! isset($this->config['attributes']['class']))
		{
			$this->config['attributes']['class'] = 'table';
		}
		else
		{
			$this->config['attributes']['class'] = 'table '.$this->config['attributes']['class'];
		}
	}
	
	/**
	 * Assige un modele et des donneés (resultats Query)
	 * à l'instance table en cours
	 * 
	 * @access public
	 * @param mixed $model
	 * @return void
	 */
	public function set_model($model)
	{
		// namespace de la langue à partir de celui de la classe
		if (! $this->config['language'])
		{
			$this->config['language'] = rtrim(\Inflector::get_namespace(strtolower($model)), '\\');
		}
		if (property_exists($model, '_table') and is_array($model::$_table))
		{
			$this->_set_model_fields($model::$_table);
			$this->model		= $model;
			$this->data			= $this->_get_model_data($model);
		}
		return $this;
	}
	
	/**
	 * Génère les champs à traiter pour le tri à partir des données du modèle
	 * 
	 * @access protected
	 * @param mixed array $fields
	 * @return void
	 */
	protected function _set_model_fields(array $fields)
	{
		$output = array();
		foreach ($fields as $key => $field)
		{
			if (is_array($field))
			{
				$this->mfields[$key]	= $field['sort'];
				$output[$key] = $field['display'];
			}
			else
			{
				$this->mfields[$key] = $field;
				$output[$key] = $field;
			}
		}
		return ($this->fields = $this->fields + $output);
	}
	
	/**
	 * Génère la requête à partir des données de configuration.
	 * Pour le tri du tableau, le modèle supporte les relations
	 * 
	 * @access public
	 * @return void
	 */
	protected function _get_model_data($model)
	{
		$query = ($search = \Input::get($this->config['search'])) ? $model::search($search) : $model::query();
		
		if ($this->config['sort'] === true)
		{
			$order_key	= \Input::get($this->config['order_by']);
			$direction  = \Input::get($this->config['direction']);
			
			if (array_key_exists($order_key, $this->mfields))
			{
				$order_by = $this->mfields[$order_key];
				if (strpos($order_by,'.') > 0)
				{
				  list ($related) = explode('.', $order_by);
				  $query->related($related);
				}
				$query->order_by($order_by, $direction ?: 'asc');
			}
			else
			{
				$query->order_by('id', 'desc');
			}
		}
		if ($this->config['paginate'] === true)
		{
			$query = $this->_paginate($query);
		}
		try {
			$output = $query->get();
		}
		catch (\Database_Exception $e) {
			$output = null;
		}
		return $output ?: array();
	}
	
	/**
	 * Prépare la configuration et la requête pour la 
	 * Pagination des résultats
	 * 
	 * @access protected
	 * @param mixed $query
	 * @return void
	 */
	protected function _paginate($query)
	{
		if ($count = $query->count() and $count > $this->config['per_page'])
		{
			$conf = array(
				'pagination_url'	=> \Uri::current(),
				'uri_parameter'		=> $this->config['offset'],
				'total_items'			=> $query->count(),
				'per_page'				=> $this->config['per_page'],
			);
			
			\Pagination::set_config($conf);
			$this->pagination = \Pagination::create_links();
			
			$query
				->limit($this->config['per_page'])
				->offset(\Pagination::offset());
		}
		return $query;
	}
	
	/**
	 * Assigne les champs à partir des données fields récupérées
	 * via la propriété _table du modèle. Les valeurs peuvent être à la fois
	 * une propriétée ou une fonction. (la propriété aura l'ascendance dans le
	 * traitement si les noms sont identiques).
	 *
	 * La méthode supporte également les champs issues des relations de type "one" du modèle.
	 * Il faut séparer les accès aux objets par un point "." dans la valeur du champ
	 * 
	 * @access public
	 * @param mixed array $data
	 * @return void
	 */
	public function _set_from_model(array $data)
	{
		// Assigne les champs
		$fields			= $this->fields;
		$highlight	= \Input::get($this->config['search']);
		$mfields		=& $this->mfields;
		
		$set_fields = function($instance, $output = array()) use($highlight, $fields, &$mfields) {
			foreach($fields as $name => $field)
			{
				// La valeur est une fonction
				if (is_callable($field))
				{
					$output[$name] = call_user_func($field, $instance);
				}
				// La valeur est une méthode du modèle
				elseif (method_exists($instance, $field))
				{
					$output[$name] = $instance->$field();
				}
				// La valeur est une propriété
				elseif (isset($instance->$field))
				{
					$output[$name] = $instance->$field;
				}
				// la valeur et une sous-propriété
				elseif (is_string($field) and strpos($field, '.') > 0)
				{
					$temp = $instance;
					foreach (explode('.', $field) as $prop)
					{
						$temp = (isset($temp->$prop)) ? $temp->$prop : null;
					}
					$output[$name] = $temp;
				}
				if ($highlight)
				{
					$output[$name] = \Str::highlight($output[$name], $highlight);
				}
			}
			return $output;
		};
		
		foreach ($data as $row) $this->parsed[] = $set_fields($row);

		return $this->parsed;
	}
		
	/**
	 * Assigne un champ particulier.
	 * L'usage principal sera de déterminer des fonctions de retour au traitement
	 * des données du tableau.
	 * Dans le Cas d'un traitement à partir d'un modèle, l'instance du modèle
	 * est passé comme argument si $value est un callback
	 * ex : set_field('username', function($instance) { return $this->username })
	 * 
	 * @access public
	 * @param mixed $field
	 * @param mixed $value
	 * @return void
	 */
	public function set_field($field, $value)
	{
		return $this->fields[$field] = $value;
	}
	
	
	/**
	 * Traitement 'par lots' pour déterminer des champs
	 * à partir d'un tableau associatif
	 * 
	 * @access public
	 * @param mixed array $array
	 * @return void
	 */
	public function set_fields(array $array)
	{
		foreach ($array as $key => $value)
		{
			$this->set_field($key, $value);
		}
		return $this;
	}
	
	/**
	 * Rendu du tableau
	 * 
	 * @access public
	 * @param array $data. (default: array())
	 * @return void
	 */
	public function render()
	{
		$data = $this->_set_from_model($this->data);
		
		// Construction du tableau
		$table  = html_tag('thead', array(), $this->_header());
		$table .= html_tag('tbody', array(), $this->_body($data));
		$table .= html_tag('tfoot', array(), $this->_footer());

		return html_tag('table', $this->config['attributes'], $table);
	}
	
	/**
	 * @access protected
	 * @param string $content. (default: '')
	 * @return void
	 */
	protected function _header()
	{
		$output = '';
		$order	= $this->config['order_by'];
		$dir		= $this->config['direction'];
		
		foreach ($this->fields as $key => $item)
		{
			// tri...
			if ($this->config['sort'] === true and array_key_exists($key, $this->mfields))
			{
				$show_sort	= \Input::get($order) === $key;
				$params			= \Input::get();
				$params[$order]	= $key;
				$attrs['class'] = null;
				
				if (! isset($params[$dir]) or $params[$dir] === 'desc')
				{
					$params[$dir] = 'asc';
					$show_sort and $attrs['class'] = 'icon-chevron-up';
				}
				else
				{
					$params[$dir] = 'desc';
					$show_sort and $attrs['class'] = 'icon-chevron-down';
				}
				
				$uri			= \Uri::current().'?'.http_build_query($params);
				$content	= __($this->config['language'].'.'.$key).html_tag('i',$attrs, '');
				$item	= \Html::anchor($uri, $content, array('class' => 'dir-'.$params[$dir]));
			}
			else
			{
				$item	= __($this->config['language'].'.'.$key);
			}
			$output .= html_tag('th', array(), $item);
		}
		return html_tag('tr', array(), $output);
	}
	
	/**
	 * @access protected
	 * @param mixed $val
	 * @param mixed $alt
	 * @return void
	 */
	protected function _body(array $data)
	{
		if (count($data) === 0)
		{
			$output = html_tag('tr', html_tag('td', array(
				'class' 	=> 'no-data',
				'colspan' => count($this->fields)),
				__($this->config['language'].'.table.empty')));
		}
		else
		{
			$output = '';
			$alt = ($this->config['alternator'])
				? call_user_func_array(array('Str', 'alternator'), $this->config['alternator'])
				: array();

			foreach ($data as $row)
			{
				$temp = '';
				foreach ($row as $item)
				{
					$temp .= html_tag('td', array('class' => $alt()), $item);
				}
				$output .= html_tag('tr', array(), $temp);
			}
		}
		return $output;
	}
	
	/**
	 * @access protected
	 * @return void
	 */
	protected function _footer()
	{
		if (empty($this->pagination)) return null;
		
		$content = html_tag('td', array('colspan' => count($this->fields)), $this->pagination);
		
		return html_tag('tr', array(), $content);
	}
	
}