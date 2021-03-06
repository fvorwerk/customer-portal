<?php

namespace Oro\Bundle\FrontendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

use Oro\Bundle\ConfigBundle\Form\Type\FormFieldType;

class PageTemplateFormFieldType extends AbstractType
{
    const NAME = 'oro_frontend_page_template_form_field';

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FormFieldType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return self::NAME;
    }
}
