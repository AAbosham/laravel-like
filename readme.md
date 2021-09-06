# Likeable

Likeable is a package for Laravel.

## Installation

From the command line, run:

```
composer require aabosham/like
```

```
php artisan migrate
```

```php
<?php

namespace App;

use Aabosham\Likeable\Likeable;

class Post extends Model
{
    use Likeable;

    ...
}
```

```php
$model->isLikedBy();


$model->liked();

$model->likedsCount();
```

### Scoping

```php
$models = Model::likedBy(User $user)->get();
```
