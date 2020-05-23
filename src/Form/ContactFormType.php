<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, [
            "attr" => [
                "placeholder" => "John Doe",
            ],
            "row_attr" => [
                "class" => "input-item"
            ],
            "label" => "Your Name"
        ])
        ->add('email', EmailType::class, [
            "attr" => [
                "placeholder" => "doe@john.de",
            ],
            "row_attr" => [
                "class" => "input-item"
            ],
            "label" => "Your E-Mail Address"
        ])
        ->add('type', ChoiceType::class, [
            "label" => "Type",
            "choices" => [
                "Feedback" => "Feedback",
                "Bugreport" => "Bugreport",
                "General Contact" => "General Contact",
                "Idea / Suggestion" => "Idea / Suggestion"
            ],
            "row_attr" => [
                "class" => "input-item"
            ],
        ])
        ->add('message', TextareaType::class, [
            "label" => "Your Message",
            "row_attr" => [
                "class" => "input-item"
            ],
        ])
        ->add('submit', SubmitType::class, [
            "label" => "Submit"
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
