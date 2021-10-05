(function() {
    var client = algoliasearch('2KK3NN704W', '461ccdf3575b68327b145eaf240d2be7');
    var index = client.initIndex('products_index');
    var enterPressed = false;
    let footer = $('.card-footer.foot')
    let totalProduct = $('#totalProduct')
    //initialize autocomplete on search input (ID selector must match)
    autocomplete('#aa-search-input',
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
            $('#tbody').append(temp(suggestion.id, suggestion.name))
             let item = $('#tbody tr').length
                if (item > 0) {
                    footer.removeClass('d-none')
                    footer.addClass('d-md-flex')
                    totalProduct.text(`${item} Product`)
                }
            enterPressed = true;
        })
})();
function clearThis(target) {
          target.value = ''
}

function destroy(id) {
    let footer = $('.card-footer.foot')

    let item = $(`tr#${id}`)
    item.remove()
    let th = $('#tbody tr').length

    if (th == 0) {
        footer.removeClass('d-md-flex')
        footer.addClass('d-none')
    } else {
        totalProduct.text(`${th} Product`)
    }
}

function temp(id, name) {
    let comp = `<tr id=${id}>
                <td>${name}</td>
                <td>
                    <div class=" d-flex border-bottom border-neutral px-0">
                        <button
                            onclick="var result = document.getElementById('sst${id}'); var sst = result.value; if( !isNaN( sst ) ) result.value--;return false;"
                            class="btn btn-sm shadow-none--hover mr-0" type="button">
                            <i class="fas fa-minus"></i>
                        </button>

                        <input class="form-control form-control-flush text-center " type="number"
                            name="qty[]" id="sst${id}" maxlength="5" value="1" class="input-text qty">

                        <input type="hidden" name="product_id[]" value="${id}">

                        <button
                            onclick="var result = document.getElementById('sst${id}'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                            class="btn btn-sm shadow-none--hover" type="button">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </td>
                  <td class="align-middle"><a href="javascript:void(0)" onclick="destroy(${id})"><i class="fas fa-times"></i></a></td>
            </tr>`

    return comp
}