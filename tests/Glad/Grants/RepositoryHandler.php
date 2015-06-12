<?php

namespace Glad\Grants;

class RepositoryHandler
{
	protected $path;
	protected $file;

	public function __construct()
	{
		$this->path = __DIR__ . '/../../';
		$this->file = 'storage.json';

		if(! file_exists($this->path . $this->file)) {
			touch($this->path . $this->file);
		}
	}

	public function save($collection = false, array $newData)
	{
		if($collection) {
			$data = $this->getAll();

			if($data) {
				$data = json_decode($data, true);
			}else{
				$data = [];
			}
			
			if(! isset($data[$collection])) {
				$data[$collection] = [];
			}
			$data[$collection] = array_merge($data[$collection], $newData);
			
			return file_put_contents($this->path . $this->file, json_encode($data));
		}
	}

	public function update($collection = false, $id, array $newData)
	{
		if($collection) {
			$data = $this->getAll();

			if($data) {
				$data = json_decode($data, true);

				if(isset($data[$collection][$id])) {
					$data[$collection][$id] = array_merge($data[$collection][$id], $newData);
					return file_put_contents($this->path . $this->file, json_encode($data));
				}
			}
		}
		return false;
	}

	public function get($collection = false, $id)
	{
		if($collection) {
			$data = $this->getAll();

			if($data) {
				$data = json_decode($data, true);

				if(isset($data[$collection]) && isset($data[$collection][$id])) {
					return $data[$collection][$id];
				}
			}
		}
	}

	public function remove($collection = false, $id)
	{
		if($collection) {

			$data = $this->getAll();

			if($data) {
				
				$data = json_decode($data, true);

				if(isset($data[$collection]) && isset($data[$collection][$id])) {
					unset($data[$collection][$id]);
					return file_put_contents($this->path . $this->file, json_encode($data));
				}
			}
		}
	}

	protected function getAll()
	{
		return file_get_contents($this->path . $this->file);
	}

	protected function createRepo()
	{
		return touch($this->path . $this->file);
	}
}