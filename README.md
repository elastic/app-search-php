# PHP client for the Swiftype App Search API

[![CircleCI](https://circleci.com/gh/swiftype/swiftype-app-search-php.svg?style=svg)](https://circleci.com/gh/swiftype/swiftype-app-search-php)

## Installation

The repo is still WIP and documentation is coming.

## Usage

The repo is still WIP and documentation is coming.

## Contributions

To contribute code, please fork the repository and submit a pull request.

### Developers

Code for the endpoints are generated automatically using a custom version of [OpenAPI Generator](https://github.com/openapitools/openapi-generator).

The easier way to regenerate endpoints is to use the docker laucher packaged in `resources/dev` :

```bash
./dev/resources/generate-endpoints-code
```

The custom generator will be build and launched using the following the `resources/api/api-spec.yml` file.

You can then commit and PR your endpoint code and modified api-spec files.
The client class may be changed in some case. Do not forget to include it in your commit.
