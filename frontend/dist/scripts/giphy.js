alert('test');
const giphy_search = () => {
    const t = document.querySelector('input[name="giphy_search_query"]');
    t && document.querySelector('input[name="giphy_search_submit"]').addEventListener("click", e => {
        e.preventDefault();
        const s = `https://api.giphy.com/v1/gifs/search?api_key=${api_key}&q=${t.value}&limit=${api_limit}`;
        fetch(s).then(t => t.json()).then(e => {
            build_results_list(e.data), show_pagination(e, s)
        })
    });
    const clear = document.querySelector('button#clear-giphy-results');
    clear.addEventListener('click', (e) => {
        e.preventDefault();
        clear_results(t, document.querySelector('#giphy_search_results ul'));
        return document.querySelector('#giphy_search_results').classList.add('hidden');
    })
},
build_results_list = t => {
    if (t.length == 0) { results_messages(); return;}

    const e = document.querySelector("#giphy_search_results ul");
    const wrapper = document.querySelector('#giphy_search_results');

    e.innerHTML = '';
    
    for (d of t) {
        const t = d.images.original,
            s = document.createElement("li");
        s.classList.add("results"), s.classList.add("has-results"), s.innerHTML = `<div class="giphy-result"><img src="${t.url}" alt="${d.title} - gif from giphy" /></div>\n                        <div class="buttons button-group"><button type="button" class="button button-primary copy-gif" data-url="${t.url}" data-title="${d.title}">Copy Gif</button>`, e.appendChild(s)
        wrapper.classList.remove('hidden');
    }
    e.addEventListener('click', (e) => {
        e.preventDefault();
        if (!e.target.classList.contains('copy-gif')) return;
        const gif_url = e.target.dataset.url;
        const gif_title = e.target.dataset.title;
        copy_gif(gif_url, gif_title, e.target);
    });
},
copy_gif = (url, title, el) => {
    const gif_wrapper = document.createElement('div');
    gif_wrapper.setAttribute('class', 'giphy-gif');
    const img = document.createElement('img');
    img.setAttribute('src', url);
    img.setAttribute('alt', `${title} gif - pulled form Giphy API.`);
    gif_wrapper.appendChild(img);
    const storage = document.createElement('textarea');
    storage.setAttribute('style', 'display:none;');
    storage.value = gif_wrapper.outerHTML;
    document.body.appendChild(storage);
    navigator.clipboard.writeText(storage.value);
    document.body.removeChild(storage);
    el.setAttribute('disabled', 'disabled');
    el.innerText = 'Gif has been copied to the clipboard';
    el.classList.add('gif-copied')
    setTimeout(() => {
        el.classList.remove('gif-copied');
        el.removeAttribute('disabled');
        el.innerText = 'Copy Gif'
    }, 3000)
    return;
},
next_page = (btn, api_endpoint) => {
    btn.addEventListener('click', () => {
        let new_offset = btn.dataset.offset;
        btn.setAttribute('data-offset', (Number(new_offset) + Number(api_limit)));
        let new_endpoint = `${api_endpoint}&offset=${new_offset}`;
        fetch(new_endpoint)
            .then((res) => res.json())
            .then((data) => {
                if (data.pagination.total_count <= new_offset) {
                    clear_results(document.querySelector('input[name="giphy_search_query"]'), document.querySelector('#giphy_search_results ul'));
                }
                build_results_list(data.data);
            })
    });
},
show_pagination = (result, api_endpoint) => {
    const paginateBtn = document.querySelector('#giphy-next-page');
    if (!paginateBtn) return;
    if (result.pagination.total_count > 0) {
        paginateBtn.classList.remove('hidden');
        next_page(paginateBtn, api_endpoint);
    } else {
        paginateBtn.classList.add('hidden');
    }
},
clear_results = (term, list) => {
    term.value = '';
    return list.innerHTML = '';
},
results_messages = (msg = 'Unfortunately, your search returned no results. Many apologies.') => {
    alert(msg);
}
window.addEventListener("load", t => {
giphy_search()
});
