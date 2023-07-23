Container Usage Documentation

# Container Usage Documentation

The **Container** class is a basic dependency injection container that allows you to manage dependencies and resolve them automatically within your application. It provides a convenient way to handle object instantiation and dependency injection, making your code more modular and maintainable.

## Getting Started

To begin using the Container class, you first need to include the relevant PHP files and create an instance of the container:

```php
    use Tests\Core\Container;
    require 'vendor/autoload.php';
    $container = Container::getInstance();
```

## Binding Classes to the Container

You can bind classes or closures to the container using the `bind` method. When you request an instance from the container, it will automatically resolve the bound dependencies for you.

```php
    // Binding a customized MysqlDb instance to the container
    $container->bind(MysqlDb::class, function () {
        // Custom configuration for MysqlDb, if needed
        $db = new Db(/*...*/);
        return new MysqlDb($db);
    });
```

## Resolving Dependencies

When you call the `get` method with a class name, the container will resolve and return an instance of that class, along with its dependencies.

```php
    // Retrieving an instance of MysqlDb from the container
    $mysqlDb = $container->get(MysqlDb::class);
    // Retrieving an instance of User from the container
    $user = $container->get(User::class);
```

## Dependency Injection

The container can automatically inject dependencies into closures when using the `call` method. This allows you to resolve dependencies for a specific function or method.

```php
    // Using the call method to automatically resolve dependencies for a closure
    $container->call(function (Logger $logger, EntityManager $em) use ($user) {
        // Here, the logUserData method of the User class will be called with resolved dependencies
        $user->logUserData($logger);
        $data = $em->getData();
        var_dump($data);
    });
```

## Error Handling

If a class is not found in the container, or if there are unresolved dependencies, the Container class will throw an `Exception`. It is important to handle these exceptions properly in your application to ensure smooth operation.

```php
    try {
        $invalidInstance = $container->get(NonExistentClass::class);
    } catch (Exception $e) {
        // Handle the exception here
    }
```

## Conclusion

The Container class simplifies the management of dependencies in your PHP application. By binding classes and closures, you can easily resolve instances with their dependencies, making your code more organized and easier to maintain.
