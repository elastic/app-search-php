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


## Contributions

To contribute code, please fork the repository and submit a pull request.

### Developers

Code for the endpoints are generated automatically using a custom version of [OpenAPI Generator](https://github.com/openapitools/openapi-generator).

The easier way to regenerate endpoints is to use the docker laucher packaged in `vendor/bin`:

```bash
./vendor/bin/swiftype-codegen.sh
```

The custom generator will be build and launched using the following the `resources/api/api-spec.yml` file.

You can then commit and PR your endpoint code and modified api-spec files.
The client class may be changed in some case. Do not forget to include it in your commit.
