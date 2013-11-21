<?php
ini_set('xdebug.var_display_max_depth', '10');
use Webiny\Component\Entity\Attribute\DecimalType;
use Webiny\Component\Entity\EntityAbstract;
use Webiny\Component\Entity\EntityException;


require_once '../../library/autoloader.php';

class Person extends EntityAbstract
{

	/**
	 * @return DecimalType
	 */
	public function getSalary() {
		return $this->getAttribute('salary');
	}

	protected function _entityStructure() {
		$this->attr('id')
			 ->integer('person_id')
				 ->label('Person ID')
				 ->defaultValue(0)
				 ->validation('required', 'number')
		 ->attr('name')
			 ->char('person_name')
				 ->label('Full name')
				 ->tooltip('Enter your full name')
				 ->help('We will not use your name in advertising purposes.')
				 ->message('Must be at least 5 characters long')
				 ->validation('minlength:5', 'maxlength:10')
		 ->attr('phone_number')
			 ->char()
				->format('__/___-___')
		 ->attr('salary')
			 ->decimal('person_salary')
				 ->label('Desired salary')
				 ->digit(8, 2)
				 ->validation('required', 'number')
				 ->defaultValue(3000);
	}
}


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