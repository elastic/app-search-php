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

***

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

### Basic usage

#### Retrieve or create an engine

Most methods of the API require that you have access to an Engine.

To check if an Engine exists and retrieve its configuration, you can use the `Client::getEngine` method :

```php
  $engine = $client->getEngine('my-engine');
```

If the Engine does not exists yet, you can create it by using the `Client::createEngine` method :

```php
  $engine = $client->createEngine('my-engine', 'en');
```

The second parameter (`$language`) is optional or can be set to null. Then the Engine will be created using the `universal` language.
The list of supported language is available here : https://swiftype.com/documentation/app-search/api/engines#multi-language

#### Index some documents

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

#### Search

In order to search in your Engine you can use the `Client::search` method :

```php
    $searchParams = [
      'page'  => ['current' => 1, 'size' => 10];
    ];

    $searchResponse = $client->search('my-engine', 'search text', $searchParams);
```
If you want to match all documents you can use and empty search query `''` as second parameter (`$queryText`).

The `$searchRequestParams` parameter is optional and can be used to use advanced search features. Allowed params are :

Param name      | Documentation URL
--------------- | ----------------------------------------------------------------------
`page`          | https://swiftype.com/documentation/app-search/api/search#paging
`filters`       | https://swiftype.com/documentation/app-search/api/search/filters
`facets`        | https://swiftype.com/documentation/app-search/api/search/facets
`sort`          | https://swiftype.com/documentation/app-search/api/search/sorting
`boosts`        | https://swiftype.com/documentation/app-search/api/search/boosts
`search_fields` | https://swiftype.com/documentation/app-search/api/search/search-fields
`result_fields` | https://swiftype.com/documentation/app-search/api/search/result-fields
`group`         | https://swiftype.com/documentation/app-search/api/search/grouping

The search response will contains at least a meta field and a results field as shown in this example:

```php
[
    'meta' => [
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

### Clients methods

Method      | Description | Documentation
------------|-------------|--------------
**`createCuration`**| Create a new curation.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$queries` (required) <br />   - `$promotedDocIds`<br />   - `$hiddenDocIds`<br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/curations#create)
**`createEngine`**| Creates a new engine.<br/> <br/> **Parameters :** <br />  - `$name` (required) <br />   - `$language`<br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/engines#create)
**`createSynonymSet`**| Create a new synonym set.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$synonyms` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/synonyms#create)
**`deleteCuration`**| Delete a curation by id.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$curationId` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/curations#destroy)
**`deleteDocuments`**| Delete documents by id.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$documentIds` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/documents#partial)
**`deleteEngine`**| Delete an engine by name.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/engines#delete)
**`deleteSynonymSet`**| Delete a synonym set by id.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$synonymSetId` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/synonyms#delete)
**`getApiLogs`**| The API Log displays API request and response data at the Engine level.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$fromDate` (required) <br />   - `$toDate` (required) <br />   - `$currentPage`<br />   - `$pageSize`<br />   - `$query`<br />   - `$httpStatusFilter`<br />   - `$httpMethodFilter`<br />   - `$sortDirection`<br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/logs)
**`getCountAnalytics`**| Returns the number of clicks and total number of queries over a period.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$filters`<br />   - `$interval`<br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/analytics/counts)
**`getCuration`**| Retrieve a curation by id.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$curationId` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/curations#single)
**`getDocuments`**| Retrieves one or more documents by id.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$documentIds` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/documents#get)
**`getEngine`**| Retrieves an engine by name.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/engines#get)
**`getSchema`**| Retrieve current schema for then engine.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/schema#read)
**`getSearchSettings`**| Retrive current search settings for the engine.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/search-settings#show)
**`getSynonymSet`**| Retrieve a synonym set by id.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$synonymSetId` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/synonyms#list-one)
**`getTopClicksAnalytics`**| Returns the number of clicks received by a document in descending order.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$query`<br />   - `$pageSize`<br />   - `$filters`<br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/analytics/clicks)
**`getTopQueriesAnalytics`**| Returns queries anlaytics by usage count.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$pageSize`<br />   - `$filters`<br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/analytics/queries)
**`indexDocuments`**| Create or update documents.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$documents` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/documents#create)
**`listCurations`**| Retrieve available curations for the engine.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$currentPage`<br />   - `$pageSize`<br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/curations#read)
**`listDocuments`**| List all available documents with optional pagination support.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$currentPage`<br />   - `$pageSize`<br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/documents#list)
**`listEngines`**| Retrieves all engines with optional pagination support.<br/> <br/> **Parameters :** <br />  - `$currentPage`<br />   - `$pageSize`<br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/engines#list)
**`listSynonymSets`**| Retrieve available synonym sets for the engine.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$currentPage`<br />   - `$pageSize`<br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/synonyms#get)
**`logClickthrough`**| Send data about clicked results.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$queryText` (required) <br />   - `$documentId` (required) <br />   - `$requestId`<br />   - `$tags`<br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/clickthrough)
**`multiSearch`**| Run several search in the same request.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$queries` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/search#multi)
**`querySuggestion`**| Provide relevant query suggestions for incomplete queries.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$query` (required) <br />   - `$fields`<br />   - `$size`<br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/query-suggestion)
**`resetSearchSettings`**| Reset search settings for the engine.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/search-settings#reset)
**`search`**| Allows you to search over, facet and filter your data.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$queryText` (required) <br />   - `$searchRequestParams`<br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/search)
**`updateCuration`**| Update an existing curation.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$curationId` (required) <br />   - `$queries` (required) <br />   - `$promotedDocIds`<br />   - `$hiddenDocIds`<br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/curations#update)
**`updateDocuments`**| Partial update of documents.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$documents` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/documents#partial)
**`updateSchema`**| Update schema for the current engine.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$schema` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/schema#patch)
**`updateSearchSettings`**| Update search settings for the engine.<br/> <br/> **Parameters :** <br />  - `$engineName` (required) <br />   - `$searchSettings` (required) <br/>|[Endpoint Documentation](https://swiftype.com/documentation/app-search/api/search-settings#update)

## Development

Code for the endpoints is generated automatically using a custom version of [OpenAPI Generator](https://github.com/openapitools/openapi-generator).

The easier way to regenerate endpoints is to use the docker laucher packaged in `vendor/bin`:

```bash
./vendor/bin/swiftype-codegen.sh
```

The custom generator will be built and launched using the following Open API spec file : `resources/api/api-spec.yml`.

You can then commit and PR your endpoint code and modified the api-spec files.

The client class may be changed in some case. Do not forget to include it in your commit!

## FAQ 🔮

### Where do I report issues with the client?

If something is not working as expected, please open an [issue](https://github.com/swiftype/swiftype-app-search-php/issues/new).

### Where can I find the full API documentation ?

Your best bet is to read the [documentation](https://swiftype.com/documentation/app-search).

### Where else can I go to get help?

You can checkout the [Elastic community discuss forums](https://discuss.elastic.co/c/app-search).

## Contribute 🚀

We welcome contributors to the project. Before you begin, a couple notes...

+ Before opening a pull request, please create an issue to [discuss the scope of your proposal](https://github.com/swiftype/swiftype-app-search-php/issues).
+ Please write simple code and concise documentation, when appropriate.

## License 📗

[Apache 2.0](https://github.com/swiftype/swiftype-app-search-php/blob/master/LICENSE) © [Elastic](https://github.com/elastic)

Thank you to all the [contributors](https://github.com/swiftype/swiftype-app-search-php/graphs/contributors)!

