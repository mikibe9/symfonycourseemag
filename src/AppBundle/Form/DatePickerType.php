<?php
/**
 * Created by PhpStorm.
 * User: mihaela.aldea
 * Date: 2015-06-09
 * Time: 17:31
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatePickerType extends AbstractType
{

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'widget' => 'single_text'
            )
        );
    }

    public function getParent()
    {
        return 'date';
    }

    public function getName()
    {
        return 'datePicker';
    }

} 