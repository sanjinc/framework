<?php
namespace WF\Test\WkfTest;

class OfferCRUD extends CRUD
{

    public $status = array(
        'draft'     => 'Confirm',
        // Show label of action you want to perform
        'confirmed' => 'Confirmed'
    );

    /**
     * WORKFLOW METHODS
     */


    function wkfDraft() {
        // Will be executed each time an offer is loaded in DRAFT state
        $this->_getForm();
    }

    function wkfDraftToConfirmed() {
        // Will be executed once - when transition from DRAFT to CONFIRMED state is VALID
    }

    function wkfConfirmed() {
        // Will be executed each time an offer is loaded in CONFIRMED state
    }

    /**
     * CRUD METHODS
     */

    function load($id) {
        // TODO: Implement load() method.
    }

    function populate() {
        // TODO: Implement populate() method.
    }

    function save() {
        // TODO: Implement save() method.
    }

    /**
     * PRIVATE METHODS
     */

    private function _getForm() {
        // Return FORM
    }
}