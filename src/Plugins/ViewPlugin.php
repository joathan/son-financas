<?php
declare(strict_types=1);
namespace SONFin\Plugins;

use Interop\Container\ContainerInterface;
use SONFin\ServiceContainerInterface;
use SONFin\View\Twig\TwigGlobals;
use SONFin\View\ViewRenderer;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Twig\Extension\DebugExtension;

class ViewPlugin implements PluginInterface
{
    public function register(ServiceContainerInterface $container)
    {
        $container->addLazy("twig", function (ContainerInterface $container) {
            $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../../templates');
            $twig = new \Twig_Environment($loader, [
                'debug' => true,
            ]);

            $translator = new Translator('es');
            $translator->addLoader('yaml', new YamlFileLoader());

            $locale = $_SESSION['locale'] ?? 'es';
            $translator->setLocale($locale);

            $translator->addResource('yaml', __DIR__ . '/../../translations/messages.en.yaml', 'en');
            $translator->addResource('yaml', __DIR__ . '/../../translations/messages.es.yaml', 'es');
            $translator->addResource('yaml', __DIR__ . '/../../translations/messages.pt_BR.yaml', 'pt_BR');

            $auth = $container->get('auth');

            $generator = $container->get('routing.generator');
            $twig->addExtension(new TwigGlobals($auth));
            $twig->addExtension(new DebugExtension());
            $twig->addExtension(new TranslationExtension($translator));

            $twig->addFunction(new \Twig_SimpleFunction('route',
                function (string $name, array $params = []) use ($generator) {
                    return $generator->generate($name, $params);
            }));
            return $twig;
        });

        $container->addLazy('view.renderer', function (ContainerInterface $container) {
            $twigEnvironment = $container->get('twig');
            return new ViewRenderer($twigEnvironment);
        });
    }
}
