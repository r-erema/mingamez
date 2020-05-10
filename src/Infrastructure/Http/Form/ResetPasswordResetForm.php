<?php
namespace App\Infrastructure\Http\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordResetForm extends AbstractType
{
    public const TOKEN_OPT_KEY = 'token';

    private const PASSWORD = 'password';
    private string $token;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->token = $options[self::TOKEN_OPT_KEY];
        $builder->add(self::PASSWORD, PasswordType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Command::class,
            'empty_data' => fn (FormInterface $form): Command => new Command(
                $this->token,
                $form->get(self::PASSWORD)->getData()
            ),
            'translation_domain' => 'common',
            self::TOKEN_OPT_KEY => null
        ]);
    }

}
