<?php

declare( strict_types = 1 );

use App\Auth;
use App\Config;
use App\Contracts\AuthInterface;
use App\Contracts\SessionInterface;
use App\Contracts\UserProviderServiceInterface;
use App\DataObjects\SessionConfig;
use App\Enum\AppEnvironment;
use App\Enum\SameSite;
use App\Services\UserProviderService;
use App\Session;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookup;
use Symfony\WebpackEncoreBundle\Asset\TagRenderer;
use Symfony\WebpackEncoreBundle\Twig\EntryFilesTwigExtension;
use Twig\Extra\Intl\IntlExtension;

use function DI\create;

return [
    App::class => static function ( ContainerInterface $container ) {
        AppFactory::setContainer( $container );

        $addMiddlewares = require CONFIG_PATH . '/middleware.php';
        $router = require CONFIG_PATH . '/routes/web.php';

        $app = AppFactory::create();

        $router( $app );

        $addMiddlewares( $app );

        return $app;
    },
    Config::class => create( Config::class )->constructor( require CONFIG_PATH . '/app.php' ),
    EntityManager::class => static fn( Config $config ) => EntityManager::create(
        $config->get( 'doctrine.connection' ),
        ORMSetup::createAttributeMetadataConfiguration(
            $config->get( 'doctrine.entity_dir' ),
            $config->get( 'doctrine.dev_mode' )
        )
    ),
    Twig::class => static function ( Config $config, ContainerInterface $container ) {
        $twig = Twig::create( VIEW_PATH, [
            'cache' => STORAGE_PATH . '/cache/templates',
            'auto_reload' => AppEnvironment::isDevelopment( $config->get( 'app_environment' ) ),
        ] );

        $twig->addExtension( new IntlExtension() );
        $twig->addExtension( new EntryFilesTwigExtension( $container ) );
        $twig->addExtension( new AssetExtension( $container->get( 'webpack_encore.packages' ) ) );

        return $twig;
    },
    /**
     * The following two bindings are needed for EntryFilesTwigExtension & AssetExtension to work for Twig
     */
    'webpack_encore.packages' => static fn() => new Packages(
        new Package( new JsonManifestVersionStrategy( BUILD_PATH . '/manifest.json' ) )
    ),
    'webpack_encore.tag_renderer' => static fn( ContainerInterface $container ) => new TagRenderer(
        new EntrypointLookup( BUILD_PATH . '/entrypoints.json' ),
        $container->get( 'webpack_encore.packages' )
    ),
    ResponseFactoryInterface::class => static fn( App $app ) => $app->getResponseFactory(),
    AuthInterface::class => static fn( ContainerInterface $container ) => $container->get( Auth::class ),
    UserProviderServiceInterface::class => static fn( ContainerInterface $container ) => $container->get(
        UserProviderService::class
    ),
    SessionInterface::class => static fn( Config $config ) => new Session(
        new SessionConfig(
            $config->get( 'session.name', '' ),
            $config->get( 'session.flash_name', 'flash' ),
            $config->get( 'session.secure', true ),
            $config->get( 'session.httponly', true ),
            SameSite::tryFrom( $config->get( 'session.samesite', 'lax' ) )
        )
    ),
];
