<?php
use Webiny\Component\Entity\Attribute\CharType;
use Webiny\Component\Entity\Attribute\DecimalType;
use Webiny\Component\Entity\EntityAbstract;

require_once '../../library/autoloader.php';

class Person extends EntityAbstract {

	/**
	 * @return IntegerType
	 */
	public function getId(){
		return $this->getAttribute('id');
	}

	/**
	 * @return CharType
	 */
	public function getName(){
		return $this->getAttribute('name');
	}

	/**
	 * @return DecimalType
	 */
	public function getSalary(){
		return $this->getAttribute('salary');
	}

	protected function _wbEntityStructure(){
		$this->attr('id')
			->integer('person_id')
			->label('Person ID')
			->defaultValue(0)
		->attr('name')
			->char('person_name')
			->label('Full name')
		->attr('salary')
			->decimal('person_salary')
			->label('Desired salary')
			->digit(8,2)
			->defaultValue(3000);
	}
}

$entity = new Person();
$data = ['person_id' => 12, 'person_name' => 'Pavel Denisjuk'];
$entity->populate($data);
$entity->getName()->setValue('Pero');


var_dump($entity);