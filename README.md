# Podium

A modern [Podio](https://www.podio.com/) API client compatible with Laravel.

## Installation

    composer require juanparati/podium


### Laravel setup
This library can is compatible with Laravel. The following command will deploy the configuration file:

```
artisan vendor:publish --provider="Juanparati\Podium\Providers\PodiumProvider"
```

## How to use it?


### Client instance and authentication

```php
$client = new \Juanparati\Podium\Podium(
        session: '12346',
        clientId: 'myClientId',
        clientSecret: 'myClientSecret'
);

$client->authenticate(
    new \Juanparati\Podium\Auths\AppAuth(
        appId: '987654',      
        appToken: 'myAppToken'
    )
);
```

### Request a single item

```php
$item = (new \Juanparati\Podium\Requests\ItemRequest($client))->get(itemId: 11111111);
```

### Request all the items of an App

```php
$models = (new \Juanparati\Podium\Requests\ItemRequest($client))->filter(appId: 987654);
```

or alternatively with custom options:

```php
$models =  (new \Juanparati\Podium\Models\ItemFilterModel([], $client))           
        ->setSortBy('last_edit_on')
        ->setSortDesc(true)
        ->setLimit(5);
```

Read all the items. Note that items() method is a generator, and it will automatically request the additional pages.


```php
$itemNum = 0;

foreach ($models->items() as $item) {
    print_r($item->originalValues())
    
    // The setLimit options indicate the limit of items per page to the request,
    // but the generator will automatically request the next page.
    // In order to limit the number of results we should manually limit the results.
    if ($itemNum === 10)
        break;
        
    $itemNum++;   
}
```


### Reading item values.

This library provides to ways to read the values of the items.

1. Obtain the original values using the `originalValues` method:

```php
$item->originalValues();
```

2. Obtain the simplified values using the `decodeValue` method:

```php
$item->decodeValue();
```

Both values will transverse all the values tree, however you can reference to an specific value.

```php
// Accessing to the fields
$item->fields->decodeValue();
```

or 

```php
// Accessing to specific values
$item->fields->title;
$item->fields->{'my-custom-field'};
```

### Saving items

⚠️ **Note**: Saving items is still an experimental feature. Use this feature at your own risk.

It's possible to save/update items.

```php
// Accessing to specific values
$item->fields->title = "My new title";
$item->save(silent: false, hook: true); // Will perform silent update calling the bounded hooks 
```


### Inserting new items

⚠️ **Note**: Saving items is still an experimental feature. Use this feature at your own risk.

```php
$attr = [
    'title' => 'My new title'
    'revenue' => ['currency' => 'DKK', 'value' => 123.34];
];

(new \Juanparati\Podium\Requests\ItemRequest($client))->create(
    appId: 987654,
    attr: $attr,
    silent: false,
    hook: false
);
```

### Using different field keys

It's possible to retrieve the field keys using the following formats:

- external_id (Default).
- external_id in snake case format.
- field_id.

For example sometime is very suitable to retrieve the field keys in snake case so it's easiest to manipulate.


```php
$item = (new \Juanparati\Podium\Requests\ItemRequest($client))->get(itemId: 11111111);
echo $item->fields->{'my-long-named-field'};

$item->setOptions([
    \Juanparati\Podium\Models\ItemFieldModel::class => [
        \Juanparati\Podium\Models\ItemFieldModel::OPTION_KEY_AS => \Juanparati\Podium\Models\ItemFieldModel::KEY_AS_SNAKECASE,
    ]
);

echo $item->fields->my_long_named_field;

$item->setOptions([
    \Juanparati\Podium\Models\ItemFieldModel::class => [
        \Juanparati\Podium\Models\ItemFieldModel::OPTION_KEY_AS => \Juanparati\Podium\Models\ItemFieldModel::KEY_AS_FIELD_ID,
    ]
);

echo $item->fields->{'12345567'};
```


### Transforming date and datetime to different time zones and formats

```php
$item = (new \Juanparati\Podium\Requests\ItemRequest($client))->get(itemId: 11111111);

$item->setOptions([
    \Juanparati\Podium\Models\ItemFieldModel::class => [
        \Juanparati\Podium\Models\ItemFields\DateItemField::class => [
            \Juanparati\Podium\Models\ItemFields\DateItemField::OPTION_TIMEZONE => 'Europe/Copenhagen',
            \Juanparati\Podium\Models\ItemFields\DateItemField::OPTION_FORMAT => \Juanparati\Podium\Models\ItemFields\DateItemField::FORMAT_TIMESTAMP
        ]
    ]
);
```

