(function() {
    var client = algoliasearch('2KK3NN704W', '461ccdf3575b68327b145eaf240d2be7');
    var index = client.initIndex('products_index');
    var enterPressed = false;
    //initialize autocomplete on search input (ID selector must match)
    autocomplete('#aa-search-input',
        { hint: false }, {
            source: autocomplete.sources.hits(index, { hitsPerPage: 6 }),
            //value to be displayed in input control after user's suggestion selection
            displayKey: 'name',
            //hash of templates used when rendering dataset
            templates: {
                //'suggestion' templating function used to render a single suggestion
                suggestion: function (suggestion) {
                    const markup = `
                        <div class="d-flex justify-content-between align-items-center">
                            <span class='mr-md-2'>
                                <img src="${window.location.origin}/storage/${suggestion.image}" alt="img" class="algolia-thumb">
                            </span>
                            <span>
                            ${suggestion._highlightResult.name.value}
                            </span>
                        </div>
                    `;

                    return markup;
                },
                empty: function (result) {
                  const nothing = 
                    `<div class="algolia-result p-3">
                      Sorry, we did not find any results for ${result.query}"
                    </div>`
                    ;
                    return nothing;
                }
            }
        }).on('autocomplete:selected', function (_event, suggestion) {
            window.location.href = window.location.origin + '/product/' + suggestion.slug;
            enterPressed = true;
        }).on('keyup', function(event){
            if (event.keyCode == 13 && !enterPressed) {
                window.location.href = window.location.origin + '/product/search?q=' + document.getElementById('aa-search-input').value;
            }
        })
})();