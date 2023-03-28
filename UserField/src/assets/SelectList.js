(function(window, document) {
    function debounce(func, timeout = 300){
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    class AutoComplete {
        constructor({selector, initValues,inputName,multiple,ajaxUrl,timeout,ajaxData,token,sessid}) {
            this.selector = selector;
            this.selectedItems = [];
            this.initValues = initValues;
            this.inputName = inputName;
            this.multiple = multiple;
            this.ajaxUrl = ajaxUrl;
            this.timeout = timeout;
            this.ajaxData = ajaxData;
            this.token = token;
            this.sessid = sessid;
            this.init();
        }

        init() {
            this.initNodes();
            this.initInputEvents();
            this.initItems();
            this.searchRequest = debounce(this.request.bind(this),this.timeout);
            this.assignSearchItems(this.selectedItems)
        }

        initInputEvents() {
            this.container.querySelector(".auto-complete__input").addEventListener("click", () => this.input.focus())
            this.input.addEventListener("input", () => {
                this.input.size = this.input.value.length + 2;
                this.searchItems(this.input.value);
            });
            this.input.addEventListener("focus", () => {
                this.search.style.display = "block"
            });
            document.addEventListener("click", event => {
                const isClickInside = this.container.contains(event.target)

                if (!isClickInside) {
                    this.search.style.display = "none";
                }
            });
        }

        initNodes() {
            this.container = document.querySelector(this.selector);
            this.input = this.container.querySelector(".auto-complete__input-field");
            this.search = this.container.querySelector(".auto-complete__search");
        }

        initItems() {
            this.initValues.map(this.addItem.bind(this))
        }

        searchItems(query) {
            if(this.controller) {
                this.controller.abort();
            }
            this.searchRequest(query);
        }

        addItem(item) {
            const node = this.createNodeValue(item)
            const insertBeforeNode = this.container.querySelector(".auto-complete__input-field")
            const parentNode = this.container.querySelector(".auto-complete__input")

            parentNode.insertBefore(node, insertBeforeNode)

            node.querySelector(".auto-complete__input-selected-close").addEventListener("click", () => {
                this.removeItem(item.id);
                this.sayBitrixUpdate();
            });
            this.selectedItems.push(item);
            this.calculateSearchItems();
            this.addInputHidden(item.id);
        }

        addSearchItem(item) {
            const node = this.createNodeSearch(item);
            node.addEventListener("click",() => this.toggleSearchItem(item));
            this.container.querySelector(".auto-complete__search").appendChild(node);
        }

        addInputHidden(id) {
            const hiddenZero = this.container.querySelector(`.auto-complete__input-hidden[data-id="0"]`);
            if(hiddenZero) {
                hiddenZero.remove();
            }
            const node = document.createElement("input");
            node.type = "hidden";
            node.className = "auto-complete__input-hidden";
            node.dataset.id = id;
            node.name = this.inputName;
            node.value = id;
            this.container.appendChild(node);
        }

        toggleSearchItem(item) {
            const ids = this.selectedItems.map(e => e.id);
            if(this.multiple) {
                if(ids.includes(item.id)) {
                    this.removeItem(item.id);
                } else {
                    this.addItem(item);
                }
            } else {
                this.selectedItems.forEach(e => this.removeItem(e.id));
                if(!ids.includes(item.id)) {
                    this.addItem(item)
                }
            }
            this.sayBitrixUpdate();
        }

        assignSearchItems(items) {
            this.container.querySelector(".auto-complete__search").innerHTML = "";
            items.map(this.addSearchItem.bind(this));
            this.calculateSearchItems();
        }

        calculateSearchItems() {
            const ids = this.selectedItems.map(e => e.id);
            this.container.querySelector(".auto-complete__search").childNodes.forEach(e => {
                const checked = ids.includes(parseInt(e.dataset.id));

                if(checked) {
                    e.querySelector(".auto-complete__search-item-checkbox").classList.add("checked");
                } else {
                    e.querySelector(".auto-complete__search-item-checkbox").classList.remove("checked");
                }
            });
        }

        removeItem(id) {
            const ids = this.selectedItems.map(e => e.id);
            if(ids.includes(id)) {
                this.container.querySelector(`.auto-complete__input-selected[data-id="${id}"]`).remove();
                this.selectedItems = this.selectedItems.filter(e => e.id !== id);
                this.calculateSearchItems();
                this.container.querySelector(`.auto-complete__input-hidden[data-id="${id}"]`).remove();
                if(ids.length === 1) {
                    this.addInputHidden(0);
                    this.sayBitrixUpdate();
                }
            }
        }

        createNodeValue(item) {
            const node = document.createElement("div")
            node.className = "auto-complete__input-selected";
            node.dataset.id = item.id;
            node.innerHTML = `
                <div title="${item.title}" class="auto-complete__input-selected-value">${item.title}</div>
                <div class="auto-complete__input-selected-close"></div>`;
            return node;
        }

        createNodeSearch(item) {
            const node = document.createElement("div")
            node.className = "auto-complete__search-item";
            node.dataset.id = item.id;
            node.innerHTML = `
                <div class="auto-complete__search-item-checkbox"></div>
                <div class="auto-complete__search-item-value">${item.title}</div>`;

            return node;
        }

        setLoading(value = true) {
            // TODO: загрузка
        }

        sayBitrixUpdate() {
            this.container.querySelectorAll(".auto-complete__input-hidden").forEach(e => {
                BX.fireEvent(e,"change");
            });
        }

        request(search) {
            this.setLoading(true);

            this.controller = new AbortController();

            window.fetch(this.ajaxUrl,{
                method: "POST",
                body: window.JSON.stringify({
                    search: search,
                    ajaxData: this.ajaxData,
                    token: this.token,
                    sessid: this.sessid,
                }),
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                },
                signal: this.controller.signal
            })
                .then(r => r.json())
                .then(this.assignSearchItems.bind(this))
                .catch(e => {});
        }
    }

    window.AutoComplete = AutoComplete;

})(window, document)