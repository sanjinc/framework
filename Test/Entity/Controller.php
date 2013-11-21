<?php

require_once '../../library/autoloader.php';
require_once __DIR__.'/Person.php';

class Controller
{

	public function action() {
		$entity = new Person();
		$entity->getSalary()->value(4500);


		$data = [
			'person_salary' => 500,
			'person_name'   => 'Pavel'
		];

		try {
			$entity->populate($data);
		} catch (EntityException $e) {
			foreach ($e->getInvalidAttributes() as $attribute => $ve) {
				echo $attribute . ': ';
				foreach ($ve->getErrorMessages() as $msg) {
					echo $msg . '<br />';
				}
			}
			die();
		}

		var_dump($entity->getAttributes());
	}
}

$c = new Controller();
$c->action();