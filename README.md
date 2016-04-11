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
    pools:
        # Example:
        poolname:
            service: service.key
```

### Generate proxies
```
services:
    app.manager.products:
        class: App\Manager\ProductsManager
        tags:
            - { name: 'annotated_cache.eligable' }
```

### Configure your own interceptor
```
services:
    app.interceptor.my_interceptor:
        class: App\Interceptor\MyInterceptor
        tags:
            - { name: 'annotated_cache.interceptor' }
```