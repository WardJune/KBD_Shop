(function() {
    const search = instantsearch({
        appId: '2KK3NN704W',
        apiKey: '461ccdf3575b68327b145eaf240d2be7',
        indexName: 'products_index',
        urlSync: true
    });

    search.addWidget(
        instantsearch.widgets.hits({
            container: '#hits',
            cssClasses: {
                root: 'row',
                item: 'col-md-3'
            },
            templates: {
                empty: 'No results',
                item: function(item){
                    return `
                    <a href="${window.location.origin}/product/${item.slug}">
                        <div class="card bg-transparent shadow-none border-0 text-center">
                            <img class="card-img-top" src="${window.location.origin}/storage/${item.image}"
                                alt="Image placeholder">
                            <h5 class="h3 card-title mt-3 mb-0 font-weight-500">${item.name}</h5>
                            <h5 class="h3 text-warning  font-weight-normal">IDR ${item.price}
                            </h5>
                        </div>
                    </a>`
                }
            }
        })
    );

    search.addWidget(
        instantsearch.widgets.pagination({
            container: '#pagination',
            maxPages: 20,
            // default is to scroll to 'body', here we disable this behavior
            scrollTo: false
        })
    );

    search.addWidget(
        instantsearch.widgets.stats({
            container: '#stats-container',
        })
    );

    search.start()
})();