<?php

namespace App\Form;

use App\DTO\ContactDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use function Symfony\Component\Translation\t;

class ContactType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('name', textType::class, [
        'empty_data' => '',
        'label' => t('contactForm.name')
      ])
      ->add('email', emailType::class, [
        'empty_data' => '',
        'label' => t('contactForm.email')
      ])
      ->add('message', textareaType::class, [
        'empty_data' => '',
        'label' => t('contactForm.message')
      ])
      ->add('save', SubmitType::class, [
        'label' => t('contactForm.submit')
      ])
      ->add('service', ChoiceType::class, [
        'label' => t('contactForm.service'),
        'choices'  => [
          'choices.compta' => 'compta@demo.fr',
          'choices.support' => 'support@demo.fr',
          'choices.marketing' => 'marketing@demo.fr',
        ],
      ]);;
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => ContactDTO::class,
    ]);
  }
}
