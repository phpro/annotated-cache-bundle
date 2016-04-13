[![Build status](https://api.travis-ci.org/phpro/annotated-cache-bundle.svg)](http://travis-ci.org/phpro/annotated-cache-bundle)
[![Insight](https://img.shields.io/sensiolabs/i/042d537e-9bc2-4dd7-b9d8-f165a5f5039f.svg)](https://insight.sensiolabs.com/projects/042d537e-9bc2-4dd7-b9d8-f165a5f5039f)
[![Installs](https://img.shields.io/packagist/dt/phpro/annotated-cache-bundle.svg)](https://packagist.org/packages/phpro/annotated-cache-bundle/stats)
[![Packagist](https://img.shields.io/packagist/v/phpro/annotated-cache-bundle.svg)](https://packagist.org/packages/phpro/annotated-cache-bundle)

# Annotated Cache bundle

## Installation

```sh
composer require phpro/annotated-cache-bundle
```

```php
# AppKernel.php
public function registerBundles()
{
    $bundles = [
        // Bundles ....
        new Phpro\AnnotatedCacheBundle\AnnotatedCacheBundle(),
    ];

    return $bundles;
}
```

## Configuration

```yaml
# Default configuration for extension with alias: "annotated_cache"
annotated_cache:
    key_generator: phpro.annotated_cache.keygenerator.expressions
    proxy_config:
        cache_dir: '%kernel.cache_dir%/annotated_cache'
        namespace: AnnotatedCacheGeneratedProxy
        register_autoloader: true
    pools:
        # Example:
        poolname:
            service: service.key
```

### Generate proxies
```yaml
services:
    app.manager.products:
        class: App\Manager\ProductsManager
        tags:
            - { name: 'annotated_cache.eligable' }
```

You can register your own services to use a caching proxy instead. 
 The only thing you will have to do is to tag your service with the `annotated_cache.eligable` tag.


### Configure your own interceptor
```yaml
services:
    app.interceptor.my_interceptor:
        class: App\Interceptor\MyInterceptor
        tags:
            - { name: 'annotated_cache.interceptor' }
```

Adding your own functionality is easy. 
 Create your own `InterceptorInterface` and tag it with the `annotated_cache.interceptor` tag.
