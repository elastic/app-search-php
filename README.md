<p align="center"><img src="https://github.com/swiftype/swiftype-app-search-php/blob/master/logo-app-search.png?raw=true" alt="Elastic App Search Logo"></p>

<p align="center"><a href="https://circleci.com/gh/swiftype/swiftype-app-search-php"><img src="https://circleci.com/gh/swiftype/swiftype-app-search-php.svg?style=svg&circle-token=c5aa66b0ee683b0f485c414eb6554837c29cc150" alt="CircleCI buidl"></a></p>

> A first-party PHP client for building excellent, relevant search experiences with [Elastic App Search](https://www.elastic.co/cloud/app-search-service).

## Contents

- [Getting started](#getting-started-)
- [Usage](#usage)
- [Development](#development)
- [FAQ](#faq-)
- [Contribute](#contribute-)
- [License](#license-)

---

## Getting started 🐣

Using this client assumes that you have already created an App Search account on https://swiftype.com/ or you have a [self managed version](https://swiftype.com/documentation/app-search/self-managed/overview) of App Search available.

You can install the client in your project by using composer:

```bash
composer require swiftype/swiftype-app-search-php
```

## Usage

### Configuring the client

#### Basic client instantiation

To instantiate a new client you can use `\Swiftype\AppSearch\ClientBuilder`:

```php
  $apiEndpoint   = 'http://localhost:3002/';
  $apiKey        = 'private-XXXXXXXXXXXX';
  $clientBuilder = \Swiftype\AppSearch\ClientBuilder::create($apiEndpoint, $apiKey);

  $client = $clientBuilder->build();
```

**Notes:**

- The resulting client will be of type `\Swiftype\AppSearch\Client`

- You can find the API endpoint and your API key URL in your App Search account: https://app.swiftype.com/as/credentials.

- You can use any type of API Key (private, public or admin). The client will throw an exception if you try to execute an action that is not authorized for the key used.

#### Basic usage

##### Retrieve or create an engine

Most methods of the API require that you have access to an Engine.

To check if an Engine exists and retrieve its configuration, you can use the `Client::getEngine` method :

```php
  $engine = $client->getEngine('my-engine');
```

If the Engine does not exists yet, you can create it by using the `Client::createEngine` method :

```php
  $engine = $client->createEngine(['name' => 'my-engine', 'language' => 'en']);
```

The language parameter is optional or can be set to null. Then the Engine will be created using the `universal` language.
The list of supported language is available here : https://swiftype.com/documentation/app-search/api/engines#multi-language

##### Index some documents

In order to index some documents in the Engine you can use the `Client::indexDocuments` method :

```php
    $documents = [
      ['id' => 'first-document', 'name' => 'Document name', 'description' => 'Document description'],
      ['id' => 'other-document', 'name' => 'Other document name', 'description' => 'Other description'],
    ];

    $indexingResults = $client->indexDocuments('my-engine', $documents);
```

The `$indexingResults` array will contains the result of the indexation of each documents. You should always check the content of the result.

Full documentation is available here : https://swiftype.com/documentation/app-search/api/documents#create.

##### Search

In order to search in your Engine you can use the `Client::search` method :

```php
    $searchRequest = [
      'query' => 'search text',
      'page'  => ['current' => 1, 'size' => 10];
    ];

    $searchResponse = $client->search('my-engine', $searchRequest);
```
Search request should contains at least a query (use `''` to match all docs).

The page param allow you to configure pagination and is optional.

Other allowed params are :

Param name|Documentation URL
----------|-----------------
`filters` | https://swiftype.com/documentation/app-search/api/search/filters
`facets` | https://swiftype.com/documentation/app-search/api/search/facets
`sort` | https://swiftype.com/documentation/app-search/api/search/sorting
`boosts` | https://swiftype.com/documentation/app-search/api/search/boosts
`search_fields` | https://swiftype.com/documentation/app-search/api/search/search-fields
`result_fields` | https://swiftype.com/documentation/app-search/api/search/result-fields
`group` | https://swiftype.com/documentation/app-search/api/search/grouping

The search response will contains at least a meta field and a results field as shown in this example:

```php
[
    'meta' = [
      'warnings' => [],
      'page' => [
        'current' => 1,
        'total_pages' => 1,
        'total_results' => 1,
        'size' => 10
      ],
      'request_id' => 'feff7cf2359a6f6da84586969ef0ca89'
    ],
    'results' => [
      [
        'id' => ['raw' => 'first-document'],
        'name' => ['raw' => 'Document name'],
        'description' => ['raw' => ['Document description']
      ]
    ]
  ]
]
```


#### Clients methods

Method name |Parameters| Description
------------|----------|------------
`createCuration` | - `$engineName` (required) <br /> - `$curationData` (required)  | Create a new curation.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/curations#create)
`createEngine` | - `$engine` (required)  | Creates a new engine.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/engines#create)
`createSynonymSet` | - `$engineName` (required) <br /> - `$synonymSetData` (required)  | Create a new synonym set.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/synonyms#create)
`deleteCuration` | - `$engineName` (required) <br /> - `$curationId` (required)  | Delete a curation by id.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/curations#destroy)
`deleteDocuments` | - `$engineName` (required) <br /> - `$documentIds` (required)  | Delete documents by id.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/documents#partial)
`deleteEngine` | - `$engineName` (required)  | Delete an engine by name.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/engines#delete)
`deleteSynonymSet` | - `$engineName` (required) <br /> - `$synonymSetId` (required)  | Delete a synonym set by id.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/synonyms#delete)
`getCuration` | - `$engineName` (required) <br /> - `$curationId` (required)  | Retrieve a curation by id.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/curations#single)
`getDocuments` | - `$engineName` (required) <br /> - `$documentIds` (required)  | Retrieves one or more documents by id.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/documents#get)
`getEngine` | - `$engineName` (required)  | Retrieves an engine by name.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/engines#get)
`getSchema` | - `$engineName` (required)  | Retrieve current schema for then engine.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/schema#read)
`getSearchSettings` | - `$engineName` (required)  | Retrive current search settings for the engine.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/search-settings#show)
`getSynonymSet` | - `$engineName` (required) <br /> - `$synonymSetId` (required)  | Retrieve a synonym set by id.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/synonyms#list-one)
`indexDocuments` | - `$engineName` (required) <br /> - `$documents` (required)  | Create or update documents.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/documents#create)
`listCurations` | - `$engineName` (required) <br /> - `$listParams` | Retrieve available curations for the engine.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/curations#read)
`listDocuments` | - `$engineName` (required) <br /> - `$listParams` | List all available documents with optional pagination support.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/documents#list)
`listEngines` | - `$listParams` | Retrieves all engines with optional pagination support.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/engines#list)
`listSynonymSets` | - `$engineName` (required) <br /> - `$listParams` | Retrieve available synonym sets for the engine.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/synonyms#get)
`multiSearch` | - `$engineName` (required) <br /> - `$queries` (required)  | Run several search in the same request.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/search#multi)
`resetSearchSettings` | - `$engineName` (required)  | Reset search settings for the engine.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/search-settings#reset)
`search` | - `$engineName` (required) <br /> - `$searchRequest` (required)  | Allows you to search over, facet and filter your data.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/search)
`sendClick` | - `$engineName` (required) <br /> - `$clickData` (required)  | Send data about clicked results.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/clickthrough)
`updateCuration` | - `$engineName` (required) <br /> - `$curationId` (required) <br /> - `$curationData` (required)  | Update an existing curation.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/curations#update)
`updateDocuments` | - `$engineName` (required) <br /> - `$documents` (required)  | Partial update of documents.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/documents#partial)
`updateSchema` | - `$engineName` (required) <br /> - `$schema` (required)  | Update schema for the current engine.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/schema#patch)
`updateSearchSettings` | - `$engineName` (required) <br /> - `$searchSettings` (required)  | Update search settings for the engine.<br />[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/search-settings#update)

## Development

Code for the endpoints are generated automatically using a custom version of [OpenAPI Generator](https://github.com/openapitools/openapi-generator).


The easier way to regenerate endpoints is to use the docker laucher packaged in `vendor/bin`:


```bash
./vendor/bin/swiftype-codegen.sh
```

The custom generator will be built and launched using the following the `resources/api/api-spec.yml` file.

You can then commit and PR your endpoint code and modified api-spec files.

The client class may be changed in some case. Do not forget to include it in your commit.

## FAQ 🔮

### Where do I report issues with the client?

If something is not working as expected, please open an [issue](https://github.com/swiftype/swiftype-app-search-php/issues/new).

### Where can I learn more about App Search?

Your best bet is to read the [documentation](https://swiftype.com/documentation/app-search).

### Where else can I go to get help?

You can checkout the [Elastic App Search community discuss forums](https://discuss.elastic.co/c/app-search).

## Contribute 🚀

We welcome contributors to the project. Before you begin, a couple notes...

- Prior to opening a pull request, please create an issue to [discuss the scope of your proposal](https://github.com/swiftype/swiftype-app-search-php/issues).
- Please write simple code and concise documentation, when appropriate.

## License 📗

[Apache 2.0](https://github.com/swiftype/swiftype-app-search-php/blob/master/LICENSE) © [Elastic](https://github.com/elastic)

Thank you to all the [contributors](https://github.com/swiftype/swiftype-app-search-php/graphs/contributors)!
