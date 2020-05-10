<?php

namespace App\Infrastructure\Http\Form;

use App\Application\UseCase\User\ResetPassword\Request\Command;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordRequestForm extends AbstractType
{

    private const EMAIL = 'email';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(self::EMAIL, EmailType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class,
            'empty_data' => fn (FormInterface $form): Command => new Command($form->get(self::EMAIL)->getData()),
            'translation_domain' => 'common'
        ]);
    }
}
