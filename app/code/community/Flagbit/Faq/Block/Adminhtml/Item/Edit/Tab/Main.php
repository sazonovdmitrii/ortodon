<?php
/**
 * FAQ for Magento
 *
 * @category   Flagbit
 * @package    Flagbit_Faq
 * @copyright  Copyright (c) 2009 Flagbit GmbH & Co. KG <magento@flagbit.de>
 */

/**
 * FAQ for Magento
 *
 * @category   Flagbit
 * @package    Flagbit_Faq
 * @author     Flagbit GmbH & Co. KG <magento@flagbit.de>
 */
class Flagbit_Faq_Block_Adminhtml_Item_Edit_Tab_Main extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Prepares the page layout
     * 
     * Loads the WYSIWYG editor on demand if enabled.
     * 
     * @return Flagbit_Faq_Block_Admin_Edit
     */
    protected function _prepareLayout()
    {
        $return = parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        return $return;
    }
    
    /**
     * Preparation of current form
     *
     * @return Flagbit_Faq_Block_Admin_Edit_Tab_Main Self
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('faq');
        
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('faq_');
        
        $fieldset = $form->addFieldset('base_fieldset', array (
                'legend' => Mage::helper('flagbit_faq')->__('Вопросы'), 
                'class' => 'fieldset-wide' ));
        
        if ($model->getFaqId()) {
            $fieldset->addField('faq_id', 'hidden', array (
                    'name' => 'faq_id' ));
        }
        
        $fieldset->addField('question', 'text', array (
                'name' => 'question', 
                'label' => Mage::helper('flagbit_faq')->__('Вопрос'), 
                'title' => Mage::helper('flagbit_faq')->__('Вопрос'), 
                'required' => true ));
        
        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', 
                    array (
                            'name' => 'stores[]', 
                            'label' => Mage::helper('cms')->__('Store view'), 
                            'title' => Mage::helper('cms')->__('Store view'), 
                            'required' => true, 
                            'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true) ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array (
                    'name' => 'stores[]', 
                    'value' => Mage::app()->getStore(true)->getId() ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $fieldset->addField('name', 'text', 
            array (
                'label' => Mage::helper('flagbit_faq')->__('Имя покупателя'), 
                'title' => Mage::helper('flagbit_faq')->__('Имя покупателя'), 
                'name' => 'name', 
                'required' => true)
        );
        $fieldset->addField('email', 'text', 
            array (
                'label' => Mage::helper('flagbit_faq')->__('Email покупателя'), 
                'title' => Mage::helper('flagbit_faq')->__('Email покупателя'), 
                'name' => 'email', 
                'required' => true)
        );
        $fieldset->addField('city', 'text', 
            array (
                'label' => Mage::helper('flagbit_faq')->__('Город покупателя'), 
                'title' => Mage::helper('flagbit_faq')->__('Город покупателя'), 
                'name' => 'city', 
                'required' => true)
        );       
        $fieldset->addField('is_active', 'select', 
                array (
                        'label' => Mage::helper('cms')->__('Status'), 
                        'title' => Mage::helper('flagbit_faq')->__('Item status'), 
                        'name' => 'is_active', 
                        'required' => true, 
                        'options' => array (
                                '1' => Mage::helper('cms')->__('Enabled'), 
                                '0' => Mage::helper('cms')->__('Disabled') ) ));

	$fieldset->addField('notice', 'checkbox', 
             array(
    		'label'     => Mage::helper('flagbit_faq')->__('Уведомить пользователя?'),
    		'onclick'   => 'this.value = this.checked ? 1 : 0;',
    		'name'      => 'notice',
	     )
        );
        $fieldset->addField('is_featured', 'select', 
                array (
                        'label' => Mage::helper('cms')->__('Избранный вопрос/отзыв?'), 
                        'title' => Mage::helper('flagbit_faq')->__('Избранный?'), 
                        'name' => 'is_featured', 
                        'required' => true, 
                        'options' => array (
                                '1' => Mage::helper('cms')->__('Да'), 
                                '0' => Mage::helper('cms')->__('Нет') ) ));



        $fieldset->addField('category_id', 'multiselect', 
            array (
                'label' => Mage::helper('flagbit_faq')->__('Category'), 
                'title' => Mage::helper('flagbit_faq')->__('Category'), 
                'name' => 'categories[]', 
                'required' => false,
                'values' => Mage::getResourceSingleton('flagbit_faq/category_collection')->toOptionArray(),
            )
        );
        
        $fieldset->addField('answer', 'editor', 
                array (
                        'name' => 'answer', 
                        'label' => Mage::helper('flagbit_faq')->__('Ответ'), 
                        'title' => Mage::helper('flagbit_faq')->__('Ответ'), 
                        'style' => 'height:36em;',
                        'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
                        'required' => true ));
        
        $fieldset->addField('answer_html', 'select', 
                array (
                        'label' => Mage::helper('flagbit_faq')->__('HTML ответ'), 
                        'title' => Mage::helper('flagbit_faq')->__('HTML ответ'), 
                        'name' => 'answer_html', 
                        'required' => true, 
                        'options' => array (
                                '1' => Mage::helper('cms')->__('Enabled'), 
                                '0' => Mage::helper('cms')->__('Disabled') ) ));
        
        $form->setValues($model->getData());

        $this->setForm($form);
        
        return parent::_prepareForm();
    }
}
