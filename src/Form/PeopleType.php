<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\Hobby;
use App\Entity\People;
use App\Entity\Profile;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PeopleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('name')
            ->add('age')
            ->add('createdAt')
            ->add('updateAt')
            ->add('profile', EntityType::class, [
                'expanded' => true,
                'class' => Profile::class,
                'required' => false,
                'multiple' => false,
                'attr'   =>  [
                'class'   => 'select2'
                ]
            ])
            ->add('Hobbies', EntityType::class, [
                'expanded' => false,
                'class' => Hobby::class,
                'multiple' => true,
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('h')
                        ->orderBy('h.designation', 'ASC');
                },
                'choice_label' => 'designation', // cela fait le travail à la place de la function public _toString
                'attr'   =>  [
                'class'   => 'select2'
                ]
            ])
            ->add('Job', EntityType::class, [
                'required' => false,
                'class' => Job::class,
                'attr'   =>  [
                'class'   => 'select2'
                ]
            ])
            ->add('photo', FileType::class, [
                'label' => 'Votre image de profil (file img only)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ])
            ->add('editer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => People::class,
        ]);
    }
}