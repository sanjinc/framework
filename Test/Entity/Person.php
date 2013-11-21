<?php
ini_set('xdebug.var_display_max_depth', '10');

use Webiny\Component\Entity\Attribute\DecimalAttribute;
use Webiny\Component\Entity\EntityAbstract;


require_once '../../library/autoloader.php';

class Person extends EntityAbstract
{
	/**
	 * @return DecimalAttribute
	 */
	public function getSalary() {
		return $this->getAttribute('salary');
	}

	protected function _entityStructure() {
		// Set primary key attribute
		$this->_entityPrimaryKey('id');

		// Set entity mask
		$this->_entityMask('{name} ({id})');

		// Create attributes
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