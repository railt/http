export default function (schema) {
    let search = window.location.search;

    let parameters = {};

    search.substr(1).split("&").forEach(function (entry) {
        let eq = entry.indexOf("=");
        if (eq >= 0) {
            parameters[decodeURIComponent(entry.slice(0, eq))] =
                decodeURIComponent(entry.slice(eq + 1));
        }
    });

    if (parameters.variables) {
        try {
            parameters.variables = JSON.stringify(JSON.parse(parameters.variables), null, 2);
        } catch (e) {
            console.error(e);
        }
    }

    function updateURL() {
        let newSearch = "?" + Object.keys(parameters).filter(function (key) {
            return Boolean(parameters[key]);
        }).map(function (key) {
            return encodeURIComponent(key) + "=" + encodeURIComponent(parameters[key]);
        }).join("&");
        history.replaceState(null, null, newSearch);
    }

// Defines a GraphQL fetcher using the fetch API.
    function graphQLFetcher(graphQLParams) {
        graphQLParams.schema = schema.getValue();

        return fetch("/", {
            method: "post",
            headers: {
                "Accept": "application/json",
                "Content-Type": "application/json",
            },
            body: JSON.stringify(graphQLParams),
            credentials: "include",
        }).then(function (response) {
            return response.text();
        }).then(function (responseBody) {
            try {
                return JSON.parse(responseBody);
            } catch (error) {
                return responseBody;
            }
        });
    }

    // When the query and variables string is edited, update the URL bar so
    // that it can be easily shared
    function onEditQuery(newQuery) {
        parameters.query = newQuery;
        updateURL();
    }

    function onEditVariables(newVariables) {
        parameters.variables = newVariables;
        updateURL();
    }

    function onEditOperationName(newOperationName) {
        parameters.operationName = newOperationName;
        updateURL();
    }

    // Render <GraphiQL /> into the body.
    ReactDOM.render(
        React.createElement(GraphiQL, {
            fetcher: graphQLFetcher,
            query: parameters.query,
            variables: parameters.variables,
            operationName: parameters.operationName,
            onEditQuery: onEditQuery,
            onEditVariables: onEditVariables,
            onEditOperationName: onEditOperationName
        }),
        document.getElementById("graphiql")
    );
}