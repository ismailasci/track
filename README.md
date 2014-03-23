## track event based tracking system with multiple backend support

track is an event based tracking system that helps you to collect and query statistical data
based on the actions from your customers, users or visitors.

With track you can;

- collect page views, clicks, conversions or any kind of user actions for your website, API or mobile app.
- query collected data with complex filters. (currently only with native queries)
- create funnels (in progress)

#### Features

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


#### Examples

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
