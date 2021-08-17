(function() {
    const client = algoliasearch('2KK3NN704W', '461ccdf3575b68327b145eaf240d2be7');
    const index = client.initIndex('specs');
    var enterPressed = false;
    let tableSpec = $('#table-spec')
    //initialize autocomplete on search input (ID selector must match)
    autocomplete('#specs-search',
        { hint: false }, {
            source: autocomplete.sources.hits(index, { hitsPerPage: 10 }),
            //value to be displayed in input control after user's suggestion selection
            displayKey: 'name',
            //hash of templates used when rendering dataset
            templates: {
                //'suggestion' templating function used to render a single suggestion
                suggestion: function (suggestion) {
                    const markup = `
                        <div class="d-flex justify-content-between align-items-center">
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
            $('#tbody').append(temp(suggestion.id, suggestion.name))
            let item = $('#tbody tr').length
            if (item > 0) {
               tableSpec.removeClass('d-none')
               console.log(item);
            }
            enterPressed = true;
        })
})();

function destroy(id) {
    let tableSpec = $('#table-spec')
    let item = $(`tr#${id}`)
    item.remove()
    let tr =  $('#tbody tr').length

    if (tr == 0) {
      tableSpec.addClass('d-none')
    }
}

function temp(id, name) {
    let comp = `<tr id=${id}>
                <td>${name}</td>
                    
                <td>

                  <input class="form-control form-control-sm border-light shadow-none" type="text" name="value[${id}]" id="spec" >
                </td>

                <td class="text-right"><a href="javascript:void(0)" onclick="destroy(${id})"><i class="fas fa-times"></i></a></td>
            </tr>`

    return comp
}