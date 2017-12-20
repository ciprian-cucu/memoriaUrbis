<?php
namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;



use AppBundle\Form\LightPhotoForm;

class StoryForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class);
        $builder->add('abstract', TextareaType::class);
        $builder->add('text', TextareaType::class);
        $builder->add('storyOrder', NumberType::class);
        $builder->add('year0', NumberType::class);
        $builder->add('longitude', NumberType::class);
        $builder->add('latitude', NumberType::class);




        $builder->add('photos', CollectionType::class, array(
            'entry_type' => LightPhotoForm::class,
            'entry_options' => array('label' => false),
            'allow_add' => true,
            'prototype' => true,
        ));
        $builder->add('author',EntityType::class, array(
            'class' => 'AppBundle:User',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->where('u.isActive = 1');
            },
            'choice_label' => 'name')
        );
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Story'
        ]);
    }
}
