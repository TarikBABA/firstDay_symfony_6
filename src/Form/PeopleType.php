<?php

namespace App\Form;

use App\Entity\Hobby;
use App\Entity\People;
use App\Entity\Profile;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

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
                'required' => false,
                'class' => Profile::class,
                'multiple' => false,
            ])
            ->add('Hobbies', EntityType::class, [
                'expanded' => true,
                'class' => Hobby::class,
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('h')
                        ->orderBy('h.designation', 'ASC');
                },
                'choice_label' => 'designation' // cela fait le travail à la place de la function public _toString
            ])
            ->add('Job')
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