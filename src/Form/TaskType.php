<?php

namespace App\Form;
use App\Entity\User;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
            ->add('users', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC')
                        ->andWhere('u.name != :heros')
                        ->setParameter('heros', 'ProfessorX');
                },
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('finishDate', DateType::class, [
                'placeholder' => 'Select a value',
                'widget' => 'choice',
                'required' => false,
            ])
            ->add('priority', ChoiceType::class, [
                'choices'  => [
                    'Low' => 1,
                    'Medium' =>2,
                    'High' => 3,
                ],
            ])
            ->add('done', ChoiceType::class, [
                'choices'  => [
                    'To do' => 'To do',
                    'In progress' => 'In progress',
                    'Done' => 'Done',
                ],
            ])
            ->add('submit', SubmitType::class, [
                "label" => 'submit',
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);

    }
}