# PHP client for the Swiftype App Search API

[![CircleCI](https://circleci.com/gh/swiftype/swiftype-app-search-php.svg?style=svg&circle-token=c5aa66b0ee683b0f485c414eb6554837c29cc150)](https://circleci.com/gh/swiftype/swiftype-app-search-php)

### Requirements

Using this client assumes that you have already created an App Search account on https://swiftype.com/ or you have a self managed version of App Search available.

## Installation

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

#### Logging

The repo is still WIP and documentation is coming.

### Client usage

#### Basic usage

##### Create an engine

##### Index some documents

##### Search

#### Clients methods

## Contributions

To contribute code, please fork the repository and submit a pull request.

### Developers

Code for the endpoints are generated automatically using a custom version of [OpenAPI Generator](https://github.com/openapitools/openapi-generator).

The easier way to regenerate endpoints is to use the docker laucher packaged in `resources/dev`:

```bash
./dev/resources/generate-endpoints-code
```

The custom generator will be build and launched using the following the `resources/api/api-spec.yml` file.

You can then commit and PR your endpoint code and modified api-spec files.
The client class may be changed in some case. Do not forget to include it in your commit.
