## track event based tracking system with multiple backend support

[![Build Status](https://travis-ci.org/ismailasci/track.svg?branch=master)](https://travis-ci.org/ismailasci/track)
[![Latest Stable Version](https://poser.pugx.org/asci/track/v/stable.png)](https://packagist.org/packages/asci/track) [![Total Downloads](https://poser.pugx.org/asci/track/downloads.png)](https://packagist.org/packages/asci/track)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4f7480b0-5705-48a4-a019-aad522f97f58/mini.png)](https://insight.sensiolabs.com/projects/4f7480b0-5705-48a4-a019-aad522f97f58)

track is an event based tracking system that helps you to collect and query statistical data
based on the actions from your customers, users or visitors.

With track you can;

- collect page views, clicks, conversions or any kind of user actions for your website, API or mobile app.
- query collected data with complex filters. (currently only with native queries)
- create funnels (in progress)

### Features

- Collect data with unlimited custom parameters.

```php
new Event(
    'Purchase',
    array(
        'Affiliate'     => 'Amazon.de',
        'Category'      => 'Smartphones',
        'Product Name'  => 'iPhone 5s Black 64GB',
        'Price'         => 549.99,
        //...
    )
);
```

- Query on collected data and create analytics.
- Create funnels (in progress)
- Duplicate/Unique filtering. (in progress)
- Multiple database backend options. (currently only mongodb and postgresql with hstore)
- Easily extendable architecture.
- Full test coverage with [phpspec](http://phpspec.net/).

### Events

An `event`  basically defines the same thing as in its name. Any kind of action made by your customers, users or applications can be described as an event. For instance; 'Page View', 'Button Click', 'Purchase', 'API Request', 'Exception', etc.

Track defines events within the special `Event` objects. An `Event` object can carry many special information with it among the custom ones. For instance an Event object can have; 

- IP address, 
- a unique key to distinguish your users, 
- the time of the event,
- requested URL,
- product category,
- pruduct name,
- customer source,
- and many other defined by you.


Track comes with bunch of built-in events specicialized for different purposes. All the built-in event can be found under the `Track\Event` namespace. You can also define your own customized events for your needs.

### Built-in Events

Track comes with bunch of built-in events specicialized for different purposes. All the built-in event can be found under the `Track\Event` namespace.

#### 1. Track\Event

This is the base event of Track and provides very basic expectations from an event. All the other events has to be extending to it, this means that every event has to/will have at least a `name`, a `timestamp` and a unique `id`. If you don't give these values while initializing your event object they will get automatically generated by Track.

```php
$event = new Track\Event(
    'Page View',                // Event name
    array(
        'utm_source' => 'partner_x',
        'utml_medium' => 'affiliate',
    )
);

print_r($event->toArray());

/*
will give you like the following 

array(
    'timestamp' => 'timestamp',
    'id' => 'random_string',
    'utm_source' => 'partner_x',
    'utml_medium' => 'affiliate'
)
*/

```

### Examples

**Store an event**

```php
use Track\Client;
use Track\Storage\MongoDBStorage;
use Track\Event\Event;

// Configure your mongodb connection
$mongoClient = new \MongoClient();
$mongoDB = $mongoClient->selectDB('stats');

// Initialize the storage
$storage = new MongoDBStorage($mongoDB);

$client = new Client($storage);

// Create an event for your needs
$event = new Event(
    'Purchase',
    array(
        'Affiliate' => 'Amazon.de',
        'Category'  => 'Smartphones',
        'Product Name'  => 'iPhone 5s Black',
        'Price' => 549.99,
    )
);

// Store the event
$client->track($event);
```

**Query events**

```php
use Track\Query;
use Track\Storage\MongoDBStorage;

// Configure your mongodb connection
$mongoClient = new \MongoClient();
$mongoDB = $mongoClient->selectDB('stats');

// Initialize the storage
$storage = new MongoDBStorage($mongoDB);

$query = new Query($storage);
$results = $query->native(array('name' => 'Purchase'));

/*
will return

array(
    array(
        'Affiliate' => 'Amazon.de',
        'Category'  => 'Smartphones',
        'Product Name'  => 'iPhone 5s Black',
        'Price' => 549.99,
        ...
    ),
    ...
)
*/

```

#### PostgreSQL hstore Setup

```sql
CREATE EXTENSION IF NOT EXISTS hstore;

CREATE TABLE IF NOT EXISTS events (
  id serial PRIMARY KEY,
  data hstore
);

-- Some hstore raw query examples

SELECT data
FROM events
WHERE (data->'timestamp')::int > 12345678;

SELECT data
FROM events
WHERE data->'name'= 'Purchase';

```
