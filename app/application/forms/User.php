<?php
/**
 * @package Application_Form_User
 */

/**
 * Declaration of the user form
 * @author zefiryn
 * @since Mar 2011
 */
class Application_Form_User extends Zefir_Form
{
    protected $_type;

    public function __construct($type = 'form', $options = null)
    {
        $this->_type = $type;
        parent::__construct($options);
    }

    public function init()
    {
        $L = $this->_regex['L'];
        $N = $this->_regex['N'];
        $S = $this->_regex['S'];
        $E = $this->_regex['E'];
        $B = $this->_regex['B'];
        parent::init();

        $this->setMethod('post');
        $this->setName('UserForm');
        $this->setTranslator(Zend_Registry::get('Zend_Translate'));

        $this->addElementPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');
        $this->addPrefixPath('GP_Decorator', 'GP/Form/Decorator', 'decorator');

        $element = $this->createElement('hidden', 'user_id')->setDecorators(array('ViewHelper'));
        $this->addElement($element);

        $element = $this->createElement('text', 'nick');
        $element->setAttribs(array('class' => 'width1'))
            ->setLabel('nick')
            ->setDecorators($this->_getZefirDecorators())
            ->setRequired(TRUE)
            ->setDescription('user_form_nick_field_description')
            ->addValidators(array(
                new Zend_Validate_Regex('/^[' . $L . $N . '\-_]+$/'),
                new Zefir_Validate_Notdigit(),
                new Zend_Validate_StringLength(array('min' => 3, 'max' => 50)),
                new Zefir_Validate_Unique(array(
                    'table' => 'users',
                    'field' => 'nick',
                    'id' => 'user_id'))
            ));
        $this->addElement($element);

        $element = $this->createElement('password', 'password');
        $element->setAttribs(array('class' => 'width1'))
            ->setLabel('password')
            ->setDecorators($this->_getStandardDecorators());

        if ($this->_type == 'new' || $this->_type == 'subform')
            $element->setRequired(TRUE);
        $this->addElement($element);

        $element = $this->createElement('password', 'password_check');
        $element->setAttribs(array('class' => 'width1'))
            ->setLabel('password_repeat')
            ->setDecorators($this->_getStandardDecorators())
            ->addValidators(array(
                new Zefir_Validate_IdenticalField('password')
            ));
        if ($this->_type == 'new' || $this->_type == 'subform')
            $element->setRequired(TRUE);
        $this->addElement($element);

        $element = $this->createElement('text', 'name');
        $element->setAttribs(array('class' => 'width1'))
            ->setLabel('user_name')
            ->setDecorators($this->_getZefirDecorators())
            ->addFilters(array(
                new Zend_Filter_StringTrim()
            ))
            ->addValidators(array(
                new Zend_Validate_Regex('/^[' . $L . '\- ]+$/'),
                new Zend_Validate_StringLength(array('min' => 3, 'max' => 150))
            ));
        if ($this->_type == 'subform')
            $element->setRequired(TRUE);
        $this->addElement($element);

        $element = $this->createElement('text', 'surname');
        $element->setAttribs(array('class' => 'width1'))
            ->setLabel('user_surname')
            ->setDecorators($this->_getZefirDecorators())
            ->addFilters(array(
                new Zend_Filter_StringTrim()
            ))
            ->addValidators(array(
                new Zend_Validate_Regex('/^[' . $L . '\- ]+$/'),
                new Zend_Validate_StringLength(array('min' => 3, 'max' => 200))
            ));
        if ($this->_type == 'subform')
            $element->setRequired(TRUE);
        $this->addElement($element);

        $element = $this->createElement('text', 'address');
        $element->setAttribs(array('class' => 'width1'))
            ->setLabel('address')
            ->setDecorators($this->_getZefirDecorators())
            ->addValidators(array(
                new Zend_Validate_Regex('/^[' . $L . $N . $S . ' ]+$/'),
                new Zend_Validate_StringLength(array('min' => 3, 'max' => 200))
            ));
        $this->addElement($element);

        $element = $this->createElement('text', 'phone');
        $element->setAttribs(array('class' => 'width1'))
            ->setLabel('phone')
            ->setDescription('phone_description')
            ->setDecorators($this->_getZefirDecorators())
            ->addValidators(array(
                new Zend_Validate_Regex('/^\+[0-9]{2,3}( )?[0-9]{4,9}$/'),
                new Zend_Validate_StringLength(array('min' => 5, 'max' => 15))
            ));
        $this->addElement($element);

        $element = $this->createElement('text', 'email');
        $element->setAttribs(array('class' => 'width1'))
            ->setLabel('email')
            ->setDecorators($this->_getZefirDecorators())
            ->setRequired(TRUE)
            ->addValidators(array(
                new Zend_Validate_EmailAddress(),
                new Zend_Validate_StringLength(array('min' => 3, 'max' => 35)),
                new Zefir_Validate_Unique(array(
                    'table' => 'users',
                    'field' => 'email',
                    'id' => 'user_id'))
            ));
        $this->addElement($element);

        $element = $this->createElement('text', 'email_check');
        $element->setAttribs(array('class' => 'width1'))
            ->setLabel('email_repeat')
            ->setDecorators($this->_getStandardDecorators())
            ->addValidators(array(
                new Zefir_Validate_IdenticalField('email')
            ));
        if ($this->_type == 'new' || $this->_type == 'subform')
            $element->setRequired(TRUE);
        $this->addElement($element);

        $element = $this->createElement('checkbox', 'show_email');
        $element->setAttribs(array('class' => 'checkbox'))
            ->setLabel('show_email', array('tag' => 'label'))
            ->setDecorators($this->_getStandardDecorators())
            ->setRequired(FALSE)
            ->addValidators(array(
                new Zend_Validate_Regex('/^0|1$/')
            ));
        $this->addElement($element);

        $roles = array('admin' => 'admin', 'juror' => 'juror', 'user' => 'user');
        $element = $this->createElement('select', 'role');
        $element->setAttribs(array('class' => 'width1', 'size' => 1))
            ->setLabel('role')
            ->setDecorators($this->_getStandardDecorators())
            ->setMultiOptions($roles)
            ->setRequired(TRUE);
        $this->addElement($element);

        if ($this->_type == 'form' || $this->_type == 'new') {
            $this->_createCsrfElement();
            $this->_createStandardSubmit('submit');
            $this->addDisplayGroup(array('leave', 'submit'), 'submitFields')
                ->setDisplayGroupDecorators(array(
                    'FormElements',
                    array('Fieldset', array('class' => 'submit'))
                ));
        }
    }
}
