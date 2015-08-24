var ProjectSearchBar = new React.createClass({
    placeholder: "найдите свой  проект по ключевому слову или типу",
    arrowLinkClick: function(event) {
        var arrow = $(React.findDOMNode(this.refs.arrowIndicator));
        var dir = arrow.data('dir');
        var imageUrl = '/img/filter-arrow-up.png';
        if ('up' == dir) {
            arrow.data('dir', 'down');
        } else {
            imageUrl = '/img/filter-arrow-down.png';
            arrow.data('dir', 'up');
        }
        arrow.attr('src', imageUrl);
        event.preventDefault();
    },
    searchButtonClick: function(event) {
        event.preventDefault();
    },
    handleFocus: function() {
        var container = $(React.findDOMNode(this.refs.filterContainer));
        var textField = $(React.findDOMNode(this.refs.searchInput));
        container.css({"border": '4px solid #E7E7E7', "box-shadow": 'none'});
        textField.css('box-shadow', 'none');
        //textField.removeAttr('placeholder')
    },
    handleBlur: function() {
        var container = $(React.findDOMNode(this.refs.filterContainer));
        var textField = $(React.findDOMNode(this.refs.searchInput));
        container.css({"border": '4px solid #F3F3F3', "box-shadow": '0 1px 2px rgba(0, 0, 0, 0.2) inset'});
        textField.css('box-shadow', '1px 1px 2px rgba(0, 0, 0, 0.2) inset');
        /*if('' == textField.val()) {
            textField.attr('placeholder', this.placeholder);
        }*/
    },
    /*componentDidMount: function() {
     React.findDOMNode(this.refs.searchInput).placeholder = "Enter a Date";
     console.log(React.findDOMNode(this.refs.searchInput))
     },*/
    render: function() {
        return (
            React.createElement("table", null, 
                React.createElement("tbody", null, 
                React.createElement("tr", null, 
                    React.createElement("td", null, 
                        React.createElement("div", {ref: "filterContainer", className: "search-box-container"}, 
                            React.createElement("ul", {className: "tags", id: "filterbox"}), 
                            React.createElement("input", {ref: "searchInput", type: "text", placeholder: this.placeholder, onFocus: this.handleFocus, onBlur: this.handleBlur, onChange: this.handleChange, className: "placeholder"}), 
                            React.createElement("a", {href: "#", onClick: this.arrowLinkClick, className: "arrow-container"}, 
                                React.createElement("img", {ref: "arrowIndicator", className: "arrow-down", src: "/img/filter-arrow-down.png", "data-dir": "up", alt: "Раскрыть меню"})
                            ), 
                            React.createElement("a", {href: "#", id: "filterClear"})
                        )
                    ), 
                    React.createElement("td", null, 
                        React.createElement("a", {href: "#", onClick: this.searchButtonClick, className: "button clean-style-button start-search"}, "Поиск")
                    )
                )
                )
            )
        )
    }
});